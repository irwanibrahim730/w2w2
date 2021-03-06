<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Reserve;
use App\Product;
use App\User;
use App\log;
use App\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailer;

class ReserveController extends Controller

{
    public function reserve(Request $request)
    {
        
        $product_id = $request->input('product_id');

        $buyer_id = $request->input('buyer_id');
        $offeredprice = $request->input('offeredprice');
        $quantity = $request->input('quantity');
        $unit = $request->input('unit');
        $info = $request->input('info');

        $products = Product::find($product_id);

        if($products){

            $data = new Reserve;
            $data->user_id = $products->user_id;
            $data->product_id = $products->product_id;
            $data->offeredprice = $offeredprice;
            $data->buyer_id = $buyer_id;
            $data->status = 'reserved';
            $data->quantity = $quantity;
            $data->unit = $unit;
            $data->info = $info;
            $data->category = $products->maincategory;

            //notification system
            $notification = new Notification;
            $notification->user_id = $products->user_id;
            $notification->product_id = $product_id;
            $notification->type = 'reserverproduct';
            $notification->item = $products->product_name;


            //notification email seller
            $tempmessages = 'Your product name' . $products->product_name .' have been reserved';
            $messages = $tempmessages;

            $useremail = User::where('user_id',$products->user_id)->first();

            Mail::raw( $messages , function ($message) use($useremail){
                $message->to($useremail->user_email);
                $message->from('w2wmasteradm@gmail.com', 'W2W Master Admin Waste2Wealth');
                $message->subject('Ecowaste Market');
            });

            $notification->save();
            $data->save(); 

            return response()->json(['status'=>'success','value'=>'product reserved']);

        } else {
            return response()->json(['status'=>'failed','value'=>'product not exist']);
        }

    }

    public function listall(){

       $reserve = Reserve::all();

       foreach($reserve as $products){
        if($products->status=='reserved'){

          $reservearray = array();
          $imageArray = array();
          $json_array = json_decode($products->image, true);


          
          foreach ($json_array as $pic)

          {
              $url = 'https://codeviable.com/w2w2/public/image';
              $public =  $url .'/'. $pic;
              

              $imagetempArray = [
                  'image' => $public,
              ];

              array_push($imageArray,$imagetempArray);
          }

            $tempArray = [

                'id' => $products->id,
                'user_id' => $products->user_id,
                'product_id' => $products->product_id,
                'image' => $imageArray,
                'offeredprice' => $products->offeredprice,
                'buyer_id' => $products->buyer_id,
                'status' => $products->status,
                'info' => $products->info,
                'unit' => $products->unit,
                'quantity' => $products->quantity,
                'category' => $products->category,
                 
            ];
           array_push($reservearray,$tempArray);

        }
     }

     return response()->json($reservearray); 

    }

    public function listreserved (Request $request)
    {

       $reservearray = array();
       $user_id = $request->input('user_id');
       $product = Reserve::where('user_id',$user_id)->get();

           foreach($product as $products){
              if($products->status=='reserved'){

                $json_array = json_decode($products->image, true);
                $imageArray = array();

              
                
                foreach ($json_array as $pic)

                {
                    $url = 'https://codeviable.com/w2w2/public/image';
                    $public =  $url .'/'. $pic;
                    
      
                    $imagetempArray = [
                        'image' => $public,
                    ];
      
                    array_push($imageArray,$imagetempArray);
                }

                  $tempArray = [

                      'id' => $products->id,
                      'user_id' => $products->user_id,
                      'product_id' => $products->product_id,
                      'image' => $imageArray,
                      'offeredprice' => $products->offeredprice,
                      'buyer_id' => $products->buyer_id,
                      'status' => $products->status,
                      'info' => $products->info,
                      'unit' => $products->unit,
                      'quantity' => $products->quantity,
                      'category' => $products->category,
                       
                  ];
                 array_push($reservearray,$tempArray);

              }
           }

           return response()->json($reservearray); 
       

    }

    public function approve(Request $request)

    {
         $id = $request->input('id');

         $product = Reserve::where('id',$id)->first();

         $product->status = 'approved';
         $product->save();

         return response()->json('offer approved');

    }

    public function reject(Request $request)

    {
         $id = $request->input('id');

         $product = Reserve::where('id',$id)->first();

         $product->status = 'rejected';
         $product->save();

         return response()->json('offer rejected');

    }



    public function listapproved(Request $request)
    {
        $reservearray = array();
        $buyer_id = $request->input('buyer_id');
        $product = Reserve::where('buyer_id',$buyer_id)->get();
 
            foreach($product as $products){
               if($products->status=='approved'){
 
                 $json_array = json_decode($products->image, true);
                 $imageArray = array();
 
               
                 
                 foreach ($json_array as $pic)

                 {
                     $url = 'https://codeviable.com/w2w2/public/image';
                     $public =  $url .'/'. $pic;
                     
       
                     $imagetempArray = [
                         'image' => $public,
                     ];
       
                     array_push($imageArray,$imagetempArray);
                 }
 
                   $tempArray = [
                       'id' => $products->id,
                       'user_id' => $products->user_id,
                       'product_id' => $products->product_id,
                       'image' => $imageArray,
                       'offeredprice' => $products->offeredprice,
                       'buyer_id' => $products->buyer_id,
                       'status' => $products->status,
                       'info' => $products->info,
                       'unit' => $products->unit,
                       'quantity' => $products->quantity,
                       'category' => $products->category,
                        
                   ];
                  array_push($reservearray,$tempArray);
 
               }
            }
 
            return response()->json($reservearray); 

    }

    public function listrejected(Request $request)
    {
        $reservearray = array();
        $buyer_id = $request->input('buyer_id');
        $product = Reserve::where('buyer_id',$buyer_id)->get();
 
            foreach($product as $products){
               if($products->status=='rejected'){
 
                 $json_array = json_decode($products->image, true);
                 $imageArray = array();
 
               
                 
                 foreach ($json_array as $pic)

                 {
                     $url = 'https://codeviable.com/w2w2/public/image';
                     $public =  $url .'/'. $pic;
                     
       
                     $imagetempArray = [
                         'image' => $public,
                     ];
       
                     array_push($imageArray,$imagetempArray);
                 }
 
                   $tempArray = [
                      
                       'id' => $products->id,
                       'user_id' => $products->user_id,
                       'product_id' => $products->product_id,
                       'image' => $imageArray,
                       'offeredprice' => $products->offeredprice,
                       'buyer_id' => $products->buyer_id,
                       'status' => $products->status,
                       'info' => $products->info,
                       'unit' => $products->unit,
                       'quantity' => $products->quantity,
                       'category' => $products->category,
                        
                   ];
                  array_push($reservearray,$tempArray);
 
               }
            }
 
            return response()->json($reservearray); 

    }

    public function confirm(Request $request){
      
        $id = $request->input('id');

        $product = Reserve::where('id',$id)->first();

        $product->status = 'confirmed';
        $product->save();

        return response()->json('offer confirmed');


    }

    public function cancel(Request $request){
      
        $reserve_id = $request->input('id');

        $product = Reserve::where('id',$reserve_id)->first();

        $product->status = 'canceled';
        $product->save();

        return response()->json('offer canceled');


    }

    public function listconfirmed(Request $request)
    {
        $reservearray = array();
        $buyer_id = $request->input('buyer_id');
        
        $reserve = Reserve::where('buyer_id',$buyer_id)->get();
        


 
            foreach($reserve as $reserves){
               if($reserves->status=='confirmed'){

                $seller_id = $reserves->user_id;
                $seller = User::where('user_id',$seller_id)->get();

                         foreach($seller as $sellers){

 
                   $tempArray = [
  
                       'id' => $reserves->id,
                       'product_id' => $reserves->product_id,
                       'offeredprice' => $reserves->offeredprice,
                       'user_email' => $sellers->user_email,
                       'personincharge' => $sellers->personincharge,
                        'phonenumber' => $sellers->phonenumber,
                       'buyer_id' => $reserves->buyer_id,
                       'status' => $reserves->status,
                       'info' => $reserves->info,
                       'unit' => $reserves->unit,
                       'quantity' => $reserves->quantity,
                       'category' => $reserves->category,
                   ];
                  array_push($reservearray,$tempArray);
 
               }
            }
            }
 
            return response()->json($reservearray); 

    }

    public function listcancelled(Request $request)
    {
        $reservearray = array();
        $buyer_id = $request->input('buyer_id');
        $product = Reserve::where('buyer_id',$buyer_id)->get();
 
            foreach($product as $products){
               if($products->status=='canceled'){
 

                   $tempArray = [
 
                       'id' => $products->id,
                       'user_id' => $products->user_id,
                       'product_id' => $products->product_id,
                       'offeredprice' => $products->offeredprice,
                       'buyer_id' => $products->buyer_id,
                       'status' => $products->status,
                       'info' => $products->info,
                       'unit' => $products->unit,
                       'quantity' => $products->quantity,
                       'category' => $products->category,
                        
                   ];
                  array_push($reservearray,$tempArray);
 
               }
            }
 
            return response()->json($reservearray); 

    }

    public function complete(Request $request){
      
        $reserve_id = $request->input('id');

        $product = Reserve::where('id',$reserve_id)->first();
        $user_id = $product->user_id;
        $user = User::where('user_id',$user_id)->first();
        $buyer_id = $product->buyer_id;
        $buyer = User::where('user_id',$buyer_id)->first();
        $product_id = $product->product_id;
        $products = Product::where('product_id',$product_id)->first();

        $product->status = 'completed';
        $product->category = $products->maincategory;
        $product->save();

        $sellerlog = new Log;
        $sellerlog->username = $user->user_fname;
        $sellerlog->type = 'seller';
        $sellerlog->save();

        $buyerlog = new Log;
        $buyerlog->username = $buyer->user_fname;
        $buyerlog->type = 'buyer'; 
        $buyerlog->save();

        $notifybuyer = new notification;
        $notifybuyer->email = $buyer->user_email;
        $notifybuyer->item = $products->product_name;
        $notifybuyer->user_id = $product->buyer_id;
        $notifybuyer->product_id = $product->product_id;
        $notifybuyer->status = 'need review';
        $notifybuyer->type = 'review';
        $notifybuyer->save();
        


        return response()->json('offer completed');


    }

    public function listcompleted(Request $request)
    {
        $reservearray = array();
        $buyer_id = $request->input('buyer_id');
        $product = Reserve::where('buyer_id',$buyer_id)->get();
 
            foreach($product as $products){
               if($products->status=='completed'){
 

                   $tempArray = [
 
                       'id' => $products->id,
                       'user_id' => $products->user_id,
                       'product_id' => $products->product_id,
                       'offeredprice' => $products->offeredprice,
                       'buyer_id' => $products->buyer_id,
                       'status' => $products->status,
                       'info' => $products->info,
                       'unit' => $products->unit,
                       'quantity' => $products->quantity,
                       'category' => $products->category,
                        
                   ];
                  array_push($reservearray,$tempArray);
 
               }
            }
 
            return response()->json($reservearray); 

    }


    public function liststatusseller(Request $request)
    {
  
        $reservearray = array();
        $imageArray = array();
         $status = $request->input('status');
         $user_id = $request->input('user_id');

         $reserve = Reserve::where('user_id',$user_id)->get();

         foreach($reserve as $products)
         {
             $product_id = $products->product_id;
             $data = Product::where('product_id',$product_id)->get();

             foreach($data as $item){

                $user_id = $products->user_id;
                $user = User::where('user_id',$user_id)->get();

                foreach($user as $users){
               
             if($products->status == $status)
             {

                    $json_array = json_decode($products->image, true);
                 
    
                    foreach ($json_array as $pic)

                    {
                        $url = 'https://codeviable.com/w2w2/public/image';
                        $public =  $url .'/'. $pic;
                        
          
                        $imagetempArray = [
                            'image' => $public,
                        ];
          
                        array_push($imageArray,$imagetempArray);
                    }
    
                      $tempArray = [
                         
                          'id' => $products->id,
                          'user_id' => $products->user_id,
                          'user_fname' => $users->user_fname,
                          'user_lname' => $users->user_lname,
                          'user_email' => $users->user_email,
                          'product_id' => $products->product_id,
                          'product_name' => $item->product_name,
                          'image' => $imageArray,
                          'offeredprice' => $products->offeredprice,
                          'buyer_id' => $products->buyer_id,
                          'status' => $products->status,
                          'info' => $products->info,
                          'unit' => $products->unit,
                          'quantity' => $products->quantity,
                          'category' => $products->category,
                           
                      ];
                    

                 




                      array_push($reservearray,$tempArray);
             }
           
         }
        }
        }
         return response()->json($reservearray);
 
        }


        public function liststatusbuyer(Request $request)
        {
            $reservearray = array();
            $imageArray = array();
             $status = $request->input('status');
             $buyer_id = $request->input('buyer_id');
    
             $reserve = Reserve::where('buyer_id',$buyer_id)->get();
    
             foreach($reserve as $products)
             {
                 $product_id = $products->product_id;
                 $data = Product::where('product_id',$product_id)->get();

                 foreach($data as $item){

                    $user_id = $products->user_id;
                    $user = User::where('user_id',$user_id)->get();

                    foreach($user as $users){
                   
                 if($products->status == $status)
                 {
    
                        $json_array = json_decode($products->image, true);
                     
        
                        foreach ($json_array as $pic)

                        {
                            $url = 'https://codeviable.com/w2w2/public/image';
                            $public =  $url .'/'. $pic;
                            
              
                            $imagetempArray = [
                                'image' => $public,
                            ];
              
                            array_push($imageArray,$imagetempArray);
                        }
        
                          $tempArray = [
                             
                              'id' => $products->id,
                              'user_id' => $products->user_id,
                              'user_fname' => $users->user_fname,
                              'user_lname' => $users->user_lname,
                              'user_email' => $users->user_email,
                              'product_id' => $products->product_id,
                              'product_name' => $item->product_name,
                              'image' => $imageArray,
                              'offeredprice' => $products->offeredprice,
                              'buyer_id' => $products->buyer_id,
                              'status' => $products->status,
                              'info' => $products->info,
                              'unit' => $products->unit,
                              'quantity' => $products->quantity,
                              'category' => $products->category,
                               
                          ];
                        
    
                     
    
    
    
    
                          array_push($reservearray,$tempArray);
                 }
               
             }
            }
            }
             return response()->json($reservearray);
            }


    public function detail(Request $request)
    {
        $id = $request->input('id');
        $products = Reserve::where('id',$id)->first();

        $reservearray = array();
        $json_array = json_decode($products->image, true);
        $imageArray = array();

      
        
                foreach ($json_array as $pic)
                {
                    $public = rtrim(app()->basePath('public/image'), '/');
                    $imagepath = $public.'/'.$pic;
                    
  
                    $imagetempArray = [
                        'image' => $imagepath,
                    ];
  
                    array_push($imageArray,$imagetempArray);
                }

          $tempArray = [
              'id' => $products->id,
              'user_id' => $products->user_id,
              'product_id' => $products->product_id,
              'image' => $imageArray,
              'offeredprice' => $products->offeredprice,
              'buyer_id' => $products->buyer_id,
              'status' => $products->status,
              'info' => $products->info,
              'unit' => $products->unit,
              'quantity' => $products->quantity,
              'category' => $products->category,
               
          ];
         array_push($reservearray,$tempArray);

       
        return response()->json($reservearray);

    }        


    public function delete (Request $request)
    {
 
     $id = $request->input('id');

     $reserve = Reserve::where('id',$id)->first();
     $reserve->delete();

     return response()->json('Reservation deleted');

    }


    

          







}