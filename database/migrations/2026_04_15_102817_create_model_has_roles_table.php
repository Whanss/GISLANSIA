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
        Schema::create("model_has_roles", function (Blueprint $table) {
            $table->unsignedBigInteger("role_id");
            $table->string("model_type");
            $table->unsignedBigInteger("model_id");

            $table
                ->foreign("role_id")
                ->references("id")
                ->on("roles")
                ->onDelete("cascade");

            // This assumes you might have other models that can have roles.
            // If it's strictly users and lansias, you could add specific foreign keys.
            // For a generic approach, we'll just store the type and ID.

            $table->primary(["role_id", "model_id", "model_type"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("model_has_roles");
    }
};
