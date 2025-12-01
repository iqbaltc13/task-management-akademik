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
        $this->table_name =  'users';
        $this->schema = Schema::connection($this->getConnection());
    }

    public function up(): void
    {
        $this->schema->create($this->table_name, function (Blueprint $table) {
            $table->string('id',255)->primary();
            $table->string('name')->nullable();
            $table->string('username')->unique()->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->string('phone')->nullable();
            $table->string('job_title')->nullable();
            $table->unsignedInteger('rate')->nullable();
            $table->string('google_id')->nullable();
            $table->rememberToken();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('archived_at')->nullable();
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
