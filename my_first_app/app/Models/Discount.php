<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;
    public $table = 'discounts';

    protected $fillable = [
        'percentage', 'time', 'product_id'];



    public function product(){
        return $this->belongsTo(Product::class, 'product_id');

    }

}
