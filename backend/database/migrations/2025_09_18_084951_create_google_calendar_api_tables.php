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
        Schema::create('calendars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('calendar_id');
            $table->string('summary')->nullable();
            $table->string('description')->nullable();
            $table->string('time_zone')->nullable();
            $table->string('calendar_etag')->nullable();
            $table->string('list_events_etag')->nullable();
            $table->timestamps();
        });

        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calendar_id')->references('id')->on('calendars')->onDelete('cascade')->onUpdate('cascade');
            $table->string('event_id');
            $table->string('summary')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->timestamps();
        });

        Schema::create('calendar_sync_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calendar_id')->references('id')->on('calendars')->onDelete('cascade')->onUpdate('cascade');
            $table->string('sync_token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendars');
        Schema::dropIfExists('events');
        Schema::dropIfExists('calendar_sync_tokens');
    }
};
