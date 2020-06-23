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


        $products = Product::find($product_id);

          if($products){

            $user = User::find($products->user_id);
            $package = Package::find($products->package_id);

            $curtime = Carbon::now()->toDateTimeString();

            $expiration = $package->package_duration;
            $date = $curtime;
            $expirationdate = date('Y-m-d H:i:s', strtotime($date. ' + ' . $expiration));

            $products->product_status = 'success';
            $products->suggestcustomer = $suggestcustomer;
            $products->tagging = json_encode($tag);
            $products->approved_at = $curtime;
            $products->expired_at = $expirationdate; 
            $products->publishstatus = 'yes';

            $approved = 'approved';

            $notify = new Notification;
            $notify->status = $approved;
            $notify->item = $products->product_name;
            $notify->user_id = $user->user_id;
            $notify->product_id = $product_id;
            $notify->type = 'approval';

            $messages = 'your product, '.$products->product_name.'  has been approved';

            Mail::raw( $messages ,function ($message) use($user)
              {
                $message->to($user->user_email);
                $message->from('testemaillumen123@gmail.com', 'Admin of W2W');
                $message->subject('Product Approval');
                }); 
            
            $products->save(); 
            $notify->save();
        
            return response()->json(['status'=>'success','value'=>'product approved']);

          } else {

            return response()->json(['status'=>'failed','value'=>'product not exist']);

          }

        }
      
         public function reject(Request $request)
         {
  
            $product_id = $request->input('product_id');
            $rejectremark = $request->input('rejectremark');


            $products = Product::where('product_id',$product_id)->first(); 
            $user = User::where('user_id',$products->user_id)->first();

            $products->product_status = 'rejected';
            $products->rejectremark = $rejectremark;
            $products->save();

            $rejected = 'rejected';

            $notify = new Notification;
            $notify->status = $rejected;
            $notify->user_id = $user->user_id;
            $notify->product_id = $product_id;
            $notify->item = $products->product_name;
            $notify->type = 'approval';
            $notify->save();

            $messages = 'your product, '.$products->product_name.'  has been rejected';

            Mail::raw( $messages ,function ($message) use($user)
              {
               $message->to($user->user_email);
               $message->from('testemaillumen123@gmail.com', 'Admin of W2W');
               $message->subject('Product Approval');
   
   
               }); 

            return response()->json(['status'=>'success','value'=>'product rejected']);
 

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



         public function statecategory(Request $request)
         
         
         {


          $state = $request->input('state');

          $countarray = array();

          $user = User::where('state',$state)->count();

          $waste = Product::where('user_state',$state)->where('maincategory','waste')->count();

          $service = Product::where('user_state',$state)->where('maincategory','service')->count();

          $technology = Product::where('user_state',$state)->where('maincategory','technology')->count();

          $tempArray = [

             'total_user' => $user,
             'total_waste' => $waste,
             'total_service' => $service,
             'total_technology' => $technology,

          ];

          array_push($countarray,$tempArray);

          return response()->json($countarray);
 




         }





    }



     

    












    
    


