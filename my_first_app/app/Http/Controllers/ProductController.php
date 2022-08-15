<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Symfony\Contracts\Service\Attribute\Required;
use App\Models\Category;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class ProductController extends Controller
{

    public function show_market()
    {
        $product = Product::query()->select('name','img_url','category_id')->get();
        return response()->json($product);
    }

    //البحث عن المنتج حسب تصنيفه او اسمه او تاريخ انتهاء صلاحيته
    public function index(Request $request)
    {
        $category_id = $request->input('category_id');//البحث حسب الصنف
        $price_from = $request->input('price_from');//البحث حسب السعر
        $price_to = $request->input('price_to');//البحث حسب السعر
        $key_search = $request->input('key_search');//البحث حسب الاسم

        $productQuery = Product::query();

        //البحث حسب الاسم
        if ($key_search && isset($key_search)) {
            $productQuery->where('name', 'like', '%' . $key_search . '%');
        }
        //البحث حسب الصنف
       if ($category_id) {
            $productQuery->where('category_id', $category_id);
        }
        //البحث حسب السعر
        if ($price_from) {
            $productQuery->where('price', '>=', $price_from);
        }
        if ($price_to) {
            $productQuery->where('price', '<=', $price_to);
        }
        $productQuery->where('exp_date', '>', now());

        $products = $productQuery->get();

        return response()->json(['message'=>'the product is found',$products]);
    }

    // لاضافة منتج
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required','string', 'max:190', 'min:3'],
            'img_url' => ['required','mimes:jpg,png,jpeg', 'max:5048'],
            'exp_date' => ['required', 'date'],
            'quantity' => ['required','numeric'],
            'price' => ['required','numeric'],
            'discounts_list'=>['required',],
            'category_id' => ['required', Rule::exists('categories', 'id')],
        ]);
        ///لحتى احفظ الصورة باسم جديد يحوي التاريخ مع اسم الصورة واسم المنتج
        $imagename = time() . '-' . $request->name . '.' . $request->img_url->extension();
        $request->img_url->move('product_image', $imagename);
        $url_img=URL::asset('product_image/'.$imagename);


        $product = Product::query()->create([
            'name' => $request->name,
            'img_url' => $url_img,//$request->img_url;//$imagename,
            'exp_date' => $request->exp_date,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'user_id' => Auth::id(),//only  if the user is authenticated
            'category_id' => $request->category_id,
        ]);

        foreach ($request->discounts_list as $discount) {
            $product->discounts()->create ([
                'time' => $discount['time'],
                'percentage' => $discount['percentage'],
            ]);
         }

       return response()->json(['message'=>'adding is done',$product]);
    }

    //لعرض المنتج مع الخصومات
    public function show(Product $product)
    {   $discount_for_product = $product->discounts()->get();
        $maximum = null;
        foreach ($discount_for_product as $discount){
            if (Carbon::parse($discount['time']) <= now())
            {
                $maximum = $discount;
            }
        }

        if (!is_null($maximum)){
            $discount_value = ($product->price*$maximum['percentage'])/100;
            $product['price_after_discount'] = $product->price - $discount_value;
        }
        if (now() >= $product->exp_date) {
            $product->delete();
            return response()->json(['message'=>'the product is expired']);
        }


        $product->increment('viewer');
        return response()->json($product);
    }

//للتحديث على المنتج
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => ['string', 'max:190', 'min:3'],
           'img_url' => ['required','mimes:jpg,png,jpeg', 'max:5048'],
            'quantity' => ['required','numeric'],
            'price' => ['required','numeric'],
            'discounts_list'=>['required'],
            //لم يتم وضع ا=تاريخ انتهاء الصلاحية لان لايمكننا تعديله
        ]);

        $imagename = time() . '-' . $request->name . '.' . $request->img_url->extension();
        $request->img_url->move('product_image', $imagename);
        $url_img=URL::asset('product_image/'.$imagename);

        $product->update([
            'name' => $request->name,
            'img_url' => $url_img,
            'quantity' => $request->quantity,
            'price' => $request->price,

        ]);


       foreach ($request->discounts_list as $discount)
           $product->discounts()->update([
                'time' => $discount['time'],
                'percentage' => $discount['percentage'],
            ]);



        return response()->json(['message'=>'updating is done',$product]);
    }

  //لحذف المنتج
    public function destroy(Product $product)
    {
        $product->delete();
        response()->json(['message'=>'deleting is done']);

    }


}
