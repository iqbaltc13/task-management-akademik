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
        $this->schema->table($this->table_name, function (Blueprint $table) {
            $table->string('code', 255)->nullable()->after('order_column');
           
        });
    }

    public function down(): void
    {
        $this->schema->table($this->table_name, function (Blueprint $table) {
            $table->dropColumn('code');
        });
    }
};