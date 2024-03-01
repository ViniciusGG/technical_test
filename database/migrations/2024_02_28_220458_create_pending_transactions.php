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
        Schema::create('pending_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('bank_accounts');
            $table->foreignId('receiver_id')->constrained('bank_accounts');
            $table->float('amount');
            $table->enum('status', ['pending', 'authorized', 'rejected'])->default('pending');
            $table->date('data_scheduled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_transactions');
    }
};
