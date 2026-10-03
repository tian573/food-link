<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    protected $fillable = [
        'user_id',
        'food_name',
        'food_photo',
        'estimated_weight',
        'nutritional_value',
        'expiry_date',
        'predicted_expiry',
        'condition',
        'food_type',
        'address',
        'city',
        'selected_foodbank',
        'foodbank_distance',
        'pickup_date',
        'pickup_time',
        'contact_number',
        'ai_analysis',
        'status',
    ];
    
    protected $casts = [
        'expiry_date' => 'date',
        'pickup_date' => 'date',
        'ai_analysis' => 'array',
        'estimated_weight' => 'decimal:2',
        'foodbank_distance' => 'decimal:2',
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function getAiNutritionAttribute()
    {
        if (!$this->ai_analysis) {
            return null;
        }
        
        return $this->ai_analysis['nutritionFacts'] ?? null;
    }
    
    public function getAiExpiryAttribute()
    {
        if (!$this->ai_analysis) {
            return null;
        }
        
        return $this->ai_analysis['expirationAnalysis'] ?? null;
    }
}