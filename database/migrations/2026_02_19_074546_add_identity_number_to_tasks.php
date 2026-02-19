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
            $table->string('identity_number')->after('code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->schema->table($this->table_name, function (Blueprint $table) {
            $table->dropColumn('identity_number');
        });
    }
};
