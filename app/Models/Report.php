<?php

namespace App\Models;

use App\Models\SendedReport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';

    protected $fillable = [
        'type',
        'messages',
    ];

    public function sended_reports()
    {
        return $this->hasMany(SendedReport::class, 'report', 'id');
    }

}
