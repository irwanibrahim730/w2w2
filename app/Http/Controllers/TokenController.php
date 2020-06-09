<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Token;
use App\User;
use App\Package;
use App\History;

class TokenController extends Controller

{
    public function store (Request $request){

 
        $data = new Token();
        $data->type = $request->input('type');
        $data->quantity = $request->input('quantity');
        $data->price = $request->input('price');
        $data->freetoken = $request->input('freetoken');
        $data->netprice = $request->input('netprice');
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
            $freetoken = $request->input('freetoken');
            $netprice = $request->input('netprice');
            $discount = $request->input('discount');
            $description = $request->input('description');
            
            
            
            if($freetoken == null)
            {
                $freetoken = $data->freetoken;
            }

            if($netprice == null)
            {
                $netprice = $data->netprice;
            }
            
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
            $data->freetoken = $freetoken;
            $data->netprice = $netprice;
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
                $quantitytoken;
                
                $total = $balance + $quantitytoken;


                $user->balancetoken = $total;
                $user->save();

                $history = new History;
                $history->user_id = $user_id;
                $history->type = 'token';
                $history->name = $quantitytoken;
                $history->price = $netprice;
                $history->save();

                return response()->json(['status'=>'success','value'=>'token added to user account']);

            }

        }


        public function givetoken (Request $request)
        {

          $user_id = $request->input('user_id');
          $amount = $request->input('amount');

          $user = User::where('user_id',$user_id)->first();


          $balance = $user->balancetoken;
          
          $total = $balance + $amount;;

          $user->balancetoken = $total;
          $user->save();

          return response()->json(['status'=>'success','value'=>'token added']);


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
                    return response()->json(['status'=>'success','value'=>'success to subscribe this package']);
                } elseif($packageprice > $userbalance){
                    return response()->json(['status'=>'success','value'=>'sorry your token is insufficient balance']);
                } else {
                    return response()->json(['status'=>'failed']);
                }

            }

        }
        
    
}
    