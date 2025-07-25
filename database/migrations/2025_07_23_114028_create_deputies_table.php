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
            // chaves e paginação
            $table->id();
            $table->unsignedBigInteger('external_id')->unique();
            $table->unsignedBigInteger('legislature_id')->index();
            $table->unsignedInteger('page')->nullable();
            $table->unsignedInteger('per_page')->nullable();

            // dados básicos
            $table->string('name', 255)->index();
            $table->string('civil_name', 255)->nullable();
            $table->string('electoral_name', 255)->nullable();
            $table->char('state_code', 2)->index();
            $table->string('party_acronym', 25)->index();
            $table->enum('gender', GenderEnum::getValues())->index();
            $table->string('cpf', 11)->nullable();
            $table->date('birth_date')->nullable();
            $table->char('birth_state', 2)->nullable();
            $table->string('birth_city', 100)->nullable();
            $table->date('death_date')->nullable();
            $table->string('education_level', 50)->nullable();

            // contatos e links
            $table->string('email', 255)->nullable();
            $table->string('website_url', 255)->nullable();
            $table->json('social_links')->nullable();
            $table->string('uri', 255)->nullable();
            $table->string('party_uri', 255)->nullable();
            $table->string('photo_url', 255)->nullable();

            // filtros e ordenação
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('order_direction', ['asc', 'desc'])->default('asc');
            $table->string('order_by', 50)->default('name');
            $table->string('accept_header', 100)->nullable();

            // último status
            $table->unsignedBigInteger('status_id')->nullable();
            $table->string('status_uri', 255)->nullable();
            $table->date('status_date')->nullable();
            $table->string('status_situation', 50)->nullable();
            $table->string('status_condition', 50)->nullable();
            $table->text('status_description')->nullable();

            // gabinete
            $table->string('office_name', 50)->nullable();
            $table->string('office_building', 50)->nullable();
            $table->string('office_room', 50)->nullable();
            $table->string('office_floor', 10)->nullable();
            $table->string('office_phone', 20)->nullable();
            $table->string('office_email', 255)->nullable();

            $table->decimal('total_expenses', 19, 4)->default(0);

            // timestamps e índices compostos
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
