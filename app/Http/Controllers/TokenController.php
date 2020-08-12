<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Token;
use App\User;
use App\Package;
use App\History;
use App\Purchase;
use Billplz\Laravel\Billplz;

class TokenController extends Controller

{
    public function store (Request $request){

 
        $data = new Token();
        $data->type = $request->input('type');
        $data->quantity = $request->input('quantity');
        $data->price = $request->input('price');
        $data->discount = $request->input('discount');
        $data->description = $request->input('description');
        $data->save();


        return response()->json(['status'=>'success','value'=>'token added']);

        }

        public function index(){

            $tokenArray = array();

            $token = Token::all();

            foreach($token as $data){

                $tempArray = [

                    'id' => $data->id,
                    'type' => $data->type,
                    'quantity' => $data->quantity,
                    'price' => $data->price,
                    'discount' => $data->discount,

                ];
            array_push($tokenArray,$tempArray);

            }

           
            return response()->json(['status'=>'success','value'=>$tokenArray]);
    
        }
    
        public function show(Request $request){
    
            $id = $request->input('id');
            $data = Token::where('id',$id)->get();

            return response()->json(['status'=>'success','value'=>$data]);
        }


        public function destroy(Request $request){

            $id = $request->input('id');
    
            $data = Token::where('id',$id)->first();
            $data->delete();
  
            return response()->json(['status'=>'success','value'=>'Token deleted']);
        }

        public function edit(Request $request)
        {

            $id = $request->input('id');
    
            $data = Token::where('id',$id)->first();

            $type = $request->input('type');
            $quantity = $request->input('quantity');
            $price = $request->input('price');
            $discount = $request->input('discount');
            $description = $request->input('description');
            
            if($type == null){
               
                $type = $data->type;
    
            }
    
            if($quantity == null){
    
                $quantity = $data->quantity;
            }
    
            if($price== null){
    
                $price = $data->price;
            }
    
            if($discount == null){
    
                $discount = $data->discount;
            }
    
    
            if($description == null){
    
                $description = $data->description;
            }
    
            $data->type = $type;
            $data->quantity = $quantity;
            $data->price = $price;
            $data->discount = $discount;
            $data->description = $description;
            $data->save();
    
    
            return response()->json(['status'=>'success','value'=>'Package updated']);
        }


        public function addtoken (Request $request )
        {

            $user_id = $request->input('user_id');
            $netprice = $request->input('netprice');
            $quantitytoken = $request->input('quantitytoken');

            $user = User::find($user_id);

                if($user == null){
                    return response()->json(['status'=>'failed','value'=>'user not exist']);
                } else {

                    $balance = $user->balancetoken;
                    $total = $balance + $quantitytoken;
                    $user->balancetoken = $total;

                    $history = new History;
                    $history->user_id = $user_id;
                    $history->type = 'token';
                    $history->name = $quantitytoken;
                    $history->price = $netprice;
                    $user->save();
                    $history->save();

                

                    return response()->json(['status'=>'success','value'=>'token added to user account']);
                    
                    // $balance = $user->balancetoken;
                    // $quantitytoken;
                    
                    // $total = $balance + $quantitytoken;

                    // if($user->user_type == 'company'){
                    //     $finalname = $user->companyname;
                    // } else {
                    //     $finalname = $user->user_fname;
                    // }


                    // $email = $user->user_email;
                    // $mobile = $user->user_contact;
                    // $name = $finalname;
                    // $tempprice = $netprice;
                    // $token = $quantitytoken .' token';

                    // echo $token;

                    // //create bill billplz
                    // $price = $tempprice * 100;

                    // $bill = Billplz::bill('v3')->create(

                    //     $collectionId = "kfstwuda",
                    //     $email,
                    //     $mobile,
                    //     $name,
                    //     $price,
                    //     '-',
                    //     $token,
                    //     [
                    //         'redirect_url' => 'http://codeviable.com/w2w2/public/billplz/redirect'
                    //     ]
                

                    // );  
                    
                    // //save to purchase table
                    // $purchase = new Purchase;
                    // $purchase->billid = $bill->toArray()['id'];
                    // $purchase->userid = $user_id;

                    // $purchase->save();

                    // return redirect($bill->toArray()['url']);

                }

        }


        public function givetoken (Request $request)
        {

          $user_id = $request->input('user_id');
          $amount = $request->input('amount');

          $user = User::where('user_id',$user_id)->first();

          if($user){

            $balance = $user->balancetoken;
          
            $total = $balance + $amount;;

            $user->balancetoken = $total;

            $history = new History;
            $history->user_id = $user_id;
            $history->type = 'token';
            $history->name = $amount;
            $history->price = '-';

            $user->save();
            $history->save();

            return response()->json(['status'=>'success','value'=>'token added']);

          } else {
              return response()->json(['status'=>'error','value'=>'user not exist']);
          }

        }
    
        public function checkbalance(Request $request){

            $userid = $request->input('userid');
            $packageid = $request->input('packageid');

            $package = Package::find($packageid);
            $user = User::find($userid);

            if($package == null || $user == null){

                return response()->json(['status'=>'failed','value'=>'package or user not exist']);
                
            } else {

                $packageprice = $package->package_price;
                $userbalance = $user->balancetoken;
    
                if($userbalance == null){
                    $userbalance = 0;
                }
    
                if($userbalance > $packageprice){
                    return response()->json(['status'=>'success','value'=>'you enable to subscribe this package']);
                } elseif($packageprice > $userbalance){
                    return response()->json(['status'=>'success','value'=>'sorry your token is insufficient balance']);
                } else {
                    return response()->json(['status'=>'failed']);
                }

            }

        }

        public function mytoken(Request $request){

            $transactionarray = array();
            $finalarray = array();

            $userid = $request->input('userid');
            
            $user = User::find($userid);
            //balance
            $tokenbalance = $user->balancetoken;

            
            //transaction
            $transaction = History::where('user_id',$userid)->orderBy('created_at','DESC')->get();

            foreach($transaction as $data){

                $dateformat = $data->created_at->format('Y-m-d h:m:s');

                // {{ $post->created_at->format('Y-m-d') }}
                if($data->type == 'token'){
                    $token = '+'.$data->name;
                    $price = $data->price;
                } elseif($data->type == 'package'){
                    $packageid = Package::where('package_id',$data->name)->first();
                    $temptoken = $packageid->package_price;
                    $token = '-'.$temptoken;
                    $price = '-';
                }

                if($data->billid){
                    $receiptstatus = 'yes';
                } else {
                    $receiptstatus = 'no';
                }

                $tempArray = [

                    'id' => $data->token,
                    'token' => $token,
                    'price' => $price,
                    'date' => $dateformat,
                    'receiptstatus' => $receiptstatus,
                    'billid' => $data->billid,
                    'status' => 'success',
    
                ];

                array_push($transactionarray,$tempArray);

            }

            $finalarray = [

                'tokenbalance' => $tokenbalance,
                'transaction' => $transactionarray,

            ];

            return response()->json(['status'=>'success','value'=>$finalarray]);

        }
        
    
}
    