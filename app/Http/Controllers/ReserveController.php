<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Reserve;
use App\Product;
use App\User;
use App\log;

class ReserveController extends Controller

{
    public function reserve(Request $request)
    {
        
        $product_id = $request->input('product_id');

        $products = Product::where('product_id',$product_id)->first();
        $buyer_id = $request->input('buyer_id');
        $offeredprice = $request->input('offeredprice');
        


        $data = new Reserve;
        $data->user_id = $products->user_id;
        $data->product_id = $products->product_id;
        $data->image = json_encode($products->product_image);
        $data->offeredprice = $offeredprice;
        $data->buyer_id = $buyer_id;
        $data->status = 'reserved';
        $data->save(); 

        return response()->json('Product reserved');

    }

    public function listreserved (Request $request)

    {

       $reservearray = array();
       $user_id = $request->input('user_id');
       $product = Reserve::where('user_id',$user_id)->get();

           foreach($product as $products){
              if($products->status=='reserved'){

                // $json_array = json_decode($products->image, true);
                // $imageArray = array();

              
                
                //         foreach ($json_array as $pic)
                //         {
                //             $public = rtrim(app()->basePath('public/image'), '/');
                //             $imagepath = $public.'/'.$pic;
                            
          
                //             $imagetempArray = [
                //                 'image' => $imagepath,
                //             ];
          
                //             array_push($imageArray,$imagetempArray);
                //         }

                  $tempArray = [

                      'product_id' => $products->product_id,
                    //   'image' => $imageArray,
                      'offeredprice' => $products->offeredprice,
                      'buyer_id' => $products->buyer_id,
                      'status' => $products->status,
                       
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
 
                 // $json_array = json_decode($products->image, true);
                 // $imageArray = array();
 
               
                 
                 //         foreach ($json_array as $pic)
                 //         {
                 //             $public = rtrim(app()->basePath('public/image'), '/');
                 //             $imagepath = $public.'/'.$pic;
                             
           
                 //             $imagetempArray = [
                 //                 'image' => $imagepath,
                 //             ];
           
                 //             array_push($imageArray,$imagetempArray);
                 //         }
 
                   $tempArray = [
 
                       'product_id' => $products->product_id,
                     //   'image' => $imageArray,
                       'offeredprice' => $products->offeredprice,
                       'buyer_id' => $products->buyer_id,
                       'status' => $products->status,
                        
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
 
                 // $json_array = json_decode($products->image, true);
                 // $imageArray = array();
 
               
                 
                 //         foreach ($json_array as $pic)
                 //         {
                 //             $public = rtrim(app()->basePath('public/image'), '/');
                 //             $imagepath = $public.'/'.$pic;
                             
           
                 //             $imagetempArray = [
                 //                 'image' => $imagepath,
                 //             ];
           
                 //             array_push($imageArray,$imagetempArray);
                 //         }
 
                   $tempArray = [
 
                       'product_id' => $products->product_id,
                     //   'image' => $imageArray,
                       'offeredprice' => $products->offeredprice,
                       'buyer_id' => $products->buyer_id,
                       'status' => $products->status,
                        
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
 
                       'product_id' => $reserves->product_id,
                       'offeredprice' => $reserves->offeredprice,
                       'user_email' => $sellers->user_email,
                       'personincharge' => $sellers->personincharge,
                        'phonenumber' => $sellers->phonenumber,
                       'buyer_id' => $reserves->buyer_id,
                       'status' => $reserves->status,
                        
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
 
                       'product_id' => $products->product_id,
                       'offeredprice' => $products->offeredprice,
                       'buyer_id' => $products->buyer_id,
                       'status' => $products->status,
                        
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

        $product->status = 'completed';
        $product->save();

        $sellerlog = new Log;
        $sellerlog->username = $user->user_fname;
        $sellerlog->type = 'seller';
        $sellerlog->save();

        $buyerlog = new Log;
        $buyerlog->username = $buyer->user_fname;
        $buyerlog->type = 'buyer'; 
        $buyerlog->save();
        


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
 
                       'product_id' => $products->product_id,
                       'offeredprice' => $products->offeredprice,
                       'buyer_id' => $products->buyer_id,
                       'status' => $products->status,
                        
                   ];
                  array_push($reservearray,$tempArray);
 
               }
            }
 
            return response()->json($reservearray); 

    }




}