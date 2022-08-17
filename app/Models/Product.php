<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $dateFormat = 'h:m:s';

    protected $fillable = [
        'name', 'detail', 'status', 'created_by', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(Product::class);
    }
}
