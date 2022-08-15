<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use function PHPUnit\Framework\returnArgument;

class Product extends Model
{
    use HasFactory;

    public $table = 'products';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'img_url',
        'exp_date',
        'quantity',
        'price',
        'viewer',
        'user_id',
        'category_id',
    ];
    //عندما نريد اظهار المنتج مع صنفه
    public $with = ['category','discounts'];
//حتى رجع عدد التعليقات ةاللايكات الخاصة بالمنتج
    public $withCount = ['comments', 'likes'];

  //  protected $casts=['discounts_list'=>'array'];
    /*protected  $attributes=[
    'list_of_discount'=>'{"discount_date"=date,
                            "discount_percentage"=integer}'

    ];*/

    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }


    public function comments(){
        return $this->hasMany(Comment::class, 'product_id');
    }

    public function likes(){
        return $this->hasMany(Like::class, 'product_id');
    }

    public function discounts(){
        return $this->hasMany(Discount::class, 'product_id')->orderBy('time');
    }


}
