<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function __construct()
    {
        $this->table_name = 'tasks';
        $this->schema = Schema::connection($this->getConnection());
    }

    public function up(): void
    {
        $this->schema->table($this->table_name, function (Blueprint $table) {
            $table->string('approved_by', 255)->nullable();
            $table->index('approved_by');
        });
    }

    public function down(): void
    {
        $this->schema->table($this->table_name, function (Blueprint $table) {
            $table->dropIndex(['approved_by']);
            $table->dropColumn('approved_by');
        });
    }
};