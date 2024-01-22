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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->dateTime('start_date_time');
            $table->string('address');
            $table->unsignedInteger('tickets_quantity');
            $table->unsignedInteger('available_tickets_quantity');
            $table->decimal('total_income', 8, 2)->nullable();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('event_category_id')->constrained();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
