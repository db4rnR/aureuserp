<?php

declare(strict_types=1);

namespace Webkul\Sale\Filament\Clusters\Orders\Resources\QuotationResource\Actions;

use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Storage;
use Webkul\Partner\Models\Partner;
use Webkul\Sale\Enums\OrderState;
use Webkul\Sale\Facades\SaleOrder;
use Webkul\Sale\Models\Order;

class SendByEmailAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->beforeFormFilled(function (Order $record, Action $action): void {
                $pdf = Pdf::loadView('sales::sales.quotation', ['record' => $record])
                    ->setPaper('A4', 'portrait')
                    ->setOption('defaultFont', 'Arial');

                $fileName = "$record->name-".time().'.pdf';
                $filePath = 'sales-orders/'.$fileName;

                Storage::disk('public')->put($filePath, $pdf->output());

                $action->fillForm([
                    'file' => $filePath,
                    'partners' => [$record->partner_id],
                    'subject' => $record->partner->name.' Quotation (Ref '.$record->name.')',
                    'description' => 'Dear '.$record->partner->name.', <br/><br/>Your quotation <strong>'.$record->name.'</strong> amounting in <strong>'.$record->currency->symbol.' '.$record->amount_total.'</strong> is ready for review.<br/><br/>Should you have any questions or require further assistance, please feel free to reach out to us.',
                ]);
            })
            ->label(__('sales::filament/clusters/orders/resources/quotation/actions/send-by-email.title'))
            ->schema(
                fn (Form $form): Form => $form->schema([
                    Select::make('partners')->options(Partner::all()->pluck('name', 'id'))
                        ->multiple()
                        ->label(__('sales::filament/clusters/orders/resources/quotation/actions/send-by-email.form.fields.partners'))
                        ->searchable()
                        ->preload(),
                    TextInput::make('subject')->label(__('sales::filament/clusters/orders/resources/quotation/actions/send-by-email.form.fields.subject'))
                        ->hiddenLabel(),
                    RichEditor::make('description')->label(__('sales::filament/clusters/orders/resources/quotation/actions/send-by-email.form.fields.description'))
                        ->hiddenLabel(),
                    FileUpload::make('file')->label(__('sales::filament/clusters/orders/resources/quotation/actions/send-by-email.form.fields.attachment'))
                        ->downloadable()
                        ->openable()
                        ->disk('public')
                        ->hiddenLabel(),
                ])
            )
            ->modalIcon('heroicon-s-envelope')
            ->modalHeading(__('sales::filament/clusters/orders/resources/quotation/actions/send-by-email.modal.heading'))
            ->hidden(fn (Order $record): bool => $record->state !== OrderState::SALE)
            ->action(function (Order $record, array $data): void {
                SaleOrder::sendQuotationOrOrderByEmail($record, $data);

                $this->refreshFormData(['state']);

                Notification::make()->success()
                    ->title(__('sales::filament/clusters/orders/resources/quotation/actions/send-by-email.actions.notification.title'))
                    ->body(__('sales::filament/clusters/orders/resources/quotation/actions/send-by-email.actions.notification.body'))
                    ->send();
            });
    }

    public static function getDefaultName(): ?string
    {
        return 'orders.sales.send-by-email';
    }
}
