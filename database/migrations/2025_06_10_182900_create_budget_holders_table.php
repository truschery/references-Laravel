<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('budget_holders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('tin')->unique();
            $table->string('name');
            $table->string('region');
            $table->string('district');
            $table->string('address');
            $table->string('phone');
            $table->string('responsible');
            $table->foreignUuid('created_by');
            $table->foreignUuid('updated_by');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_holders');
    }
};
