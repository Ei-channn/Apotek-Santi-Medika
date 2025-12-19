<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Category;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'category_id',
        'stock',
        'price',
        'expired_date'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
