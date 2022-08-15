<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::query()->get();
        return \response()->json($categories,Response::HTTP_OK);

    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=> ['required', 'string', 'max:190', 'min:3']
        ]);

        $category = Category::query()->create([
            'name'=>$request->name
        ]);

        return response()->json(['message'=>'adding is done',$category]);
    }

  //لاظهار صنف معين
    public function show(Category $category)
    {
        return \response()->json($category);
    }


    public function update(Request $request, Category $category)
    {   //the request is to specify what is the type of it
        //and the category to specify the object that i want to update on it
        $request->validate([
            'name'=> ['required', 'string', 'max:190', 'min:3']
            //
        ]);

        $category->update([
           'name' => $request->name,
        ]);

       return response()->json(['message'=>'updanting is done',$category]);

    }


    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(['message'=>'deleting is done',$category]);
    }

}
