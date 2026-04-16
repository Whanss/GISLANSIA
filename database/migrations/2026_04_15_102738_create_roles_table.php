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
        Schema::create("roles", function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string("name");
            $table->string("guard_name");
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
        Schema::dropIfExists("roles");
    }
};
