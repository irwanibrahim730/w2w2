<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailer;
use App\Product;
use Carbon\Carbon;
use App\User;
use App\Notification;
use App\Package;
use App\Userpack;

class AdminController extends Controller

{


    public function approve(Request $request)
    {

        $product_id = $request->input('product_id');
        $suggestcustomer = $request->input('suggestcustomer');
        $tagging = $request->input('tagging');

        $tag=array();
        foreach($tagging as $taggings)
        {
            $tag[] = $taggings;
        }



        $products = Product::where('product_id',$product_id)->first(); 
       
        $user = User::where('user_id',$products->user_id)->first();

        $curtime = Carbon::now()->toDateString();
       
        $id = $products->package_id;
        $userpack = Userpack::where('id',$id)->first();
        
        $package_id = $userpack->package_id;
        $package = Package::where('package_id',$package_id)->first();
         

         $userlimit = $userpack->limit;
         $substract = 1;


         $total = $userlimit - $substract;
         $userpack->limit = $total;
         $userpack->save();


 


        $products->product_status = 'success';
        $products->suggestcustomer = $suggestcustomer;
        $products->tagging = json_encode($tag);
        $products->approved_at = $curtime;
        $products->premiumlist = $package->premiumlist;
        $products->save(); 


        $expiration = $package->package_duration;
        $date = $products->approved_at;
        $expirationdate = date('Y-m-d', strtotime($date. ' + ' . $expiration . ' days'));

         
         $products->expired_at = $expirationdate; 
         $products->save(); 

         $approved = 'approved';

         $notify = Notification::where('product_id',$product_id)->first();
         $notify->status = $approved;
         $notify->save();

            

         $messages = 'your product, '.$products->product_name.'  has been approved';

         Mail::raw( $messages ,function ($message) use($user)
           {
            $message->to($user->user_email);
            $message->from('testemaillumen123@gmail.com', 'Admin of W2W');
            $message->subject('Product Approval');


            }); 



    
         return response()->json('product approved');

        }
        
        
         public function reject(Request $request)
         {
  
            $product_id = $request->input('product_id');
            $rejectremark = $request->input('rejectremark');


            $products = Product::where('product_id',$product_id)->first(); 



            $products->product_status = 'rejected';
            $products->rejectremark = $rejectremark;
            $products->save();

            $rejected = 'rejected';

            $notify = Notification::where('product_id',$product_id)->first();
            $notify->status = $rejected;
            $notify->save();


            $user = User::where('user_id',$products->user_id)->first();

            $messages = 'your product, '.$products->product_name.'  has been rejected';

            Mail::raw( $messages ,function ($message) use($user)
              {
               $message->to($user->user_email);
               $message->from('testemaillumen123@gmail.com', 'Admin of W2W');
               $message->subject('Product Approval');
   
   
               }); 



            return response()->json('product rejected');
 

         }





    }



     

    












    
    


