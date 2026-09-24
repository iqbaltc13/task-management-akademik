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
            $table->string('code_jenis_pelayanan', 255)->nullable()->after('group_id');
            $table->foreign('code_jenis_pelayanan')->references('code')->on('master_jenis_pelayanans');
        });
    }

    public function down(): void
    {
        $this->schema->table($this->table_name, function (Blueprint $table) {
            $table->dropForeign(['code_jenis_pelayanan']);
            $table->dropColumn('code_jenis_pelayanan');
        });
    }
};