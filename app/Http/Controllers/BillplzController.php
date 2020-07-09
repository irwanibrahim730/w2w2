<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use Billplz\Laravel\Billplz;

class BillplzController extends Controller

{
    
    public function index(){
        return response()->json(['status'=>'success','value'=>'Billplz Engine']);
    }

    public function redirect(Request $request){
        $a = $request->all();

        dd($a);

        //getbillid
        //query table purchase
        //get userid
        //get bill

         // $user->balancetoken = $total;

                // $history = new History;
                // $history->user_id = $user_id;
                // $history->type = 'token';
                // $history->name = $quantitytoken;
                // $history->price = $netprice;
                // $user->save();
                // $history->save();

                

                // return response()->json(['status'=>'success','value'=>'token added to user account']);
    }

}
