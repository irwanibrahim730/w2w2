<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Userenquiery;
use DB;

class UserenquieryController extends Controller

{
    
    public function index(){
        return response()->json(['status'=>'success','value'=>'engine userenquiery']);
    }

    public function store(Request $request){

        $validator = \validator::make($request->all(),
        [
            'category' => 'required',
            'email' => 'required',
        ]);

        if ($validator->fails()){
            return response()->json($validator->errors(), 422);
        } else {

            $category = $request->input('category');
            $email = $request->input('email');

            $userenquiery = new Userenquiery;

            $userenquiery->category = $category;
            $userenquiery->email = $email;

            $userenquiery->save();

            return response()->json(['status'=>'success','value'=>'success store userenquiery']);

        }

    }

    public function list(Request $request){

        $category = $request->input('category');

        if($category){
            $list = Userenquiery::where('category',$category)->get();
        } else {
            $list = Userenquiery::all();
        }

        return response()->json(['status'=>'success','value'=>$list]);

    }

    public function update(Request $request){

        $id = $request->input('id');
        $category = $request->input('category');
        $email = $request->input('email');

        $userenquiery = Userenquiery::find($id);

        if($userenquiery){

            if($category == null){
                $category = $userenquiery->category;
            }
            if($email == null){
                $email = $userenquiery->email;
            }

            $userenquiery->category = $category;
            $userenquiery->email = $email;

            $userenquiery->save();

            return response()->json(['status'=>'success','value'=>'success updated']);


        } else {
            return response()->json(['status'=>'error','value'=>'user not exist']);
        }

    }

    public function destroy(Request $request){

        $id = $request->input('id');
        $details = Userenquiery::find($id);
        $details->delete();

        return response()->json(['status'=>'success','value'=>'success deleted']);

    }

}
