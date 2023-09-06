<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SendedReport extends Model
{
    use HasFactory;

    protected $table = 'sended_reports';

    protected $fillable = [
        'report',
        'telegram_user',
        'status',
    ];


}
