<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('reference_code', 30)->unique();
            $table->unsignedBigInteger('credit_request_id')->unique(); // 1-to-1
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('organization_id');
            $table->decimal('principal_amount', 12, 2);
            $table->decimal('interest_rate', 5, 2);
            $table->decimal('interest_amount', 12, 2);
            $table->decimal('total_payable', 12, 2);  // principal + interest
            $table->decimal('total_paid', 12, 2)->default(0);
            $table->decimal('outstanding_balance', 12, 2);
            $table->decimal('monthly_installment', 10, 2);
            $table->tinyInteger('tenure_months');
            $table->date('start_date');
            $table->date('end_date');
            $table->date('next_due_date')->nullable();
            $table->date('last_payment_date')->nullable();
            $table->enum('status',['active','completed','defaulted','written_off','restructured'])
                  ->default('active');
            $table->decimal('penalty_balance', 10, 2)->default(0);
            $table->timestamps();
            $table->foreign('credit_request_id')->references('id')->on('credit_requests');
            $table->foreign('user_id')->references('id')->on('users');
            $table->index(['organization_id', 'status']);
            $table->index('next_due_date');
        });
    }
    public function down(): void { Schema::dropIfExists('loans'); }
};
