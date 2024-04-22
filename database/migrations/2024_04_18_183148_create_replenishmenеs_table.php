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
        Schema::create('replenishmenеs', function (Blueprint $table) {
            $table->id();
            $table->text('id_user');
            $table->text('id_replenishment');
            $table->text('sum_replenishment');
            $table->text('service');
            $table->boolean('status');
            $table->text('end_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('replenishmenеs');
    }
};
