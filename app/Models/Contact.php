<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int team_id
 * @property string name
 * @property string phone
 * @property string email
 * @property int sticky_phone_number_id
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Contact extends Model
{
    use HasFactory;

    
}
