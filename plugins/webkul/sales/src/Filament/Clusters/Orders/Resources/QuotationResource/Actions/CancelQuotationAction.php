<?php

declare(strict_types=1);

namespace Webkul\Sale\Filament\Clusters\Orders\Resources\QuotationResource\Actions;

use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Form;
use Webkul\Partner\Models\Partner;
use Webkul\Sale\Enums\OrderState;
use Webkul\Sale\Facades\SaleOrder;

class CancelQuotationAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->color('gray')
            ->label(__('sales::filament/clusters/orders/resources/quotation/actions/cancel-quotation.title'))
            ->modalIcon('heroicon-s-x-circle')
            ->modalHeading(__('sales::filament/clusters/orders/resources/quotation/actions/cancel-quotation.modal.heading'))
            ->modalDescription(__('sales::filament/clusters/orders/resources/quotation/actions/cancel-quotation.modal.description'))
            ->modalFooterActions(fn ($record, $livewire): array => [
                Action::make('sendAndCancel')->label(__('sales::filament/clusters/orders/resources/quotation/actions/cancel-quotation.footer-actions.send-and-cancel.title'))
                    ->icon('heroicon-o-envelope')
                    ->modalIcon('heroicon-s-envelope')
                    ->action(function ($data) use ($record, $livewire): void {
                        SaleOrder::cancelSaleOrder($record, $livewire->mountedActionsData[0] ?? []);

                        $livewire->refreshFormData(['state']);

                        Notification::make()->success()
                            ->title(__('sales::filament/clusters/orders/resources/quotation/actions/cancel-quotation.footer-actions.send-and-cancel.notification.cancelled.title'))
                            ->body(__('sales::filament/clusters/orders/resources/quotation/actions/cancel-quotation.footer-actions.send-and-cancel.notification.cancelled.body'))
                            ->send();
                    })
                    ->cancelParentActions(),
                Action::make('cancel')->label(__('sales::filament/clusters/orders/resources/quotation/actions/cancel-quotation.footer-actions.cancel.title'))
                    ->icon('heroicon-o-x-circle')
                    ->modalIcon('heroicon-s-x-circle')
                    ->action(function () use ($record, $livewire): void {
                        SaleOrder::cancelSaleOrder($record);

                        $livewire->refreshFormData(['state']);

                        Notification::make()->success()
                            ->title(__('sales::filament/clusters/orders/resources/quotation/actions/cancel-quotation.footer-actions.cancel.notification.cancelled.title'))
                            ->body(__('sales::filament/clusters/orders/resources/quotation/actions/cancel-quotation.footer-actions.cancel.notification.cancelled.body'))
                            ->send();
                    })
                    ->cancelParentActions(),
                Action::make('close')->color('gray')
                    ->label(__('sales::filament/clusters/orders/resources/quotation/actions/cancel-quotation.footer-actions.close.title'))
                    ->cancelParentActions(),
            ])
            ->schema(
                fn (Form $form, $record): Form => $form->schema([
                    Select::make('partners')->options(Partner::all()->pluck('name', 'id'))
                        ->multiple()
                        ->default([$record->partner_id])
                        ->searchable()
                        ->label(__('sales::filament/clusters/orders/resources/quotation/actions/cancel-quotation.form.fields.partner'))
                        ->preload(),
                    TextInput::make('subject')->default(fn () => __('sales::filament/clusters/orders/resources/quotation/actions/cancel-quotation.form.fields.subject-default', [
                            'name' => $record->name,
                            'id' => $record->id,
                        ]))
                        ->placeholder(__('sales::filament/clusters/orders/resources/quotation/actions/cancel-quotation.form.fields.subject-placeholder'))
                        ->label(__('sales::filament/clusters/orders/resources/quotation/actions/cancel-quotation.form.fields.subject'))
                        ->hiddenLabel(),
                    RichEditor::make('description')->label(__('sales::filament/clusters/orders/resources/quotation/actions/cancel-quotation.form.fields.description'))
                        ->default(fn () => __('sales::filament/clusters/orders/resources/quotation/actions/cancel-quotation.form.fields.description-default', [
                            'partner_name' => $record?->partner?->name,
                            'name' => $record?->name,
                        ]))
                        ->hiddenLabel(),
                ])
            )
            ->hidden(fn ($record): bool => ! in_array($record->state, [OrderState::DRAFT, OrderState::SENT, OrderState::SALE], true));
    }

    public static function getDefaultName(): ?string
    {
        return 'orders.sales.cancel';
    }
}
