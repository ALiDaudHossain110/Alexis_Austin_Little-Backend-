<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketingSpending extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_source_id',
        'year',
        'january', 'february', 'march', 'april', 'may', 'june',
        'july', 'august', 'september', 'october', 'november', 'december'
    ];

    public function leadSource()
    {
        return $this->belongsTo(LeadSource::class);
    }
}
