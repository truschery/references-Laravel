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
        Schema::create('swifts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('swift_code')->unique();
            $table->string('bank_name');
            $table->string('country');
            $table->string('city');
            $table->string('address');
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
        Schema::dropIfExists('swifts');
    }
};
