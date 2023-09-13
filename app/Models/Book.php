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

    protected $appends = ['image_url'];

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function getImageUrlAttribute()
    {
        return asset('images/books/' . $this->image);
    }

    public static function getBooksByUser($userId)
    {
        return self::where('user_id', $userId)
            ->with('user')
            ->get();
    }
}
