<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function __construct()
    {
        $this->table_name = 'task_groups';
        $this->schema = Schema::connection($this->getConnection());
    }

    public function up(): void
    {
        $this->schema->dropIfExists($this->table_name);

        $this->schema->create($this->table_name, function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });
    }

    public function down(): void
    {
        $this->schema->dropIfExists($this->table_name);

        $this->schema->create($this->table_name, function (Blueprint $table) {
            $table->string('id', 255)->primary();
            $table->string('uuid')->unique();
            $table->text('connection')->nullable();
            $table->text('queue')->nullable();
            $table->longText('payload')->nullable();
            $table->longText('exception')->nullable();
            $table->dateTime('failed_at')->useCurrent()->nullable();
        });
    }
};