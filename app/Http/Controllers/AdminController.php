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
use App\Reserve;

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
         $notify->type = 'approval';
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
            $notify->type = 'approval';
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


         public function dashboard(Request $request)

         {

          $totalarray = array();

           //user

           $user = User::all();
           $users = count($user);

           $individual = User::where('user_type','individual')->get();
           $individuals = count($individual);

           $company = User::where('user_type','company')->get();
           $companies = count($company);


           //sold product
           $sold = Reserve::where('status','completed')->get();
           $solds = count ($sold);

           $waste = Reserve::where('status','completed')->where('category','waste')->get();
           $wasted = count($waste);

           $service = Reserve::where('status','completed')->where('category','service')->get();
           $services = count($service);



           $technology = Reserve::where('status','completed')->where('category','technology')->get();
           $technologies = count($technology);



           //approved product

           $product = Product::where('product_status','success')->get();
           $products = count($product);


           $productwaste = Product::where('product_status','success')->where('maincategory','waste')->get();
           $productwasted = count($productwaste);
           
           
           $productservice = Product::where('product_status','success')->where('maincategory','service')->get();
           $productserviced = count($productservice);


           $producttechnology = Product::where('product_status','success')->where('maincategory','technology')->get();
           $producttechnologies = count($producttechnology);


           $temparray = [

             'total_user' => $users,
             'total_individual' => $individuals,
             'total_company' => $companies,
             'total_sold_product' => $solds,
             'total_sold_waste' => $wasted,
             'total_sold_technology' => $technologies,
             'total_sold_services' => $services,
             'total_approved_product' => $products,
             'total_approved_waste' => $productwasted,
             'total_approved_service' => $productserviced,
             'total_approved_technology' => $producttechnologies,

           ];

           array_push($totalarray,$temparray);
          
  
           
           return response()->json($totalarray);           
      
         }



         public function dashstate(Request $request)
         {

          $state = $request->input('state');
          $statearray = array();
          $states = User::where('state',$state)->get();
          $stated = count($states);

            $temparray =[
             'total_user' => $stated,
            ] ;

            array_push($statearray,$temparray);
            
           return response()->json($statearray);

         }





    }



     

    












    
    


