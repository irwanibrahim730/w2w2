<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailer;
use App\Product;
use Carbon\Carbon;
use App\User;
use App\Package;

class AdminController extends Controller

{


    public function approve(Request $request)
    {

        $product_id = $request->input('product_id');

        $products = Product::where('product_id',$product_id)->first(); 

    

        $curtime = Carbon::now()->toDateString();
       
        $user_id = $products->user_id;
        $user = User::where('user_id',$user_id)->first();
        $package_id = $user->package_id;
        $package = Package::where('package_id',$package_id)->first();
         

         $userlimit = $user->package_limit;
         $substract = 1;

         $token = $user->balancetoken;

         $balance = $token - $substract;
         $total = $userlimit - $substract;
         $user->package_limit = $total;
         $user->balancetoken = $balance;
         $user->save();


        $products->product_status = 'success';
        $products->approved_at = $curtime;
        $products->premiumlist = $package->premiumlist;
        $products->save(); 


        $expiration = $package->package_duration;
        $date = $products->approved_at;
        $expirationdate = date('Y-m-d', strtotime($date. ' + ' . $expiration . ' days'));

         
         $products->expired_at = $expirationdate; 
         $products->save(); 


    
         return response()->json('product approved');

        }
        
        
         public function reject(Request $request)
         {
  
            $product_id = $request->input('product_id');


            $products = Product::where('product_id',$product_id)->first(); 



            $products->product_status = 'rejected';
            $products->save();



            return response()->json('product rejected');
 

         }





    }



     

    












    
    


