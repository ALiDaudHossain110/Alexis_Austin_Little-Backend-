<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LawFirm extends Model
{
    use HasFactory;

    // ✅ Prefer pluralized table name to follow Laravel convention
    protected $table = 'law_firm';

    protected $fillable = [
        'name',
        'address',
        'email',
        'phone',
        'website',
    ];

    /**
     * Relationship: Law Firm has many Users
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
