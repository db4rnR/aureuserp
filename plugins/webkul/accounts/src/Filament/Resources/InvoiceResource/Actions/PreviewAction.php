<?php

declare(strict_types=1);

namespace Webkul\Account\Filament\Resources\InvoiceResource\Actions;

use Filament\Actions\Action;
use InvalidArgumentException;
use Webkul\Account\Enums\MoveState;
use Webkul\Account\Models\Move;
use Webkul\Support\Traits\PDFHandler;

class PreviewAction extends Action
{
    use PDFHandler;

    private string $template = '';

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(__('accounts::filament/resources/invoice/actions/preview.title'))
            ->color('gray')
            ->visible(fn (Move $record): bool => $record->state === MoveState::POSTED)
            ->icon('heroicon-o-viewfinder-circle')
            ->modalHeading(__('accounts::filament/resources/invoice/actions/preview.modal.title'))
            ->modalSubmitAction(false)
            ->modalContent(fn ($record) => view($this->template, ['record' => $record]));
    }

    public static function getDefaultName(): ?string
    {
        return 'customers.invoice.preview';
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function setTemplate(string $template): static
    {
        if (! view()->exists($template)) {
            throw new InvalidArgumentException("The view [{$template}] does not exist.");
        }

        $this->template = $template;

        return $this;
    }
}
