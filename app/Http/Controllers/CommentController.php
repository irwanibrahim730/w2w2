<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Comment;
use App\User;
use App\Product;
use App\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailer;

class CommentController extends Controller

{
    public function addcomment(Request $request)
    {
        
        $product_id = $request->input('product_id');
        $description = $request->input('description');
        $user_id = $request->input('user_id');

        $product = Product::where('product_id',$product_id)->first();
 
        if($product){

            $data = new Comment;
            $data->description = $description;
            $data->status = 'visible';
            $data->title = 'null';
            $data->product_id = $product_id;
            $data->user_id = $user_id;

            //notification system
            $notify = new Notification;
            $notify->user_id = $product->user_id;
            $notify->product_id = $product->product_id;
            $notify->item = $product->product_name;
            $notify->status = 'new comment';
            $notify->type = 'comment';

            //notification email owner product
            $date = date('Y-m-d H:i:s');
            $tempmessages = 'You have receive comment from product:';
            $pro = 'Product :'.$product->product_name;
            $desc = 'Comment :'.$description;
            $date = 'Date :' . $date;
            $messages = $tempmessages."\n".$pro."\n".$desc."\n".$date;

            $useremail = User::where('user_id',$product->user_id)->first();

            Mail::raw( $messages, function ($message) use($useremail){
                $message->to($useremail->user_email);
                $message->from('hafizaldevtest@gmail.com', 'muhamad ijal');
                $message->subject('EcoWaste Market');

            });

            $data->save(); 
            $notify->save();

            return response()->json(['status'=>'success']);
        } else {
            return response()->json(['status'=>'failed','value'=>'product not exist']);
        }

        

    }


    public function listcomment(Request $request)
    {
           $product_id = $request->input('product_id');
           $status = $request->input('status');
           $finalArray = array(); 

           if($status){
            $comment = Comment::where('product_id',$product_id)
                    ->where('status',$status)
                    ->orderBy('created_at','desc')
                    ->get();
           } else {
            $comment = Comment::where('product_id',$product_id)
                    ->where('status','visible')
                    ->orderBy('created_at','desc')->get();
           }
           
           foreach ($comment as $comments){
            
                $user_id = $comments->user_id;
                $users = User::where('user_id',$user_id)->first();
                
                if($users->user_type == 'company'){
                    $fname = $users->companyname;
                    $lname = '';
                } else {
                    $fname = $users->user_fname;
                    $lname = $users->user_lname; 
                }
       
                            $tempArray = [
                            'id' => $comments->id,
                            'product_id' =>$comments->product_id,
                            'user_id' => $comments->user_id,
                            'user_fname' =>$fname,
                            'user_lname' => $lname,
                            'title'=>$comments->title,
                            'description'=>$comments->description,
                            'status' => $comments->status,
                            'created_at' => $comments->created_at->format('d M Y - H:i:s'),
                            'updated_at' => $comments->updated_at->format('d M Y - H:i:s'),
                        ];

                    array_push($finalArray,$tempArray);
                
            

            }
      
        return response()->json(['status'=>'success','value'=>$finalArray]);
    }


    public function status(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');
    
        $comment = Comment::where('id',$id)->first();
        $comment->status = $status;
        $comment->save();

        return response()->json(['status'=>'success','value'=>'status changes']);

    }


    public function delete(Request $request)
    {
        $id = $request->input('id');

        $comments = Comment::where('id',$id)->first();
        $comments->delete();

        return response()->json(['status'=>'success']);

    }

    public function list()
    {

        $comment = Comment::all();
        $finalArray = array();
            
        foreach ($comment->sortByDesc('created_at') as $comments){
        $user_id = $comments->user_id;
        $user = User::where('user_id',$user_id)->first(); 

        if($user){

            if($user->user_type == 'company'){
                $fname = $user->companyname;
                $lname = '';
            } else {
                $fname = $user->user_fname;
                $lname = $user->user_lname; 
            }

            
                            $tempArray = [
                            'id' => $comments->id,
                            'product_id' => $comments->product_id,
                            'user_id' => $comments->user_id,
                            'user_fname' =>$fname,
                            'user_lname' => $lname,
                            'title'=> $comments->title,
                            'description'=> $comments->description,
                            'status' => $comments->status,
                            'created_at' => $comments->created_at->format('d M Y - H:i:s'),
                            'updated_at' => $comments->updated_at->format('d M Y - H:i:s'),
                    ];
    
                    array_push($finalArray,$tempArray);
                    

        }
        
        }

        return response()->json(['status'=>'success','value'=>$finalArray]);

    }

    public function listid(Request $request)
    {
    
        $product_id = $request->input('product_id');

        $comment = Comment::where('product_id',$product_id)->where('status','visible')->get();
    
        $finalArray = array();
        
        foreach ($comment->sortByDesc('created_at') as $comments){
        $user_id = $comments->user_id;
        $user = User::where('user_id',$user_id)->first(); 
        
        if($user->user_type == 'company'){
            $fname = $user->companyname;
            $lname = '';
        } else {
            $fname = $user->user_fname;
            $lname = $user->user_lname; 
        }
        
        $tempArray = [
            'id' => $comments->id,
            'product_id' =>$comments->product_id,
            'user_id' => $comments->user_id,
            'user_fname' =>$fname,
            'user_lname' => $lname,
            'title'=>$comments->title,
            'description'=>$comments->description,
            'status' => $comments->status,
            'created_at' => $comments->created_at->format('d M Y - H:i:s'),
            'updated_at' => $comments->updated_at->format('d M Y - H:i:s'),
            ];

            array_push($finalArray,$tempArray);

        }
    
        return response()->json(['status'=>'success','value'=>$finalArray]);
    }

    public function detail(Request $request)
    {
    
        $id = $request->input('id');

        $comment = Comment::where('id',$id)->get();

        $finalArray = array();
            
            foreach ($comment as $comments){

            $user_id = $comments->user_id;
            $user = User::where('user_id',$user_id)->get(); 
        
                foreach($user as $users){
        
                        $tempArray = [
                            
                            'id' => $comments->id,
                            'product_id' =>$comments->product_id,
                            'user_id' => $comments->user_id,
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

        return response()->json(['status'=>'success','value'=>$finalArray]);
    }

}