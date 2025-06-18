<?php

declare(strict_types=1);

namespace Webkul\Purchase\Filament\Admin\Clusters\Orders\Resources\OrderResource\Actions;

use Exception;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Livewire\Component;
use Webkul\Account\Models\Partner;
use Webkul\Purchase\Enums\OrderState;
use Webkul\Purchase\Facades\PurchaseOrder;
use Webkul\Purchase\Models\Order;

final class SendEmailAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $userName = Auth::user()->name;

        $acceptRespondUrl = URL::signedRoute('purchases.quotations.respond', [
            'order' => $this->getRecord()->id,
            'action' => 'accept',
        ]);

        $declineRespondUrl = URL::signedRoute('purchases.quotations.respond', [
            'order' => $this->getRecord()->id,
            'action' => 'decline',
        ]);

        $this
            ->label(__('purchases::filament/admin/clusters/orders/resources/order/actions/send-email.label'))
            ->label(fn () => $this->getRecord()->state === OrderState::DRAFT ? __('purchases::filament/admin/clusters/orders/resources/order/actions/send-email.label') : __('purchases::filament/admin/clusters/orders/resources/order/actions/send-email.resend-label'))
            ->schema([
                Select::make('vendors')
                    ->label(__('purchases::filament/admin/clusters/orders/resources/order/actions/send-email.form.fields.to'))
                    ->options(Partner::get()->mapWithKeys(fn ($partner) => [
                        $partner->id => $partner->email
                            ? "{$partner->name} <{$partner->email}>"
                            : $partner->name,
                    ])->toArray())
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->default(fn (): array => [$this->getRecord()->partner_id]),
                TextInput::make('subject')
                    ->label(__('purchases::filament/admin/clusters/orders/resources/order/actions/send-email.form.fields.subject'))
                    ->required()
                    ->default("Purchase Order #{$this->getRecord()->name}"),
                MarkdownEditor::make('message')
                    ->label(__('purchases::filament/admin/clusters/orders/resources/order/actions/send-email.form.fields.message'))
                    ->required()
                    ->default(<<<MD
Dear {$this->getRecord()->partner->name}  

Here is in attachment a request for quotation **{$this->getRecord()->name}**.  

If you have any questions, please do not hesitate to contact us.  

[Accept]({$acceptRespondUrl}) | [Decline]({$declineRespondUrl})  

Best regards,  

--  
{$userName}  
MD),
                FileUpload::make('attachment')
                    ->hiddenLabel()
                    ->disk('public')
                    ->default(fn () => PurchaseOrder::generateRFQPdf($this->getRecord()))
                    ->downloadable()
                    ->openable(),
            ])
            ->action(function (array $data, Order $record, Component $livewire): void {
                try {
                    $record = PurchaseOrder::sendRFQ($record, $data);
                } catch (Exception $e) {
                    Notification::make()
                        ->body($e->getMessage())
                        ->danger()
                        ->send();

                    return;
                }

                $livewire->updateForm();

                Notification::make()
                    ->title(__('purchases::filament/admin/clusters/orders/resources/order/actions/send-email.action.notification.success.title'))
                    ->body(__('purchases::filament/admin/clusters/orders/resources/order/actions/send-email.action.notification.success.body'))
                    ->success()
                    ->send();
            })
            ->color(fn (): string => $this->getRecord()->state === OrderState::DRAFT ? 'primary' : 'gray')
            ->visible(fn (): bool => in_array($this->getRecord()->state, [
                OrderState::DRAFT,
                OrderState::SENT,
            ], true));
    }

    public static function getDefaultName(): ?string
    {
        return 'purchases.orders.send-email';
    }
}
