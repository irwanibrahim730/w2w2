<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Notification;
use App\Product;
use App\User;

class NotificationController extends Controller

{
    public function list()
    {
        $notify = Notification::all();

        $notifyArray = array();

        foreach ($notify->sortByDesc('created_at') as $notification){



       $id = $notification->product_id;
       $product = Product::where('product_id',$id)->get();
       foreach($product->sortByDesc('created_at') as $products){

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


           $tempArray = [

               'id' => $notification->id,
               'email' => $notification->email,
               'item' => $notification->item,
               'user_id' => $notification->user_id,
               'product_id' => $notification->product_id,
               'product_image' => $imageArray,
               'rejectremark' => $products->rejectremark,
               'status' => $notification->status,
               'type' => $notification->type,
               'created_at' => $notification->created_at,
               'updated_at' => $notification->updated_at,
           ];


           array_push($notifyArray,$tempArray);

       }
   
   }

      return response()->json($notifyArray);


    }

    public function liststatus(Request $request)
    { 
        $user_id = $request->input('user_id');
        $status = $request ->input('status');

        $notify = Notification::where('user_id',$user_id)->get();
     


        $notifyArray = array();

        foreach ($notify->sortByDesc('created_at') as $notification){

            if( $notification->status == $status)

            {

            $id = $notification->product_id;
            $product = Product::where('product_id',$id)->get();
            foreach($product as $products){

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


                $tempArray = [

                    'id' => $notification->id,
                    'email' => $notification->email,
                    'item' => $notification->item,
                    'user_id' => $notification->user_id,
                    'product_id' => $notification->product_id,
                    'product_image' => $imageArray,
                    'rejectremark' => $products->rejectremark,
                    'status' => $notification->status,
                    'type' => $notification->type,
                    'created_at' => $notification->created_at,
                    'updated_at' => $notification->updated_at,
                ];


                array_push($notifyArray,$tempArray);

            }
        }
        }

           return response()->json($notifyArray);

    }

    public function delete(Request $request)
    {

        $id = $request->input('id');

        $notify = Notification::where('id',$id)->first();
        $notify->delete();
   
         return response()->json('notification deleted');
    }

    public function detail(Request $request)

    {

      $id = $request->input('id');

      $notify = Notification::where('id',$id)->get();
   
       $notifyArray = array();

      foreach ($notify as $notification){

          $id = $notification->product_id;
          $product = Product::where('product_id',$id)->get();
          foreach($product as $products){

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


              $tempArray = [

                  'id' => $notification->id,
                  'email' => $notification->email,
                  'item' => $notification->item,
                  'user_id' => $notification->user_id,
                  'product_id' => $notification->product_id,
                  'product_image' => $imageArray,
                  'rejectremark' => $products->rejectremark,
                  'status' => $notification->status,
                  'type' => $notification->type,
                  'created_at' => $notification->created_at,
                  'updated_at' => $notification->updated_at,
              ];


              array_push($notifyArray,$tempArray);

          }
      }
      

         return response()->json($notifyArray);

  }



  public function listid (Request $request)

   {

   $user_id = $request->input('user_id');

   $notify = Notification::where('user_id',$user_id)->get();

   $notifyArray = array();

   foreach ($notify->sortByDesc('created_at') as $notification){



       $id = $notification->product_id;
       $product = Product::where('product_id',$id)->get();
       foreach($product->sortByDesc('created_at') as $products){

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


           $tempArray = [

               'id' => $notification->id,
               'email' => $notification->email,
               'item' => $notification->item,
               'user_id' => $notification->user_id,
               'product_id' => $notification->product_id,
               'product_image' => $imageArray,
               'rejectremark' => $products->rejectremark,
               'status' => $notification->status,
               'type' => $notification->type,
               'created_at' => $notification->created_at,
               'updated_at' => $notification->updated_at,
           ];


           array_push($notifyArray,$tempArray);

       }
   
   }

      return response()->json($notifyArray);



   }

   public function notifiuser(Request $request){

        $userid = $request->input('userid');
        $finalArray = array();    

        $notifications = Notification::where('user_id',$userid)
                ->orderBy('created_at','desc')
                ->get();
        
        foreach($notifications as $notification){

            if($notification->type == 'item'){
                $message = "success add advertisement ". $notification->item ." and ready to review";
            } elseif($notification->type == 'approval'){
                if($notification->status == 'approved'){
                    $message = "admin approve advertisement ". $notification->item;
                } elseif($notification->status == 'rejected'){
                    $message = "admin reject advertisement ". $notification->item;
                }
            } elseif($notification->type == 'comment'){
                $message = "receive comment from product ". $notification->item;
            } elseif($notification->type == 'review'){
                $message = "review from product ". $notification->item;
            } elseif($notification->type == 'registration'){
                $message = "Thank you, your registration is activated";
            }

            $dateformat = $notification->created_at->format('Y-m-d h:m:s');

            $temparray = [

                'id' => $notification->id,
                'type' => $notification->type,
                'message' => $message,
                'date' => $dateformat,

            ];

            array_push($finalArray,$temparray);

        }

        return response()->json(['status'=>'success','value'=>$finalArray]);

   }


}
        
    


    