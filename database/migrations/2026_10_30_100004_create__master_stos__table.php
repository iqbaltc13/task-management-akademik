<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function __construct()
    {
        $this->table_name = 'master_stos';
        $this->schema = Schema::connection($this->getConnection());
    }

    public function up(): void
    {
        $this->schema->create($this->table_name, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 255);
            $table->string('code_parent', 255)->nullable();
            $table->string('name', 255);
            $table->unsignedBigInteger('id_unit')->nullable();
            $table->unsignedBigInteger('id_sub_unit')->nullable();
            $table->boolean('is_pimpinan')->default(false);
            $table->integer('level')->default(1);
            $table->unsignedBigInteger('id_jabatan')->nullable();

            $table->dateTime('archived_at')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();

            $table->index('code_parent');
            $table->index('id_unit');
            $table->index('id_sub_unit');
            $table->index('id_jabatan');
            $table->index('level');
            $table->unique('code'); 
            $table->foreign('code_parent')->references('code')->on('master_stos')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('id_unit')->references('id')->on('master_units')->restrictOnDelete();
            $table->foreign('id_sub_unit')->references('id')->on('master_sub_units')->restrictOnDelete();
            $table->foreign('id_jabatan')->references('id')->on('master_jabatans')->restrictOnDelete();

        });
    }

    public function down(): void
    {
        $this->schema->dropIfExists($this->table_name);
    }
};
