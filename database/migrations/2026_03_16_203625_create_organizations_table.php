<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('logo_path')->nullable();
            $table->enum('status', ['active','suspended','pending'])->default('pending');
            $table->enum('subscription_tier', ['basic','professional','enterprise'])->default('basic');
            $table->decimal('credit_pool', 15, 2)->default(0);
            $table->decimal('available_credit_pool', 15, 2)->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void { Schema::dropIfExists('organizations'); }
};

