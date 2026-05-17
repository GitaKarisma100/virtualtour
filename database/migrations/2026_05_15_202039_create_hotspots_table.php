<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotspots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();
            $table->foreignId('target_location_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->float('yaw')->default(0);
            $table->float('pitch')->default(0);
            $table->string('label')->nullable();
            $table->enum('type', ['navigation', 'info', 'external_link'])->default('navigation');
            $table->string('icon')->nullable();
            $table->string('url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotspots');
    }
};
