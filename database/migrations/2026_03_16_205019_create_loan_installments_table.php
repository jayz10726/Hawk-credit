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
        Schema::create('loan_installments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('organization_id');
            $table->tinyInteger('installment_number'); // 1, 2, 3...
            $table->date('due_date');
            $table->decimal('principal_component', 10, 2);
            $table->decimal('interest_component', 10, 2);
            $table->decimal('amount_due', 10, 2);
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->decimal('penalty_amount', 10, 2)->default(0);
            $table->enum('status',['pending','paid','partial','overdue','waived'])->default('pending');
            $table->smallInteger('days_overdue')->default(0);
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->foreign('loan_id')->references('id')->on('loans')->cascadeOnDelete();
            $table->unique(['loan_id', 'installment_number']);
            $table->index(['organization_id', 'status']);
            $table->index('due_date');
        });
    }
    public function down(): void { Schema::dropIfExists('loan_installments'); }
};
