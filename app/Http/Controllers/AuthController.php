<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function register(Request $request)
    {
        return view('register');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function login(Request $request)
    {
        return view('login');
    }

    /**
     * Display the specified resource.
     */
    public function registerUser(Request $request)
    {
        // dd($request->all());

        $user = new User();
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password=Hash::make($request->password);
        $user->role='User';
        $user->save();

        return redirect('login');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function loginUser(Request $request)
    {
        $user=User::where('email',$request->email)->first();

        if(Hash::check($request->password,$user->password)){
            $request->session()->put('IS_ADMIN',true);
            $request->session()->put('USER_ID',$request->id);
            $request->session()->put('USER_NAME',$request->name);

            return redirect('product');

        }else{
            return redirect('login');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function getProduct()
    {
        $product = Product::where('status','=',0)->get();
        return response()->json(['status'=>200,'data'=>$product,'message'=>'data fatched']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
