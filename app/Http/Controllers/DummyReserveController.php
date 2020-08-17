<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Reserve;
use App\Product;
use App\User;
use App\log;
use App\Review;
use App\Notification;
use App\Negotiate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailer;

class DummyReserveController extends Controller

{

    public function listreserve(Request $request){

        $temparray = array();
        $finalarray = array();
        $imageArray = array();

        $status = $request->input('status');
        $category = $request->input('category');
        $user_id = $request->input('user_id');

        if($category == 'seller'){
            $reserve = Reserve::where('status',$status)->where('user_id',$user_id)->orderBy('created_at','DESC')->get();
        } elseif($category == 'buyer'){
            $reserve = Reserve::where('status',$status)->where('buyer_id',$user_id)->orderBy('created_at','DESC')->get();
        }

        foreach($reserve as $data){

            $product = Product::where('product_id',$data->product_id)->first();
            if($product){

                $sellerinfo = User::where('user_id',$product->user_id)->first();
            $buyerinfo = User::where('user_id',$data->buyer_id)->first();

            if($sellerinfo->user_type == 'company'){
                $username = $sellerinfo->companyname;
            } else {
                $username = $sellerinfo->user_fname . ' ' . $sellerinfo->user_lname;
            }

            if($buyerinfo->user_type == 'company'){
                $buyerusername = $buyerinfo->companyname;
            } else {
                $buyerusername = $buyerinfo->user_fname . ' ' . $buyerinfo->user_lname;
            }

            $imageArray = array();

            if($product->product_image){
         
                $format = json_decode($product->product_image, true);
                //$format = $product->product_image;
                foreach($format as $pic){
                    $url = 'https://codeviable.com/w2w2/public/image';
                    $public =  $url .'/'. $pic;
                    

                    $imagetempArray = [
                        'image' => $public,
                    ];

                    array_push($imageArray,$imagetempArray);   
                }
            } 

            $temparray = [

                'id' => $data->id,
                'status' => $data->status,
                'seller_id' => $data->user_id,
                'buyer_id' => $data->buyer_id,
                'product_name' => $product->product_name,
                'offeredprice' => $data->offeredprice,
                'quantity' => $data->quantity,
                'unit' => $data->unit,
                'info' => $data->info,
                'buyer_name' => $buyerusername,
                'buyer_contact' => $buyerinfo->user_contact,
                'buyer_email' => $buyerinfo->user_email,
                'product_id' => $data->product_id,
                'product_category' => $data->category,
                'product_image' => $imageArray,
                'remarks' => $data->remarks,
                'seller_type' => $sellerinfo->user_type,
                'seller_name' => $username,
                'seller_email' => $sellerinfo->user_email,
                'seller_contact' => $sellerinfo->user_contact,
                'pic_name' => $product->name,
                'pic_contact' => $product->contact,
                'pic_email' => $product->email,
                'finalprice' => $data->finalprice,
                'finalunit' => $data->finalunit,
                'finalquantity' => $data->finalquantity,
                'date' => $data->updated_at,

            ];
        
            array_push($finalarray,$temparray);


            }
            
        }

        return response()->json(['status'=>'success','value'=>$finalarray]);

    }

    public function sellerreject(Request $request){

        $reserveid = $request->input('reserveid');
        $remarks = $request->input('remarks');

        $reserve = Reserve::where('id',$reserveid)->first();
        $buyerinfo = User::where('user_id',$reserve->buyer_id)->first();
        $productinfo = Product::where('product_id',$reserve->product_id)->first();

        $status = 'rejected';
        $reserve->status = $status;
        $reserve->remarks = $remarks;

        //negotiate
        $negotiate = new Negotiate;
        $negotiate->reserveid = $reserveid;
        $negotiate->remarks = $remarks;
        $negotiate->negostatus = 'reject';

        //notification system buyer
        $notification = new Notification;
        $notification->user_id = $reserve->buyer_id;
        $notification->product_id = $reserve->product_id;
        $notification->type = 'sellerrejectproduct';
        $notification->item = $productinfo->product_name;

        //notification email buyer
        $tempmessages = 'Your reserved ' . $productinfo->product_name .' have been rejected by seller';
        $messages = $tempmessages;

        Mail::raw( $messages , function ($message) use($buyerinfo){
            $message->to($buyerinfo->user_email);
            $message->from('hafizaldevtest@gmail.com', 'muhamad ijal');
            $message->subject('Ecowaste Market');
        });


        $notification->save();
        $negotiate->save();
        $reserve->save();

        return response()->json(['status'=>'success','value'=>'success seller reserve reject']);

    }

    public function sellerapprove(Request $request){

        $reserveid = $request->input('reserveid');
        $finalprice = $request->input('finalprice');
        $finalquantity = $request->input('finalquantity');
        $finalunit = $request->input('finalunit');

        $status = 'sold';

        $reserve = Reserve::where('id',$reserveid)->first();

        $reserve->status = $status;
        $reserve->finalprice = $finalprice;
        $reserve->finalquantity = $finalquantity;
        $reserve->finalunit = $finalunit;

        $productinfo = Product::where('product_id',$reserve->product_id)->first();

        //notification seller
        $notification = new Notification;
        $notification->user_id = $reserve->user_id;
        $notification->product_id = $reserve->product_id;
        $notification->type = 'sellerapproveproduct';
        $notification->item = $productinfo->product_name;

        $notification->save();

        //notification buyer
        $notification = new Notification;
        $notification->user_id = $reserve->buyer_id;
        $notification->product_id = $reserve->product_id;
        $notification->type = 'sellerapproveproduct';
        $notification->item = $productinfo->product_name;

        $notification->save();

        //notification email buyer
        $tempmessages = 'Your reserved ' . $productinfo->product_name .' have been approve by seller';
        $messages = $tempmessages;

        $useremail = User::where('user_id',$reserve->buyer_id)->first();

        Mail::raw( $messages , function ($message) use($useremail){
            $message->to($useremail->user_email);
            $message->from('hafizaldevtest@gmail.com', 'muhamad ijal');
            $message->subject('Ecowaste Market');
        });

        $reserve->save();

        return response()->json(['status'=>'success','value'=>'success seller reserve approve']);


    }

    //start
    public function negotiatedetails(Request $request){

        $reserveid = $request->input('reserveid');

        $listnegotiate = Negotiate::where('reserveid',$reserveid)->orderBy('created_at','asc')->get();

        return response()->json(['statue'=>'success','value'=>$listnegotiate]);

    }

    public function buyerresubmit(Request $request){

        $reserveid = $request->input('reserveid');
        $price = $request->input('price');
        $quantity = $request->input('quantity');
        $unit = $request->input('unit');
        $desc = $request->input('desc');

        $reserveinfo = Reserve::where('id',$reserveid)->first();
        $productinfo = Product::where('product_id',$reserveinfo->product_id)->first();
        $sellerinfo = User::where('user_id',$reserveinfo->user_id)->first();

        $negotiate = new Negotiate;
        $negotiate->reserveid = $reserveid;
        $negotiate->price = $price;
        $negotiate->quantity = $quantity;
        $negotiate->unit = $unit;
        $negotiate->desc = $desc;
        $negotiate->negostatus = 'resubmit';

        //notification system seller
        $notification = new Notification;
        $notification->user_id = $reserveinfo->user_id;
        $notification->product_id = $reserveinfo->product_id;
        $notification->type = 'buyerresubmit';
        $notification->item = $productinfo->product_name;

        //notification email seller
        $tempmessages = 'Your product name' . $productinfo->product_name .' have been resubmit by buyer';
        $messages = $tempmessages;

        Mail::raw( $messages , function ($message) use($sellerinfo){
            $message->to($sellerinfo->user_email);
            $message->from('hafizaldevtest@gmail.com', 'muhamad ijal');
            $message->subject('Ecowaste Market');
        });

        $negotiate->save();
        $notification->save();

        return response()->json(['status'=>'success','value'=>'success resubmit']);
    }

    //end
    
    public function buyercancel(Request $request){

        $reserveid = $request->input('reserveid');

        $status = 'cancel';

        $reserve = Reserve::where('id',$reserveid)->first();

        $reserve->status = $status;
        $reserve->save();

        return response()->json(['status'=>'success','value'=>'success buyer reserve cancel']);

    }

    public function buyerconfirm(Request $request){
        $reserveid = $request->input('reserveid');

        $status = 'confirm';

        $reserve = Reserve::where('id',$reserveid)->first();

        $reserve->status = $status;
        $reserve->save();

        return response()->json(['status'=>'success','value'=>'success buyer reserve confirm']);

    }

    
    public function sellersold(Request $request){

        $reserveid = $request->input('reserveid');
        $finalprice = $request->input('finalprice');
        $finalquantity = $request->input('finalquantity');
        $finalunit = $request->input('finalunit');

        $status = 'sold';

        $reserve = Reserve::where('id',$reserveid)->first();

        $reserve->status = $status;
        $reserve->finalprice = $finalprice;
        $reserve->finalquantity = $finalquantity;
        $reserve->finalunit = $finalunit;
        $reserve->save();

        return response()->json(['status'=>'success','value'=>'success seller reserve sold']);

    }

    public function buyercomplete(Request $request){

        $reserveid = $request->input('reserveid');
        $titlereview = $request->input('titlereview');
        $descreview = $request->input('descreview');
        $ratingreview = $request->input('ratingreview');

        $reviewdetails = Reserve::where('id',$reserveid)->first();

        $product_id = $reviewdetails->product_id;
        $seller_id = $reviewdetails->user_id;
        $buyer_id = $reviewdetails->buyer_id;

        $review = new Review;
        $review->user_id = $seller_id;
        $review->buyer_id = $buyer_id;
        $review->product_id = $product_id;
        $review->title = $titlereview;
        $review->description = $descreview;
        $review->rating = $ratingreview;

        $sellerinfo = User::where('user_id',$seller_id)->first();
        $productinfo = Product::where('product_id',$product_id)->first();

        $notifybuyer = new notification;
        $notifybuyer->email = $sellerinfo->user_email;
        $notifybuyer->item = $productinfo->product_name;
        $notifybuyer->user_id = $seller_id;
        $notifybuyer->product_id = $product_id;
        $notifybuyer->status = 'new review';
        $notifybuyer->type = 'review';

        $reviewdetails->status = 'completed';

        $notifybuyer->save();
        $review->save();
        $reviewdetails->save();

        //calculation review company
        $totalreview = 0;
        $listreview = Review::where('user_id',$sellerinfo->user_id)->get();
        $reviewCount = count($listreview);
        foreach($listreview as $data){
            
            $totalreview = $totalreview + $data->rating;

        }

        $finalreview = $totalreview / $reviewCount;
        

        $final = round($finalreview, 0);

        $sellerinfo->review = $final;
        $sellerinfo->save();

        //calculation review product   
        $totalreviewproduct = 0;
        $listreviewproduct = Review::where('product_id',$product_id)->get();
        $countrevpro = count($listreviewproduct);
        foreach($listreviewproduct as $data){
            $totalreviewproduct = $totalreviewproduct + $data->rating;
        }

        $finalreviewproduct = $totalreviewproduct / $countrevpro;

        $finalproduct = round($finalreviewproduct, 0);
        $productinfo->rating = $finalproduct;
        $productinfo->save();
        

        return response()->json(['status'=>'success','value'=>'success buyer review and complete']);
    }

    public function buyandsellactivity(Request $request){

        $user_id = $request->input('user_id');
        $finalArray = array();
        
        $listSeller = Reserve::where('user_id',$user_id)->orWhere('buyer_id',$user_id)->orderBy('created_at','DESC')->get();

        if($listSeller){

            foreach($listSeller as $data){

                if($data->user_id == $user_id){
                    $type = 'sell';
                } else{
                    $type = 'buy';
                }

                $product = Product::where('product_id',$data->product_id)->first();
                $temparray = [

                    'id' => $data->id,
                    'product_name' => $product->product_name,
                    'type' => $type,
                    'status' => $data->status,
                    'date' => $data->updated_at,

                ];

                array_push($finalArray,$temparray);

            }

        }

        return response()->json(['status'=>'success','value'=>$finalArray]);

    }
}
