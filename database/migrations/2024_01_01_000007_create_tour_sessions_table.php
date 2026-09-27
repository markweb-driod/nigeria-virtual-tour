<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tour_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attraction_id')->constrained()->onDelete('cascade');
            $table->string('session_uuid')->unique();
            $table->enum('device_type', ['mobile', 'tablet', 'desktop'])->default('desktop');
            $table->string('browser')->nullable();
            $table->string('os')->nullable();
            $table->float('avg_fps')->nullable();
            $table->float('load_time_ms')->nullable();
            $table->float('transition_time_ms')->nullable();
            $table->integer('scenes_visited')->default(0);
            $table->boolean('completed')->default(false);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('tour_sessions'); }
};
