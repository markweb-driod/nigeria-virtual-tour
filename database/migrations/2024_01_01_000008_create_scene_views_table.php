<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('scene_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_session_id')->constrained()->onDelete('cascade');
            $table->foreignId('scene_id')->constrained()->onDelete('cascade');
            $table->timestamp('viewed_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('scene_views'); }
};
