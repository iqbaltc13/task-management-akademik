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
        $this->table_name =  'tasks';
        $this->schema = Schema::connection($this->getConnection());
    }

    public function up(): void
    {
        Schema::create($this->table_name, function (Blueprint $table) {
            $table->string('id',255)->primary();
            $table->string('project_id',255)->nullable();
            $table->string('group_id',255)->nullable();
            $table->string('created_by_user_id',255)->nullable();
            $table->string('assigned_to_user_id',255)->nullable();
            $table->string('invoice_id',255)->nullable();
            $table->string('name')->nullable();
            $table->unsignedInteger('number')->nullable();
            $table->text('description')->nullable();
            $table->date('due_on')->nullable();
            $table->decimal('estimation', 6, 2)->unsigned()->nullable();
            $table->boolean('hidden_from_clients')->default(false);
            $table->boolean('billable')->default(true);
            $table->unsignedInteger('order_column');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('archived_at')->nullable();
            $table->dateTime('assigned_at')->nullable();
            $table->dateTime('completed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->table_name);
    }
};
