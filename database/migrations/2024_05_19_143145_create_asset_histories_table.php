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
        Schema::create('asset_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->nullable()->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('amount');
            $table->enum('change_type', ['increase', 'decrease', 'convert']);
            $table->foreignId('from_asset_id')->nullable()->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('to_asset_id')->nullable()->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_histories');
    }
};
