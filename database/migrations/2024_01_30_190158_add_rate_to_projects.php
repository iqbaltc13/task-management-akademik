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
        $this->table_name =  'projects';
        $this->schema = Schema::connection($this->getConnection());
    }

    public function up(): void
    {
        Schema::table($this->table_name, function (Blueprint $table) {
            $table->unsignedInteger('rate')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table($this->table_name, function (Blueprint $table) {
            $table->dropColumn('rate');
        });
    }
};
