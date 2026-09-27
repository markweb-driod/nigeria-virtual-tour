<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('hotspots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scene_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['scene', 'info', 'media']);
            $table->float('yaw');
            $table->float('pitch');
            $table->string('label');
            $table->text('description')->nullable();
            $table->foreignId('target_scene_id')->nullable()->constrained('scenes')->onDelete('set null');
            $table->string('media_path')->nullable();
            $table->string('media_type')->nullable();
            $table->string('icon')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('hotspots'); }
};
