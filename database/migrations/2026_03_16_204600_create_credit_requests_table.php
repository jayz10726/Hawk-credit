<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('credit_requests', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('reference_code', 30)->unique(); // e.g. CR-2024-00001
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->decimal('amount_requested', 12, 2);
            $table->decimal('amount_approved', 12, 2)->nullable();
            $table->string('purpose');
            $table->text('purpose_details')->nullable();
            $table->tinyInteger('tenure_months');
            $table->decimal('interest_rate', 5, 2)->nullable();
            $table->enum('status',
                ['draft','submitted','under_review','approved','rejected','disbursed','cancelled'])
                ->default('draft');
            $table->tinyInteger('current_stage')->default(1);
            $table->smallInteger('score_at_application')->nullable();
            $table->tinyInteger('fraud_score')->default(0); // 0-100 risk signal
            $table->text('rejection_reason')->nullable();
            $table->text('review_notes')->nullable();
            $table->json('documents')->nullable();
            $table->timestamp('disbursed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('organization_id')->references('id')->on('organizations');
            $table->index(['organization_id', 'status']);
            $table->index(['user_id', 'status']);
        });
 }
    public function down(): void { Schema::dropIfExists('credit_requests'); }
};
