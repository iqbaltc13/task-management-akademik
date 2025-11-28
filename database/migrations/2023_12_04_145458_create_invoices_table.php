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
        $this->table_name =  'invoices';
        $this->schema = Schema::connection($this->getConnection());
    }

    public function up(): void
    {
        Schema::create($this->table_name, function (Blueprint $table) {
            $table->string('id',255)->primary();
            $table->string('client_company_id',255)->nullable();
            $table->string('created_by_user_id',255)->nullable();
            $table->string('number')->nullable();
            $table->string('status')->nullable();
            $table->string('type')->nullable();
            $table->text('note')->nullable();
            $table->unsignedInteger('amount')->nullable();
            $table->unsignedInteger('amount_with_tax')->nullable();
            $table->unsignedInteger('hourly_rate')->nullable();
            $table->date('due_date')->nullable();
            $table->string('filename')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('archived_at')->nullable();

            $table->foreign('created_by_user_id')->references('id')->on('users');
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
