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
use Livewire\Component;
use Webkul\Account\Models\Partner;
use Webkul\Purchase\Enums\OrderState;
use Webkul\Purchase\Facades\PurchaseOrder;
use Webkul\Purchase\Models\Order;

class SendPOEmailAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $userName = Auth::user()->name;

        $this
            ->label(__('purchases::filament/admin/clusters/orders/resources/order/actions/send-po-email.label'))
            ->schema([
                Select::make('vendors')->label(__('purchases::filament/admin/clusters/orders/resources/order/actions/send-po-email.form.fields.to'))
                    ->options(Partner::get()->mapWithKeys(fn ($partner) => [
                        $partner->id => $partner->email
                            ? "{$partner->name} <{$partner->email}>"
                            : $partner->name,
                    ])->toArray())
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->default(fn (): array => [$this->getRecord()->partner_id]),
                TextInput::make('subject')->label(__('purchases::filament/admin/clusters/orders/resources/order/actions/send-po-email.form.fields.subject'))
                    ->required()
                    ->default("Purchase Order #{$this->getRecord()->name}"),
                MarkdownEditor::make('message')->label(__('purchases::filament/admin/clusters/orders/resources/order/actions/send-po-email.form.fields.message'))
                    ->required()
                    ->default(<<<MD
Dear **{$this->getRecord()->partner->name}**

Here is in attachment a purchase order **{$this->getRecord()->name}** amounting to **{$this->getRecord()->total_amount}**.

The receipt is expected for **{$this->getRecord()->planned_at}**.

Could you please acknowledge the receipt of this order?

Best regards,

--
{$userName}
MD),
                FileUpload::make('attachment')->hiddenLabel()
                    ->disk('public')
                    ->default(fn () => PurchaseOrder::generatePurchaseOrderPdf($this->getRecord()))
                    ->downloadable()
                    ->openable(),
            ])
            ->action(function (array $data, Order $record, Component $livewire): void {
                try {
                    $record = PurchaseOrder::sendPurchaseOrder($record, $data);
                } catch (Exception $e) {
                    Notification::make()->body($e->getMessage())
                        ->danger()
                        ->send();

                    return;
                }

                $livewire->updateForm();

                Notification::make()->title(__('purchases::filament/admin/clusters/orders/resources/order/actions/send-po-email.action.notification.success.title'))
                    ->body(__('purchases::filament/admin/clusters/orders/resources/order/actions/send-po-email.action.notification.success.body'))
                    ->success()
                    ->send();
            })
            ->color(fn (): string => $this->getRecord()->state === OrderState::DRAFT ? 'primary' : 'gray')
            ->visible(fn (): bool => $this->getRecord()->state === OrderState::PURCHASE);
    }

    public static function getDefaultName(): ?string
    {
        return 'purchases.orders.send-po-email';
    }
}
