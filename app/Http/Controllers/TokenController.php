<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Token;
use App\User;

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


    
        return response()->json('Token added');

        }

        public function index(){
            $data = Token::all();
    
           return response()->json($data);
    
        }
    
        public function show(Request $request){
    
            $id = $request->input('id');
            $data = Token::where('id',$id)->get();
            return response()->json($data);
        }


        public function destroy(Request $request){

            $id = $request->input('id');
    
            $data = Token::where('id',$id)->first();
            $data->delete();
        
            return response()->json('Token deleted');
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
    
            $data->package_name = $package_name;
            $data->package_limit = $package_limit;
            $data->package_duration = $package_duration;
            $data->package_price = $package_price;
            $data->premiumlist = $premiumlist;
            $data->description = $description;
            $data->save();
    
    
        
            return response()->json('package updated');
        }


        public function addtoken (Request $request )
        {

        $id = $request->input('id');
        $user_id = $request->input('user_id');

        $token = Token::where('id',$id)->first();
        $user = User::where('user_id',$user_id)->first();


        $user->balancetoken = $token->quantity;
        $user->save();


        return response()->json('token added to user account');

        }


        public function givetoken (Request $request)
        {

          $user_id = $request->input('user_id');
          $amount = $request->input('amount');

          $user = User::where('user_id',$user_id)->first();


          $total = $user->balancetoken + $amount;

          $user->balancetoken = $total;
          $user->save();

          return response()->json('token added');


        }
        
    
}
    