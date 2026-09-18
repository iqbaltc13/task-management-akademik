<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function __construct()
    {
        $this->table_name =  'task_assigned_user_update_logs';
        $this->schema = Schema::connection($this->getConnection());
    }
    public function up(): void
    {
        $this->schema->create($this->table_name, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('task_id',255)->constrained()->cascadeOnDelete();
            $table->string('old_assigned_to_user_id',255)->nullable()->constrained('users')->nullOnDelete();
            $table->string('new_assigned_to_user_id',255)->nullable()->constrained('users')->nullOnDelete();
            $table->string('changed_by_user_id',255)->nullable()->constrained('users')->nullOnDelete();
            $table->text('feedback')->nullable();
            $table->dateTime('archived_at')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->schema->dropIfExists($this->table_name);
    }
};
