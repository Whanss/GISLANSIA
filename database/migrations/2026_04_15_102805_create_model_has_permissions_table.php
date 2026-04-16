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
        Schema::create("model_has_permissions", function (Blueprint $table) {
            $table->unsignedBigInteger("permission_id");
            $table->string("model_type");
            $table->unsignedBigInteger("model_id");

            $table
                ->foreign("permission_id")
                ->references("id")
                ->on("permissions")
                ->onDelete("cascade");

            // This assumes you might have other models that can have permissions.
            // If it's strictly users and lansias, you could add specific foreign keys.
            // For a generic approach, we'll just store the type and ID.

            $table->primary(["permission_id", "model_id", "model_type"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("model_has_permissions");
    }
};
