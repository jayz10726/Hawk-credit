<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('organization_id')->nullable(); // null = super admin
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('national_id')->unique()->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('employment_status',
                ['employed','self_employed','unemployed','retired'])->default('employed');
            $table->decimal('monthly_income', 12, 2)->nullable();
            $table->string('password');
            $table->string('profile_photo_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('two_factor_enabled')->default(false);
            $table->string('two_factor_secret')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('organization_id')
                  ->references('id')->on('organizations')->nullOnDelete();
            $table->index(['organization_id', 'is_active']);
        });
    }
    public function down(): void { Schema::dropIfExists('users'); }
};

