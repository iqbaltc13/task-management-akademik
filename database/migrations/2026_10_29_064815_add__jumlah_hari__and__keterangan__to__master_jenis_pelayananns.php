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
        $this->table_name = 'master_jenis_pelayanans';;
        $this->schema = Schema::connection($this->getConnection());
    }

    public function up(): void
    {
        $this->schema->table($this->table_name, function (Blueprint $table) {
            $table->integer('jumlah_hari')->nullable()->after('name');
            $table->text('keterangan')->nullable()->after('jumlah_hari');
        });
    }

    public function down(): void
    {
        $this->schema->table($this->table_name, function (Blueprint $table) {
            $table->dropColumn('keterangan');
            $table->dropColumn('jumlah_hari');
        });
    }
};
