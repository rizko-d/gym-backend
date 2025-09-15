<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gym_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('duration_minutes');
            $table->integer('max_participants');
            $table->decimal('price', 8, 2)->default(0);
            $table->enum('difficulty_level', ['beginner', 'intermediate', 'advanced']);
            $table->json('equipment_needed')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gym_classes');
    }
};
