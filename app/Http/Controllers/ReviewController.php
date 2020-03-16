<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Review;

class ReviewController extends Controller

{
   public function store(Request $request)
   {

       $user_id = $request->input('user_id');
       $product_id = $request->input('product_id');
       $title = $request->input('title');
       $description = $request->input('description');
       $rating = $request->input('rating');


       $review = new Review;
       $review->user_id = $user_id;
       $review->product_id = $product_id;
       $review->title = $title;
       $review->description = $description;
       $review->rating = $rating;
       $review->save();

       return response()->json('Review submitted');


   }

   public function list(Request $request)
   {

      $product_id = $request->input('product_id');

      $review = Review::where('product_id',$product_id)->get();

       return response()->json($review);


   }

   public function delete(Request $request)
   {
  
        $id = $request->input('id');

        $review = Review::where('id',$id)->first();
        $review->delete();

        return response()->json('Review deleted');

   }


  
    }



