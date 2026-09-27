<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('attractions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('state_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('short_description');
            $table->text('description');
            $table->longText('history')->nullable();
            $table->text('interesting_facts')->nullable();
            $table->string('address')->nullable();
            $table->string('lga')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('audio')->nullable();
            $table->string('visiting_hours')->nullable();
            $table->string('entry_fee')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('tour_available')->default(false);
            $table->enum('status', ['published', 'draft', 'archived'])->default('draft');
            $table->integer('view_count')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('attractions'); }
};
