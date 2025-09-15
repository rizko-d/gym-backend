<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('class_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gym_class_id')->constrained()->onDelete('cascade');
            $table->foreignId('trainer_id')->constrained()->onDelete('cascade');
            $table->date('class_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('room')->nullable();
            $table->integer('current_participants')->default(0);
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled'])->default('scheduled');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['trainer_id', 'class_date', 'start_time']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('class_schedules');
    }
};
