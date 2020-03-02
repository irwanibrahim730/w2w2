<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Comment;

class CommentController extends Controller

{
    public function addcategory(Request $request)
    {
        
        $product_id = $request->input('product_id');
        $title = $request->input('title');
        $description = $request->input('description');


        $data = new Comment;
        $data->title = $title;
        $data->description = $description;
        $data->product_id = $product_id;
        $data->save(); 

        return response()->json('Comment Added');

    }


    public function listcomment(Request $request)
    {
        
          $product_id = $request->input('product_id');

          $comment = Comment::where('product_id',$product_id)->get(); 
           $finalArray = array();  
        

           foreach ($comment as $comments){
          
            $tempArray = [
              'product_id' =>$comments->product_id,
              'id' => $comments->id,
              'title'=>$comments->title,
              'description'=>$comments->description,
          ];

          array_push($finalArray,$tempArray);
        }
    
          return response()->json($finalArray); 

     
}


public function delete(Request $request)
{
    $id = $request->input('id');

    $comments = Comment::where('id',$id)->first();
    $comments->delete();

    return response()->json('comment deleted');



}


}