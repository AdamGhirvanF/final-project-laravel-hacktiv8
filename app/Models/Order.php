<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'total_price',
        'customer_name',
        'customer_address',
        'order_date',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
