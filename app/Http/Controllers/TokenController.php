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
    
            $data->type = $type;
            $data->quantity = $quantity;
            $data->price = $price;
            $data->discount = $discount;
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

        $balance = $user->balancetoken;
        $newtoken = $token->quantity;
        
        $total = $balance + $newtoken;


        $user->balancetoken = $total;
        $user->save();


        return response()->json('token added to user account');

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

          return response()->json('token added');


        }
        
    
}
    