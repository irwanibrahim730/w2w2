<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Notification;
use App\User;

class NotificationController extends Controller

{
    public function list()
    {
        $notify = Notification::all();

        return response()->json($notify);
    }

    public function delete(Request $request)
    {

        $id = $request->input('id');

        $notify = Notification::where('id',$id)->first();
        $notify->delete();
   
         return response()->json('notification deleted');
    }
        
    
}
    