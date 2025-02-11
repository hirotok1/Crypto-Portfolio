<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Swap extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function changes()
    {
        return $this->morphMany(Change::class, 'related');
    }
    protected $fillable = [
        'place',
        'coina',
        'amounta',
        'coinb',
        'amountb',
        'customfeecoin',
        'customfee',
        'customtime',
        'memo',
    ];
}
