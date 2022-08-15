<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use  Symfony\Contracts\Service\Attribute\Required;



class CommentController extends Controller
{
    //حتى أظهر كل التعليقات  الخاصة بمنتج معين
    public function index(Request $request, Product $product)
    {
        $comments = $product->comments()->get();
        return response()->json($comments);
    }

    //لاضافة تعليق على منتج معين
    public function store(Request $request, Product $product)
    {
        $request->validate([
        'value' => ['required', 'string', 'max:180']
        ]);


        $comment = $product->comments()->create([
        'value' => $request->value,
     'user_id' => Auth::id(),//لحتى يدل على ان هذا التعليق يعود خاص بمستخدم معين
        ]);

        return response()->json($comment);
    }

    //التعديل على تعليق معين لمنتح معين
    public function update(Request $request,Product $product, Comment $comment)
    {
        $request->validate([
            'value' => ['required', 'string', 'max:180']
        ]);
        if(Auth::id()!=$comment->user_id)
            return response()->json(['message'=>'not allowed']);
        else {
            $comment->update([
                'value' => $request->value
            ]);
        return response()->json(['message'=>'done',$comment]);
        }
    }

    //اظهار تعليق معين لمنتج معين
    public function show(Request $request,Product $product ,Comment $comment)
    {
        return response()->json($comment);
    }

     //لحذف تعليق معبن على منتج معين
    public function destroy(Product $product,Comment $comment)
    {   if(Auth::id()!=$comment->user_id)
        return response()->json(['message'=>'not allowed']);
        else {
            $comment->delete();
            return response()->json(['message' => 'deleted is done']);
        }
    }
}
