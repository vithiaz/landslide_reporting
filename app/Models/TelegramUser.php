<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramUser extends Model
{
    use HasFactory;

    protected $table = 'telegram_users';
    // protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'username',
        'first_name',
        'is_bot',
        'status',
    ];

}
