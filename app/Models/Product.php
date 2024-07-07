<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'price',
        'image',
        'user_id'
    ];

    public function getImageUrlAttribute()
    {
        return $this->image ? url('/') . Storage::url('public/' . $this->image) : null;
    }

    public function toArray()
    {
        $array = parent::toArray();
        $array['image_url'] = $this->image_url;
        unset($array['image']); // Remove the original image field if not needed
        return $array;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
