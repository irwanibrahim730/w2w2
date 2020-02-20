<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Product;
use Carbon\Carbon;
use App\User;
use App\Package;

class AdminController extends Controller

{

    public function listapproval() 
    {

        $products = Product::where('approved_at',null)->get();
        
        return response()-> json($products);

    }




    public function approve(Request $request)
    {

        $product_id = $request->input('product_id');
        $product_status = $request->input('product_status');
        $products = Product::where('product_id',$product_id)->first(); 

        if($product_status == 'success'){

        $curtime = Carbon::now()->toDateString();
       
        $user_id = $products->user_id;
        $user = User::where('user_id',$user_id)->first();
        $package_id = $user->package_id;
        $package = Package::where('package_id',$package_id)->first();
         

         $userlimit = $user->package_limit;
         $substract = 1;
         $total = $userlimit - $substract;
         $user->package_limit = $total;
         $user->save();



            //save status
            //save start
            //save end

        $products->product_status = $product_status;
        $products->approved_at = $curtime;
        $products->save(); 


        $expiration = $package->package_duration;
        $date = $products->approved_at;
        $expirationdate = date('Y-m-d', strtotime($date. ' + ' . $expiration . ' days'));

         
         $products->expired_at = $expirationdate; 
         $products->save(); 
       

         return response()->json('successs approve');

        } else {

            $products->product_status = $product_status;
       
         return response()->json('successs update');

        }

        
        
    
    }

        
       

/* 
        return response()->json('product approved');      */
        
    }



     

    












    
    


