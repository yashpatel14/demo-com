<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('product');
    }

    /**
     * Show the form for creating a new resource.
     */
   

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        if($request->id){
            $product=Product::find($request->id);

        }else{

            $product=new Product();
        }
        if($request->hasFile('image')){
            $image=$request->file('image');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('uploads'), $imageName);
            $imagePath = 'uploads/' . $imageName;
            $product->image=$imagePath;
        }
        $product->name=$request->name;
        $product->price=$request->price;
        $product->desc=$request->desc;
        $product->status=$request->status;
        $product->save();

        return response()->json(['status'=>200,'message'=>'data inserted']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $product = Product::all();
        return response()->json(['status'=>200,'data'=>$product,'message'=>'data fatched']);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $product = Product::where('id',$request->id)->first();
        return response()->json(['status'=>200,'data'=>$product,'message'=>'data fatched']);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $product=Product::where('id',$request->id)->delete();
        return response()->json(['status'=>200,'message'=>'data deleted']);

    }
}
