<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use phpseclib3\Crypt\EC\BaseCurves\Prime;

class LikeController extends Controller
{
   //ما في داعي اعرف ال id تبع المنتج لأن أنا باعتة المنتج يلي بدي ضفلو اللايك
    public function index(Request $request,Product $product)
    {
        $like = $product->likes();
        return response()->json($like);
    }


    public function store(Request $request,Product $product)
    {
        if($product->likes()->where('user_id',Auth::id())->exists())
         {$product->likes()->where('user_id',Auth::id())->delete();
             return response()->json(['message'=>'no like']);}
        else{
            $product->likes()->create([
                'user_id' => Auth::id(),
            ]);
            return response()->json(['message'=>'like']);
        }

    }

    public function update(Request $request,Product $product, Like $like)
    {

    }

    public function show(Like $like)
    {
        //
    }

    public function destroy(Like $like)
    {
        //
    }
}
