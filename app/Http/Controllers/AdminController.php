<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Product;
use Carbon\Carbon;

class AdminController extends Controller

{

    public function listapproval() 
    {

        $products = Product::where('approved_at',null)->get();
        
        return response()-> json($products);

    }




    public function approve($product_id)
    {
        $curtime = Carbon::now()->toDateString();
        $products = Product::where('product_id',$product_id)->first(); 
        $products->approved_at = $curtime;
        $products->save();

        return response()->json('product approved');   
    }



     

    












    
    


}