<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\LawFirm;

class LeadSource extends Model
{
    use HasFactory;

    protected $table = 'lead_sources';

    protected $fillable = [
        'name',
        'law_firm_id',
    ];

    public function lawFirm()
    {
        return $this->belongsTo(LawFirm::class);
    }
}
