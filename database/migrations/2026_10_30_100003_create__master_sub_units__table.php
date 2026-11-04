<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function __construct()
    {
        $this->table_name = 'master_sub_units';
        $this->schema = Schema::connection($this->getConnection());
    }

    public function up(): void
    {
        $this->schema->create($this->table_name, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 255)->unique();
            $table->string('name', 255);
            $table->string('master_unit_code', 255);

            $table->dateTime('archived_at')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();

            $table->index('master_unit_code');
            $table->foreign('master_unit_code')->references('code')->on('master_units')
                ->cascadeOnUpdate()->restrictOnDelete();

        });
    }

    public function down(): void
    {
        $this->schema->dropIfExists($this->table_name);
    }
};
