<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('credit_scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique(); // 1-to-1 with users
            $table->unsignedBigInteger('organization_id');
            $table->smallInteger('score')->default(300); // 300–850
            $table->enum('band',['very_poor','poor','fair','good','excellent','exceptional'])
                  ->default('poor');
            $table->enum('risk_category',['very_high','high','medium','low'])
                  ->default('high');
            $table->decimal('credit_limit', 12, 2)->default(0);
            $table->decimal('available_credit', 12, 2)->default(0);
            $table->integer('on_time_payments')->default(0);
            $table->integer('late_payments')->default(0);
            $table->integer('missed_payments')->default(0);
            $table->tinyInteger('active_loans_count')->default(0);
            $table->decimal('total_borrowed', 12, 2)->default(0);
            $table->decimal('total_repaid', 12, 2)->default(0);
            $table->decimal('debt_to_income_ratio', 5, 4)->default(0);
            $table->timestamp('last_calculated_at')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->index('score');
            $table->index('risk_category');
        });
    }
    public function down(): void { Schema::dropIfExists('credit_scores'); }
};
