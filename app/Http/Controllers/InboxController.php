<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Inbox;
use App\User;

class InboxController extends Controller

{
    public function store (Request $request){

 
        $data = new Inbox();
        $data->type = $request->input('email');
        $data->quantity = $request->input('message');
        $data->save();


    
        return response()->json('Inbox added');

        }

        public function index(){
            $data = Inbox::all();
    
           return response()->json($data);
    
        }
    
        public function show(Request $request){
    
            $id = $request->input('id');
            $data = Inbox::where('id',$id)->get();
            return response()->json($data);
        }


        public function destroy(Request $request){

            $id = $request->input('id');
    
            $data = Inbox::where('id',$id)->first();
            $data->delete();
        
            return response()->json('Inbox deleted');
        }

        public function edit(Request $request)
        {

            $id = $request->input('id');
    
            $data = Inbox::where('id',$id)->first();

            $email = $request->input('email');
            $message = $request->input('message');

            
            if($email == null){
               
                $email = $data->email;
    
            }
    
            if($message == null){
    
                $message = $data->message;
            }

    
    
    
            $data->email = $email;
            $data->message = $message;
            $data->save();
    
    
        
            return response()->json('inbox updated');
        }



        
    
}
    