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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('professional_id')->constrained('professionals')->onDelete('cascade');
            $table->string('name')->comment('Nome do serviço');
            $table->decimal('price', 10, 2)->comment('Preço do serviço');
            $table->integer('duration')->comment('Duração em minutos');
            $table->text('description')->nullable()->comment('Descrição do serviço');
            $table->boolean('is_active')->default(true)->comment('Se o serviço está ativo');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
