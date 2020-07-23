<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Inbox;
use App\Product;
use App\User;

class InboxController extends Controller

{
    public function store (Request $request){

 
        $data = new Inbox();
        $data->email = $request->input('email');
        $data->message = $request->input('message');
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

        public function listinbox(Request $request){

            $arrayinbox = array();
            $tempproduct = Product::where('product_status','processed')
                        ->orderBy('created_at','DESC')
                        ->get();
            
            foreach($tempproduct as $data){

                $temparray = [

                    'id' => $data->product_id,
                    'product_name' => $data->product_name,
                    'product_category' => $data->product_category,
                    'created_at' => $data->created_at,

                ];

                array_push($arrayinbox,$temparray);

            }

            $countarray = count($arrayinbox);
            $finalarray = array();

            $finalarray = [
                'count' => $countarray,
                'inbox' => $arrayinbox,
            ];

            return response()->json(['status'=>'success','value'=>$finalarray]);

        }        
    
}
    