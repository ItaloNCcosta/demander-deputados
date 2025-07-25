<?php

declare(strict_types=1);

namespace App\Services\DeputyExpense;

use App\Models\Deputy;
use App\Models\DeputyExpense;
use Illuminate\Support\Facades\Log;

final class DeputyExpenseUpsertService
{
    public function upsert(int $externalDeputyId, array $data): void
    {
        // Resolve o deputy_id interno
        $deputyId = Deputy::where('external_id', $externalDeputyId)->value('id');

        if (!$deputyId) {
            Log::error("Deputy not found for external_id: {$externalDeputyId}");
            throw new \Exception("Deputy not found for external_id: {$externalDeputyId}");
        }

        $documentCode = (int) ($data['codDocumento'] ?? 0);

        if ($documentCode === 0) {
            Log::warning("Invalid document code for deputy {$externalDeputyId}", $data);
            return;
        }

        try {
            $expense = DeputyExpense::updateOrCreate(
                [
                    'deputy_id' => $deputyId,
                    'external_id' => $externalDeputyId,
                    'document_code' => $documentCode,
                ],
                $this->mapExpenseData($data)
            );
            
            $action = $expense->wasRecentlyCreated ? 'created' : 'updated';
            Log::debug("Expense {$action} for deputy {$externalDeputyId}: document {$documentCode}");
        } catch (\Exception $e) {
            Log::error("Failed to upsert expense for deputy {$externalDeputyId}, document {$documentCode}: " . $e->getMessage());
            throw $e;
        }
    }

    private function mapExpenseData(array $data): array
    {
        return [
            'legislature_id' => $data['idLegislatura'] ?? null,
            'year' => $data['ano'] ?? null,
            'month' => $data['mes'] ?? null,
            'supplier_tax_id' => $data['cnpjCpfFornecedor'] ?? null,
            'supplier_name' => $data['nomeFornecedor'] ?? null,
            'batch_code' => $data['codLote'] ?? null,
            'document_type_code' => $data['codTipoDocumento'] ?? null,
            'document_date' => $data['dataDocumento'] ?? null,
            'document_number' => $data['numDocumento'] ?? null,
            'reimbursement_number' => $data['numRessarcimento'] ?? null,
            'installment' => $data['parcela'] ?? null,
            'expense_type' => $data['tipoDespesa'] ?? null,
            'document_type' => $data['tipoDocumento'] ?? null,
            'document_url' => $data['urlDocumento'] ?? null,
            'document_amount' => $data['valorDocumento'] ?? 0,
            'disallowed_amount' => $data['valorGlosa'] ?? 0,
            'net_amount' => $data['valorLiquido'] ?? 0,
            'last_synced_at' => now(),
        ];
    }
}
