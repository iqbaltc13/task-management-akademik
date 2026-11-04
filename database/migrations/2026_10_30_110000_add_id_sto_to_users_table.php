<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function __construct()
    {
        $this->table_name = 'users';
        $this->schema = Schema::connection($this->getConnection());
    }

    public function up(): void
    {
        $this->schema->table($this->table_name, function (Blueprint $table) {
            $table->string('code_sto', 255)->nullable()->after('job_sub_title');
            $table->index('code_sto');
            $table->foreign('code_sto')->references('code')->on('master_stos')
                ->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    public function down(): void
    {
        $this->schema->table($this->table_name, function (Blueprint $table) {
            $table->dropForeign(['code_sto']);
            $table->dropColumn('code_sto');
        });
    }
};