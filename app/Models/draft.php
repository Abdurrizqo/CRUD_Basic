<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class draft extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'draftnews';
    protected $primaryKey = 'idDraft';
    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = [
        'title',
        'content',
        "image",
        'user_id',
    ];
}
