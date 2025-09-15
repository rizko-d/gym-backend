<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('member_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->foreignId('class_schedule_id')->constrained()->onDelete('cascade');
            $table->timestamp('booked_at');
            $table->enum('status', ['booked', 'attended', 'missed', 'cancelled'])->default('booked');
            $table->timestamps();
            
            $table->unique(['member_id', 'class_schedule_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('member_classes');
    }
};
