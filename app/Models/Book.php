<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $table = 'books';

    protected $fillable = [
        'title',
        'description',
        'credit',
        'image',
        'user_id',
    ];

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
