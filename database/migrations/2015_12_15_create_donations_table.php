<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('food_name');
            $table->string('food_photo')->nullable();
            $table->decimal('estimated_weight', 8, 2);
            $table->string('nutritional_value')->nullable();
            
            $table->date('expiry_date');
            $table->string('predicted_expiry')->nullable();
            $table->enum('condition', ['Fresh', 'Busuk'])->default('Fresh');
            
            $table->enum('food_type', ['Upload Foto', 'Analisis AI'])->default('Upload Foto');
            $table->text('address');
            $table->string('city');
            $table->string('selected_foodbank')->nullable();
            $table->decimal('foodbank_distance', 8, 2)->nullable();
            
            $table->date('pickup_date');
            $table->time('pickup_time');
            $table->string('contact_number');
            
            $table->json('ai_analysis')->nullable();
            
            $table->enum('status', ['pending', 'approved', 'picked_up', 'completed', 'cancelled'])->default('pending');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};