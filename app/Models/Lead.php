<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'phonenumber',
        'firstname',
        'lastname',
        'email',
        'casetype',
        'casevalue',
        'location',
        'qualified',
        'unqualifiedcasetype',
        'source',
        'consultbooked',
        'converted',
        'converted_date',
        'consultdone',
        'user_id_consultantdoneby',
        'leadstatus',
        'number_of_follow_up_attempts',
        'last_date_of_contact',
        'consultation_book_date'
    ];
    public $timestamps = true;

}
