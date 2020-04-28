<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Review;
use App\User;
use App\Product;
use App\Notification;

class ReviewController extends Controller

{
   public function store(Request $request)
   {

       $user_id = $request->input('user_id');
       $buyer_id = $request->input('buyer_id');
       $product_id = $request->input('product_id');
       $title = $request->input('title');
       $description = $request->input('description');
       $rating = $request->input('rating');

       $product = Product::where('product_id',$product_id)->first();
       $user = User::where('user_id',$user_id)->first();



       $review = new Review;
       $review->user_id = $user_id;
       $review->buyer_id = $buyer_id;
       $review->product_id = $product_id;
       $review->title = $title;
       $review->description = $description;
       $review->rating = $rating;
       $review->save();

       $notifybuyer = new notification;
       $notifybuyer->email = $user->user_email;
       $notifybuyer->item = $product->product_name;
       $notifybuyer->user_id = $product->user_id;
       $notifybuyer->product_id = $product_id;
       $notifybuyer->status = 'new review';
       $notifybuyer->type = 'review';
       $notifybuyer->save();

       return response()->json('Review submitted');


   }

   public function list(Request $request)
   {

      $product_id = $request->input('product_id');

      $review = Review::where('product_id',$product_id)->get();

      $reviewarray = array();

      foreach ($review->sortByDesc('created_at') as $reviews)
      {

        $product = Product::where('product_id',$product_id)->get();


        foreach($product as $products)

        {
            $json_array = json_decode($products->product_image, true);
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


            $buyer_id = $reviews->buyer_id;
            $buyer = User::where('user_id',$buyer_id)->get();

            foreach ($buyer as $buyers){
                  $temparray = [

                    'id' => $reviews->id,
                    'user_id' => $reviews->user_id,
                    'buyer_id' => $reviews->buyer_id,
                    'user_fname' => $buyers->user_fname,
                    'user_lname' => $buyers->user_lname,
                    'product_id' => $reviews->product_id,
                    'product_name' => $products->product_name,
                    'product_image' => $imageArray,
                    'title' => $reviews->title,
                    'description' => $reviews->description,
                    'rating' => $reviews->rating,


                  ];

                 array_push($reviewarray,$temparray);
 
                }
        }


      }

       return response()->json($reviewarray);


   }


   public function listuser(Request $request)
   {

      $user_id = $request->input('user_id');

      $review = Review::where('user_id',$user_id)->get();

      $reviewarray = array();

      foreach ($review->sortByDesc('created_at') as $reviews)
      {

        $product_id = $reviews->product_id;
        $product = Product::where('product_id',$product_id)->get();


        foreach($product as $products)

        {
            $json_array = json_decode($products->product_image, true);
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


            $buyer_id = $reviews->buyer_id;
            $buyer = User::where('user_id',$buyer_id)->get();

            foreach ($buyer as $buyers){
                  $temparray = [

                    'id' => $reviews->id,
                    'user_id' => $reviews->user_id,
                    'buyer_id' => $reviews->buyer_id,
                    'user_fname' => $buyers->user_fname,
                    'user_lname' => $buyers->user_lname,
                    'product_id' => $reviews->product_id,
                    'product_name' => $products->product_name,
                    'product_image' => $imageArray,
                    'title' => $reviews->title,
                    'description' => $reviews->description,
                    'rating' => $reviews->rating,


                  ];

                 array_push($reviewarray,$temparray);
 
                }
        }


      }

       return response()->json($reviewarray);




   }

   public function delete(Request $request)
   {
  
        $id = $request->input('id');

        $review = Review::where('id',$id)->first();
        $review->delete();

        return response()->json('Review deleted');

   }




        



     


  
    }



