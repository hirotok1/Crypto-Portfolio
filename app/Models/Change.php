<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Change extends Model
{
    use HasFactory, SoftDeletes;

    // テーブル名を指定
    protected $table = 'changes';

    // 入力可能なカラムを指定
    protected $fillable = [
        'user_id',
        'place',
        'coin',
        'change',
        'related_type',
        'related_id',
        'customtime',
    ];

    // リレーション: ユーザー
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // リレーション: 紐づく取引（swaps, sends, deposits など）
    public function related()
    {
        return $this->morphTo();
    }
}
