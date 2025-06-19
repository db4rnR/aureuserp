<?php

declare(strict_types=1);

namespace Webkul\Account\Filament\Resources\InvoiceResource\Actions;

use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Webkul\Account\Enums\MoveState;
use Webkul\Account\Facades\Account;
use Webkul\Account\Models\Move;
use Webkul\Account\Models\Partner;
use Webkul\Support\Traits\PDFHandler;

class PrintAndSendAction extends Action
{
    use PDFHandler;

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(__('accounts::filament/resources/invoice/actions/print-and-send.title'))
            ->color('gray')
            ->visible(fn (Move $record): bool => $record->state === MoveState::CANCEL
            || $record->state === MoveState::POSTED);

        $this->beforeFormFilled(function ($record, Action $action): void {
            $description = "
                    <p>Dear {$record->partner->name},</p>
                    <p>Your invoice <strong>{$record->name}</strong> from <strong>{$record->company->name}</strong> for <strong>{$record->currency->symbol} {$record->amount_total}</strong> is now available. Kindly arrange payment at your earliest convenience.</p>
                    <p>When making the payment, please reference <strong>{$record->name}</strong> for account <strong>".($record->partnerBank->bank->name ?? 'N/A').'</strong>.</p>
                    <p>If you have any questions, feel free to reach out.</p>
                    <p><strong>Best regards,</strong><br>Administrator</p>
                ';

            $action->fillForm([
                'files' => $this->prepareInvoice($record),
                'partners' => [$record->partner_id],
                'subject' => $record->partner->name.' Invoice (Ref '.$record->name.')',
                'description' => $description,
            ]);
        });

        $this->schema(
            fn (Form $form): Form => $form->schema([
                Select::make('partners')->options(Partner::all()->pluck('name', 'id'))
                    ->multiple()
                    ->label(__('accounts::filament/resources/invoice/actions/print-and-send.modal.form.partners'))
                    ->searchable()
                    ->preload(),
                TextInput::make('subject')->label(__('accounts::filament/resources/invoice/actions/print-and-send.modal.form.subject'))
                    ->hiddenLabel(),
                RichEditor::make('description')->label(__('accounts::filament/resources/invoice/actions/print-and-send.modal.form.description'))
                    ->hiddenLabel(),
                FileUpload::make('files')->label(__('accounts::filament/resources/invoice/actions/print-and-send.modal.form.files'))
                    ->downloadable()
                    ->openable()
                    ->multiple()
                    ->disk('public')
                    ->hiddenLabel(),
            ])
        );

        $this->modalSubmitActionLabel(__('accounts::filament/resources/invoice/actions/print-and-send.modal.action.submit.title'));
        $this->modalIcon('heroicon-m-paper-airplane');
        $this->icon('heroicon-o-envelope');
        $this->action(function (Move $record, array $data): void {
            Account::printAndSend($record, $data);
        });
        $this->modalSubmitAction(function ($action): void {
            $action->label(__('accounts::filament/resources/invoice/actions/print-and-send.modal.action.submit.title'));
            $action->icon('heroicon-m-paper-airplane');
        });
    }

    public static function getDefaultName(): ?string
    {
        return 'customers.invoice.print-and-send';
    }

    private function prepareInvoice(Move $record): ?string
    {
        return $this->savePDF(
            view('accounts::invoice/actions/preview.index', ['record' => $record])->render(),
            'invoice-'.$record->created_at->format('d-m-Y')
        );
    }
}
