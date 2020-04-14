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

        return response()->json($notify);

    }

    public function liststatus(Request $request)
    { 
        $user_id = $request->input('user_id');
        $status = $request ->input('status');

        $notify = Notification::where('user_id',$user_id)->get();
     


        $notifyArray = array();

        foreach ($notify as $notification){

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
                  'created_at' => $notification->created_at,
                  'updated_at' => $notification->updated_at,
              ];


              array_push($notifyArray,$tempArray);

          }
      }
      

         return response()->json($notifyArray);

  }


    }
        
    


    