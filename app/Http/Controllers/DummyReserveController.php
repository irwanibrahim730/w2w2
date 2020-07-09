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

class DummyReserveController extends Controller

{
  

    public function reserveproduct(Request $request){

        $product_id = $request->input('product_id');
        $buyer_id = $request->input('buyer_id');
        $offeredprice = $request->input('offeredprice');
        $offeredmaxprice = $request->input('offeredmaxprice');
        $quantity = $request->input('quantity');
        $unit = $request->input('unit');
        $info = $request->input('info');

        $products = Product::find($product_id);

        $data = new Reserve;
        $data->user_id = $products->user_id;
        $data->product_id = $products->product_id;
        $data->buyer_id = $buyer_id;
        $data->offeredprice = $offeredprice;
        $data->offeredmaxprice = $offeredmaxprice;
        $data->quantity = $quantity;
        $data->unit = $unit;
        $data->info = $info;
        $data->category = $products->maincategory;
        $data->status = 'reserved';

        $data->save();

        return response()->json(['status'=>'success','value'=>'success product reserved']);

    }

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
            $sellerinfo = User::where('user_id',$product->user_id)->first();

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
                'offeredprice' => $data->offeredprice,
                'offeredmaxprice' => $data->offeredmaxprice,
                'info' => $data->info,
                'unit' => $data->unit,
                'quantity' => $data->quantity,
                'product_id' => $data->product_id,
                'product_name' => $product->product_name,
                'product_category' => $data->category,
                'product_image' => $imageArray,
                'remarks' => $data->remarks,
                'seller_name' => $sellerinfo->user_fname,
                'seller_email' => $sellerinfo->user_email,
                'seller_contact' => $sellerinfo->user_contact,
                'finalprice' => $data->finalprice,
                'finalunit' => $data->finalunit,
                'finalquantity' => $data->finalquantity,
                'date' => $data->updated_at,

            ];
        
            array_push($finalarray,$temparray);
        }

        return response()->json(['status'=>'success','value'=>$finalarray]);

    }

    public function sellerreject(Request $request){

        $reserveid = $request->input('reserveid');
        $remarks = $request->input('remarks');

        $reserve = Reserve::where('id',$reserveid)->first();

        $status = 'rejected';

        $reserve->status = $status;
        $reserve->remarks = $remarks;
        $reserve->save();

        return response()->json(['status'=>'success','value'=>'success seller reserve reject']);

    }

    public function sellerapprove(Request $request){

        $reserveid = $request->input('reserveid');

        $status = 'approve';

        $reserve = Reserve::where('id',$reserveid)->first();

        $reserve->status = $status;
        $reserve->save();

        return response()->json(['status'=>'success','value'=>'success seller reserve approve']);


    }
    
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
}
