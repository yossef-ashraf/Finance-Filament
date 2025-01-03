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
        Schema::create('credits', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('amount');
            $table->text('note')->nullable();
            $table->integer('type')->default(1);
            // $table->dateTime('date');
            $table->foreignId('month_id')->constrained()->onDelete('cascade'); // Foreign key referencing months table
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credits');
    }
};
