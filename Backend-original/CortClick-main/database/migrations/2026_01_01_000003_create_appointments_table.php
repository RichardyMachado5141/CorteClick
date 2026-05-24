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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade')->comment('ID do cliente');
            $table->foreignId('professional_id')->constrained('professionals')->onDelete('cascade')->comment('ID do profissional');
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade')->comment('ID do serviço');
            $table->dateTime('appointment_date')->comment('Data e hora do agendamento');
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending')->comment('Status do agendamento');
            $table->text('notes')->nullable()->comment('Observações');
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes para melhor performance
            $table->index('client_id');
            $table->index('professional_id');
            $table->index('appointment_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
