<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Log;

class LogController extends Controller

{
      public function list()
{
      $log = Log::all();
      return response()->json($log);
}
    
      public function detail(Request $request)

      {
          $id = $request->input('id');
          $log = Log::where('id',$id)->first();

          return response()->json($log);
      }

      public function type(Request $request)
      {
          $type = $request->input('type');
          $log = Log::where('type',$type)->get();

          return response()->json($log);
      }

      


      public function delete(Request $request)
      {
         $id = $request->input('id');
         $log = Log::where('id',$id)->first();
         $log->delete();

      }


   


}