<?php

use App\Enums\GenderEnum;
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
        Schema::create('deputies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('external_id')->unique();
            $table->unsignedBigInteger('legislature_id')->index();
            $table->string('name', 255)->index();
            $table->char('state_code', 2)->index();
            $table->string('party_acronym', 10)->index();
            $table->enum('gender', GenderEnum::getValues())->index();
            $table->string('email', 255)->nullable();
            $table->string('uri', 255)->nullable();
            $table->string('party_uri', 255)->nullable();
            $table->string('photo_url', 255)->nullable();
            $table->unsignedInteger('page')->nullable();
            $table->unsignedInteger('per_page')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('order_direction', ['asc', 'desc'])->default('asc');
            $table->string('order_by', 50)->default('name');
            $table->string('accept_header', 100)->nullable();
            $table->timestamps();
            $table->index(['party_acronym', 'legislature_id']);
            $table->index(['state_code', 'legislature_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deputies');
    }
};
