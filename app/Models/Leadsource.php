<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadSource  extends Model
{
    use HasFactory;
    protected $table = 'lead_sources';
    protected $fillable = [
        'name',
        'law_firm_id',
    ];

    /**
     * Relationship: Lead Source belongs to a Law Firm
     */
    public function lawFirm()
    {
        return $this->belongsTo(LawFirm::class);
    }
}
