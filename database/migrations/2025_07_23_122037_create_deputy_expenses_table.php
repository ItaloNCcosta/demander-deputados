<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('deputy_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deputy_id')
                ->constrained('deputies')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->unsignedBigInteger('external_id');
            $table->unsignedBigInteger('legislature_id')->nullable()->index();
            $table->unsignedSmallInteger('year')->index();
            $table->unsignedTinyInteger('month')->index();
            $table->string('supplier_tax_id', 14)->nullable()->index();
            $table->string('supplier_name', 255)->nullable();
            $table->unsignedBigInteger('document_code')->index();
            $table->unsignedBigInteger('batch_code')->nullable()->index();
            $table->unsignedBigInteger('document_type_code')->nullable();
            $table->date('document_date')->nullable();
            $table->string('document_number', 100)->nullable();
            $table->string('reimbursement_number', 100)->nullable();
            $table->unsignedInteger('installment')->nullable();
            $table->string('expense_type', 255)->nullable();
            $table->string('document_type', 255)->nullable();
            $table->string('document_url', 255)->nullable();
            $table->decimal('document_amount', 15, 2)->default(0);
            $table->decimal('disallowed_amount', 15, 2)->default(0);
            $table->decimal('net_amount', 15, 2)->default(0);
            $table->unsignedInteger('page')->nullable();
            $table->unsignedInteger('per_page')->nullable();
            $table->enum('order_direction', ['asc', 'desc'])->default('asc');
            $table->string('order_by', 100)->default('year');
            $table->string('accept_header', 100)->nullable();
            $table->timestamps();
            $table->timestamp('last_synced_at')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deputy_expenses');
    }
};
