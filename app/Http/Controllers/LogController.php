<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Log;

class LogController extends Controller {
    
      public function index(){
            return response()->json(['status'=>'success','value'=>'log admin engine']);
      }

      public function list(Request $request){

            $userid = $request->input('userid');

            $log = Log::where('userid',$userid)->orderBy('created_at','DESC')->get();

            return response()->json(['status'=>'success','value'=>$log]);

      }

}