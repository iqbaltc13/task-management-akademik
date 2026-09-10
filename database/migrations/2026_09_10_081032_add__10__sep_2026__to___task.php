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
        $this->table_name =  'tasks';
        $this->schema = Schema::connection($this->getConnection());
    }

    public function up(): void
    {
        $this->schema->table($this->table_name, function (Blueprint $table) {
            $table->string('email',255)->nullable();
            $table->renameColumn('result_description','final_feedback');
            $table->string('link_file_requirement',255)->nullable();
            $table->string('link_file_result',255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->schema->table($this->table_name, function (Blueprint $table) {
            $table->dropColumn('email',255)->nullable();
            $table->dropColumn('final_feedback','result_description');
            $table->dropColumn('link_file_requirement',255)->nullable();
            $table->dropColumn('link_file_result',255)->nullable();
        });
    }
};
