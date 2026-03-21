<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('repayments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('reference_code', 30)->unique();
            $table->unsignedBigInteger('loan_id');
            $table->unsignedBigInteger('installment_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('organization_id');
            $table->decimal('amount', 10, 2);
            $table->decimal('principal_applied', 10, 2)->default(0);
            $table->decimal('interest_applied', 10, 2)->default(0);
            $table->decimal('penalty_applied', 10, 2)->default(0);
            $table->enum('payment_method',['cash','bank_transfer','mobile_money','card','cheque']);
            $table->string('payment_reference')->nullable(); // M-Pesa/bank ref
            $table->enum('status',['pending','confirmed','failed','reversed'])->default('pending');
            $table->unsignedBigInteger('confirmed_by')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('paid_at');
            $table->timestamps();
            $table->foreign('loan_id')->references('id')->on('loans');
            $table->index(['organization_id', 'status']);
            $table->index('paid_at');
        });
    }
    public function down(): void { Schema::dropIfExists('repayments'); }
};
