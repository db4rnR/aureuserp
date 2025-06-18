<?php

declare(strict_types=1);

namespace Webkul\Account\Filament\Resources\BillResource\Actions;

use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Auth;
use Webkul\Account\Enums\DisplayType;
use Webkul\Account\Enums\MoveState;
use Webkul\Account\Enums\MoveType;
use Webkul\Account\Enums\PaymentState;
use Webkul\Account\Facades\Account as AccountFacade;
use Webkul\Account\Models\Move;
use Webkul\Account\Models\MoveLine;
use Webkul\Account\Models\MoveReversal;
use Webkul\Invoice\Filament\Clusters\Vendors\Resources\RefundResource;

final class CreditNoteAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(__('Credit Note'))
            ->color('gray')
            ->visible(fn (Move $record): bool => $record->state === MoveState::POSTED)
            ->icon('heroicon-o-receipt-refund')
            ->modalHeading(__('Credit Note'));

        $this->schema(
            fn (Schema $schema): Schema => $schema->components([
                Textarea::make('reason')
                    ->label(__('Reason displayed on Credit Note'))
                    ->required(),
                DatePicker::make('date')
                    ->label(__('Reason displayed on Credit Note'))
                    ->default(now())
                    ->native(false)
                    ->required(),
            ])
        );

        $this->action(function (Move $record, array $data, $livewire): void {
            $user = Auth::user();

            $creditNote = MoveReversal::create([
                'reason' => $data['reason'],
                'date' => $data['date'],
                'company_id' => $record->company_id,
                'creator_id' => $user->id,
            ]);

            $creditNote->moves()->attach($record);

            $move = $this->createMove($creditNote, $record);

            AccountFacade::computeAccountMove($move);

            $redirectUrl = RefundResource::getUrl('edit', ['record' => $move->id]);

            $livewire->redirect($redirectUrl, navigate: FilamentView::hasSpaMode());
        });
    }

    public static function getDefaultName(): ?string
    {
        return 'customers.invoice.credit-note';
    }

    private function createMove(MoveReversal $creditNote, Move $record): Move
    {
        $newMove = $record->replicate()->fill([
            'reference' => "Reversal of: {$record->name}, {$creditNote->reason}",
            'reversed_entry_id' => $record->id,
            'state' => MoveState::DRAFT,
            'move_type' => MoveType::IN_REFUND,
            'payment_state' => PaymentState::NOT_PAID,
            'auto_post' => 0,
        ]);

        $newMove->save();

        $creditNote->newMoves()->attach($newMove->id);

        $this->createMoveLines($newMove, $record);

        return $newMove;
    }

    private function createMoveLines(Move $newMove, Move $record): void
    {
        $record->lines->each(function (MoveLine $line) use ($newMove, $record): void {
            if ($line->display_type === DisplayType::PRODUCT) {
                $newMoveLine = $line->replicate()->fill([
                    'state' => $newMove->state,
                    'reference' => $record->reference,
                    'move_id' => $newMove->id,
                ]);

                $newMoveLine->save();

                $newMoveLine->taxes()->sync($line->taxes->pluck('id'));
            }
        });
    }
}
