<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Purchase;
use App\User;


class ReceiptController extends Controller{

    public function index(){
        return response()->json(['status'=>'success','view'=>'receipt engine']);
    }

    public function getreceipt(Request $request){

        $validator = \validator::make($request->all(),
        [
            'billid' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        } else {
            $billid = $request->input('billid');

            $purchase = Purchase::where('billid',$billid)->first();

            if($purchase){

                $userdetails = User::where('user_id',$purchase->userid)->first();
                if($userdetails->user_type == 'company'){
                    $name = $userdetails->companyname;
                } else {
                    $name = $userdetails->user_fname . ' ' . $userdetails->user_lname;
                }

                $tempreceipt = [
                    'billid' => $purchase->billid,
                    'date' => $purchase->created_at,
                    'token' => $purchase->token,
                    'amount' => $purchase->price,
                    'name' => $name,
                    'email' => $userdetails->user_email,
                    
                ];

                return response()->json(['status'=>'success','value'=>$tempreceipt]);

            } else{
                return response()->json(['status'=>'failed','value'=>'bill not exist']);
            }
        } 

    }

}

