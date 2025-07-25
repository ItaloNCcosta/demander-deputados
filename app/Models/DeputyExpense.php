<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ExpenseTypeEnum;
use Illuminate\Database\Eloquent\Model;

final class DeputyExpense extends Model
{
    protected $fillable = [
        'deputy_id',
        'external_id',
        'legislature_id',
        'year',
        'month',
        'supplier_tax_id',
        'supplier_name',
        'document_code',
        'batch_code',
        'document_type_code',
        'document_date',
        'document_number',
        'reimbursement_number',
        'installment',
        'expense_type',
        'document_type',
        'document_url',
        'document_amount',
        'disallowed_amount',
        'net_amount',
        'page',
        'per_page',
        'order_direction',
        'order_by',
        'accept_header',
        'last_synced_at'
    ];

    protected $casts = [
        'deputy_id'           => 'integer',
        'external_id'         => 'integer',
        'legislature_id'      => 'integer',
        'year'                => 'integer',
        'month'               => 'integer',
        'document_code'       => 'integer',
        'batch_code'          => 'integer',
        'document_type_code'  => 'integer',
        'document_date'       => 'date',
        'installment'         => 'integer',
        'document_amount'     => 'decimal:2',
        'disallowed_amount'   => 'decimal:2',
        'net_amount'          => 'decimal:2',
        'page'                => 'integer',
        'per_page'            => 'integer',
        'last_synced_at'      => 'datetime',
        // 'expense_type' => ExpenseTypeEnum::class,
    ];

    public function deputy()
    {
        return $this->belongsTo(Deputy::class);
    }
}
