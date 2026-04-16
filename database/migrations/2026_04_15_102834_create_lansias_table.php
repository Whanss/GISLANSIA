<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("lansias", function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string("nik");
            $table->string("nama");
            $table->string("foto")->nullable();
            $table->string("status");
            $table->string("rw");
            $table->string("rt");
            $table->string("alamat");
            $table->string("desa");
            $table->string("kecamatan");
            $table->string("kabupaten");
            $table->string("provinsi");
            $table->string("umur");
            $table->date("tanggal_lahir");
            $table->string("latitude")->nullable();
            $table->string("longitude")->nullable();
            $table->boolean("read_data")->default(false);
            $table->boolean("add_data")->default(false);
            $table->boolean("update_data")->default(false);
            $table->boolean("delete_data")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("lansias");
    }
};
