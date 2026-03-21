    <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('penalties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_id');
            $table->unsignedBigInteger('installment_id');
            $table->unsignedBigInteger('organization_id');
            $table->smallInteger('days_overdue');
            $table->decimal('penalty_rate', 5, 4);  // 0.0050 = 0.5%/day
            $table->decimal('penalty_amount', 10, 2);
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->enum('status',['pending','paid','waived','partial'])->default('pending');
            $table->unsignedBigInteger('waived_by')->nullable();
            $table->text('waiver_reason')->nullable();
            $table->timestamp('waived_at')->nullable();
            $table->timestamps();
            $table->foreign('loan_id')->references('id')->on('loans');
            $table->foreign('installment_id')->references('id')->on('loan_installments');
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->morphs('auditable');  // auditable_type + auditable_id
            $table->string('event', 100);  // e.g. credit_request.approved
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('tags')->nullable();
            $table->timestamp('created_at'); // NO updated_at — immutable!
            $table->index(['organization_id', 'event']);
        });

        Schema::create('credit_score_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('organization_id');
            $table->smallInteger('old_score');
            $table->smallInteger('new_score');
            $table->smallInteger('delta');  // new - old
            $table->string('trigger_event', 100);
            $table->json('factor_breakdown')->nullable();
            $table->timestamp('created_at');  // immutable
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->index(['user_id', 'created_at']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('credit_score_history');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('penalties');
    }
};

