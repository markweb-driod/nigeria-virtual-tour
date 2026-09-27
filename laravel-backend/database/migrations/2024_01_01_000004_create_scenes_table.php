<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('scenes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attraction_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug');
            $table->string('panorama_path')->nullable();
            $table->string('thumbnail')->nullable();
            $table->float('initial_yaw')->default(0);
            $table->float('initial_pitch')->default(0);
            $table->float('initial_hfov')->default(100);
            $table->integer('sort_order')->default(0);
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->text('description')->nullable();
            $table->string('audio_narration')->nullable();
            $table->boolean('is_default')->default(false);
            $table->enum('status', ['published', 'draft'])->default('draft');
            $table->timestamps();
            $table->unique(['attraction_id', 'slug']);
        });
    }
    public function down(): void { Schema::dropIfExists('scenes'); }
};
