<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Comment;
use App\User;

class CommentController extends Controller

{
    public function addcomment(Request $request)
    {
        
        $product_id = $request->input('product_id');
        $title = $request->input('title');
        $description = $request->input('description');
        $user_id = $request->input('user_id');

 


        $data = new Comment;
        $data->title = $title;
        $data->description = $description;
        $data->status = 'visible';
        $data->product_id = $product_id;
        $data->user_id = $user_id;
        $data->save(); 

        return response()->json('Comment Added');

    }


    public function listcomment(Request $request)
    {
            
           $product_id = $request->input('product_id');
           $status = $request->input('status');
           

           $comment = Comment::where('product_id',$product_id)->get();
 
           $finalArray = array();  
        
           foreach ($comment as $comments){

            $user_id = $comments->user_id;
            $user = User::where('user_id',$user_id)->get();
           
            if($comments->status == $status){


                  foreach($user as $users){
          
            $tempArray = [
              'id' => $comments->id,
              'product_id' =>$comments->product_id,
              'user_fname' =>$users->user_fname,
              'user_lname' => $users->user_lname,
              'title'=>$comments->title,
              'description'=>$comments->description,
              'status' => $comments->status,
              'created_at' => $comments->created_at->format('d M Y - H:i:s'),
              'updated_at' => $comments->updated_at->format('d M Y - H:i:s'),
          ];

           array_push($finalArray,$tempArray);
         }
        }
        }
      
    
           return response()->json($finalArray); 
    


     
}


  





public function hidecomment(Request $request){

      $id = $request->input('id');

      $comment = Comment::where('id',$id);



      $comment->status ='hidden';
      $comment->save();
      
      return response()->json('comment hidden');
}


public function unhide(Request $request)
{
  $id = $request->input('id');

  $comment = Comment::where('id',$id);

  $comment->status = 'visible';
  $comment->save();

  return response()->json('comment unhide');
}


public function delete(Request $request)
{
    $id = $request->input('id');

    $comments = Comment::where('id',$id)->first();
    $comments->delete();

    return response()->json('comment deleted');



}

public function list()
{

   $comment = Comment::all();

   return response()->json($comment);

}


}