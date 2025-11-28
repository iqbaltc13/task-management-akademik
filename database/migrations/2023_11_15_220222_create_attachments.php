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
        $this->table_name =  'attachments';
        $this->schema = Schema::connection($this->getConnection());
    }

    public function up(): void
    {
        Schema::create($this->table_name, function (Blueprint $table) {
            $table->string('id',255)->primary();
            $table->string('task_id',255)->nullable();
            $table->string('user_id',255)->nullable();
            $table->string('name')->nullable();
            $table->string('path')->nullable();
            $table->string('thumb')->nullable();
            $table->string('type')->nullable();
            $table->integer('size')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
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
