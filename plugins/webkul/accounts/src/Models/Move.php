<?php

declare(strict_types=1);

namespace Webkul\Account\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Webkul\Account\Enums\JournalType;
use Webkul\Account\Enums\MoveState;
use Webkul\Account\Enums\MoveType;
use Webkul\Account\Enums\PaymentState;
use Webkul\Chatter\Traits\HasChatter;
use Webkul\Chatter\Traits\HasLogActivity;
use Webkul\Field\Traits\HasCustomFields;
use Webkul\Partner\Models\BankAccount;
use Webkul\Partner\Models\Partner;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;
use Webkul\Support\Models\Currency;
use Webkul\Support\Models\UtmCampaign;
use Webkul\Support\Models\UTMMedium;
use Webkul\Support\Models\UTMSource;

class Move extends Model implements Sortable
{
    use HasChatter, HasCustomFields, HasFactory, HasLogActivity, SortableTrait;

    public $sortable = [
        'order_column_name' => 'sort',
        'sort_when_creating' => true,
    ];

    protected $table = 'accounts_account_moves';

    protected $fillable = [
        'sort',
        'journal_id',
        'company_id',
        'campaign_id',
        'tax_cash_basis_origin_move_id',
        'auto_post_origin_id',
        'secure_sequence_number',
        'invoice_payment_term_id',
        'partner_id',
        'commercial_partner_id',
        'partner_shipping_id',
        'partner_bank_id',
        'fiscal_position_id',
        'currency_id',
        'reversed_entry_id',
        'invoice_user_id',
        'invoice_incoterm_id',
        'invoice_cash_rounding_id',
        'preferred_payment_method_line_id',
        'creator_id',
        'sequence_prefix',
        'access_token',
        'name',
        'reference',
        'state',
        'move_type',
        'auto_post',
        'inalterable_hash',
        'payment_reference',
        'qr_code_method',
        'payment_state',
        'invoice_source_email',
        'invoice_partner_display_name',
        'invoice_origin',
        'incoterm_location',
        'date',
        'auto_post_until',
        'invoice_date',
        'invoice_date_due',
        'delivery_date',
        'sending_data',
        'narration',
        'invoice_currency_rate',
        'amount_untaxed',
        'amount_tax',
        'amount_total',
        'amount_residual',
        'amount_untaxed_signed',
        'amount_untaxed_in_currency_signed',
        'amount_tax_signed',
        'amount_total_signed',
        'amount_total_in_currency_signed',
        'amount_residual_signed',
        'quick_edit_total_amount',
        'is_storno',
        'always_tax_exigible',
        'checked',
        'posted_before',
        'made_sequence_gap',
        'is_manually_modified',
        'is_move_sent',
        'source_id',
        'medium_id',
    ];

    protected array $logAttributes = [
        'medium.name' => 'Medium',
        'source.name' => 'UTM Source',
        'partner.name' => 'Customer',
        'commercialPartner.name' => 'Commercial Partner',
        'partnerShipping.name' => 'Shipping Address',
        'partnerBank.name' => 'Bank Account',
        'fiscalPosition.name' => 'Fiscal Position',
        'currency.name' => 'Currency',
        'reversedEntry.name' => 'Reversed Entry',
        'invoiceUser.name' => 'Invoice User',
        'invoiceIncoterm.name' => 'Invoice Incoterm',
        'invoiceCashRounding.name' => 'Invoice Cash Rounding',
        'createdBy.name' => 'Created By',
        'name' => 'Invoice Reference',
        'state' => 'Invoice Status',
        'reference' => 'Reference',
        'invoiceSourceEmail' => 'Source Email',
        'invoicePartnerDisplayName' => 'Partner Display Name',
        'invoiceOrigin' => 'Invoice Origin',
        'incotermLocation' => 'Incoterm Location',
        'date' => 'Invoice Date',
        'invoice_date' => 'Invoice Date',
        'invoice_date_due' => 'Due Date',
        'delivery_date' => 'Delivery Date',
        'narration' => 'Notes',
        'amount_untaxed' => 'Subtotal',
        'amount_tax' => 'Tax',
        'amount_total' => 'Total',
        'amount_residual' => 'Residual',
        'amount_untaxed_signed' => 'Subtotal (Signed)',
        'amount_untaxed_in_currency_signed' => 'Subtotal (In Currency) (Signed)',
        'amount_tax_signed' => 'Tax (Signed)',
        'amount_total_signed' => 'Total (Signed)',
        'amount_total_in_currency_signed' => 'Total (In Currency) (Signed)',
        'amount_residual_signed' => 'Residual (Signed)',
        'quick_edit_total_amount' => 'Quick Edit Total Amount',
        'is_storno' => 'Is Storno',
        'always_tax_exigible' => 'Always Tax Exigible',
        'checked' => 'Checked',
        'posted_before' => 'Posted Before',
        'made_sequence_gap' => 'Made Sequence Gap',
        'is_manually_modified' => 'Is Manually Modified',
        'is_move_sent' => 'Is Move Sent',
    ];

    protected $casts = [
        'invoice_date_due' => 'datetime',
        'state' => MoveState::class,
        'payment_state' => PaymentState::class,
        'move_type' => MoveType::class,
    ];

    public function campaign()
    {
        return $this->belongsTo(UtmCampaign::class, 'campaign_id');
    }

    public function journal()
    {
        return $this->belongsTo(Journal::class, 'journal_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function taxCashBasisOriginMove()
    {
        return $this->belongsTo(self::class, 'tax_cash_basis_origin_move_id');
    }

    public function autoPostOrigin()
    {
        return $this->belongsTo(self::class, 'auto_post_origin_id');
    }

    public function invoicePaymentTerm()
    {
        return $this->belongsTo(PaymentTerm::class, 'invoice_payment_term_id')->withTrashed();
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }

    public function commercialPartner()
    {
        return $this->belongsTo(Partner::class, 'commercial_partner_id');
    }

    public function partnerShipping()
    {
        return $this->belongsTo(Partner::class, 'partner_shipping_id');
    }

    public function partnerBank()
    {
        return $this->belongsTo(BankAccount::class, 'partner_bank_id')->withTrashed();
    }

    public function fiscalPosition()
    {
        return $this->belongsTo(FiscalPosition::class, 'fiscal_position_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function reversedEntry()
    {
        return $this->belongsTo(self::class, 'reversed_entry_id');
    }

    public function invoiceUser()
    {
        return $this->belongsTo(User::class, 'invoice_user_id');
    }

    public function invoiceIncoterm()
    {
        return $this->belongsTo(Incoterm::class, 'invoice_incoterm_id');
    }

    public function invoiceCashRounding()
    {
        return $this->belongsTo(CashRounding::class, 'invoice_cash_rounding_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function source()
    {
        return $this->belongsTo(UTMSource::class, 'source_id');
    }

    public function medium()
    {
        return $this->belongsTo(UTMMedium::class, 'medium_id');
    }

    public function paymentMethodLine()
    {
        return $this->belongsTo(PaymentMethodLine::class, 'preferred_payment_method_line_id');
    }

    public function getTotalDiscountAttribute()
    {
        return $this->lines()
            ->where('display_type', 'product')
            ->sum('discount');
    }

    public function isInbound($includeReceipts = true): bool
    {
        return in_array($this->move_type, $this->getInboundTypes($includeReceipts), true);
    }

    public function getInboundTypes($includeReceipts = true): array
    {
        $types = [MoveType::OUT_INVOICE, MoveType::IN_REFUND];

        if ($includeReceipts) {
            $types[] = MoveType::OUT_RECEIPT;
        }

        return $types;
    }

    public function isOutbound($includeReceipts = true): bool
    {
        return in_array($this->move_type, $this->getOutboundTypes($includeReceipts), true);
    }

    public function getOutboundTypes($includeReceipts = true): array
    {
        $types = [MoveType::IN_INVOICE, MoveType::OUT_REFUND];

        if ($includeReceipts) {
            $types[] = MoveType::IN_RECEIPT;
        }

        return $types;
    }

    public function lines()
    {
        return $this->hasMany(MoveLine::class, 'move_id')
            ->where('display_type', 'product');
    }

    public function allLines()
    {
        return $this->hasMany(MoveLine::class, 'move_id');
    }

    public function taxLines()
    {
        return $this->hasMany(MoveLine::class, 'move_id')
            ->where('display_type', 'tax');
    }

    public function paymentTermLine()
    {
        return $this->hasOne(MoveLine::class)
            ->where('display_type', 'payment_term');
    }

    public function isInvoice($includeReceipts = false): bool
    {
        if ($this->isSaleDocument($includeReceipts)) {
            return true;
        }

        return $this->isPurchaseDocument($includeReceipts);
    }

    public function isEntry(): bool
    {
        return $this->move_type === MoveType::ENTRY;
    }

    public function getSaleTypes($includeReceipts = false): array
    {
        return $includeReceipts
            ? [MoveType::OUT_INVOICE, MoveType::OUT_REFUND, MoveType::OUT_RECEIPT]
            : [MoveType::OUT_INVOICE, MoveType::OUT_REFUND];
    }

    public function isSaleDocument($includeReceipts = false): bool
    {
        return in_array($this->move_type, $this->getSaleTypes($includeReceipts), true);
    }

    public function isPurchaseDocument($includeReceipts = false): bool
    {
        return in_array($this->move_type, $includeReceipts ? [
            MoveType::IN_INVOICE,
            MoveType::IN_REFUND,
            MoveType::IN_RECEIPT,
        ] : [MoveType::IN_INVOICE, MoveType::IN_REFUND], true);
    }

    public function getValidJournalTypes(): array
    {
        if ($this->isSaleDocument(true)) {
            return [JournalType::SALE];
        }
        if ($this->isPurchaseDocument(true)) {
            return [JournalType::PURCHASE];
        }
        if ($this->origin_payment_id || $this->statement_line_id) {
            return [JournalType::BANK, JournalType::CASH, JournalType::CREDIT_CARD];
        }

        return [JournalType::GENERAL];

    }

    /**
     * Update the full name without triggering additional events
     */
    public function updateSequencePrefix(): void
    {
        $suffix = date('Y').'/'.date('m');

        $this->sequence_prefix = match ($this->move_type) {
            MoveType::OUT_INVOICE => 'INV/'.$suffix,
            MoveType::OUT_REFUND => 'RINV/'.$suffix,
            MoveType::IN_INVOICE => 'BILL/'.$suffix,
            MoveType::IN_REFUND => 'RBILL/'.$suffix,
            default => $suffix,
        };
    }

    /**
     * Bootstrap any application services.
     */
    protected static function boot(): void
    {
        parent::boot();

        self::creating(function ($model): void {
            $model->creator_id = auth()->id();
        });

        self::created(function ($model): void {
            $model->updateSequencePrefix();

            $model->updateQuietly([
                'name' => $model->sequence_prefix.'/'.$model->id,
            ]);
        });
    }
}
