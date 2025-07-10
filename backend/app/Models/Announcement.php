<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Announcement extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(AnnouncementImage::class);
    }
}
