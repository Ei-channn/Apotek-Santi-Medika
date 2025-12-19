<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Medicine;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function medicines()
    {
        return $this->hasMany(Medicine::class);
    }
}
