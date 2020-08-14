<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Tagging;

class TaggingController extends Controller

{
   public function index(){
       return response()->json(['status'=>'success','value'=>'tagging index']);
   }

   public function store(Request $request){

        $name = $request->input('name');

        $tagging = new Tagging;
        $tagging->name = $name;

        $tagging->save();

        return response()->json(['status'=>'success','value'=>'success add tagging']);

   }

   public function all(){

        $tagging = Tagging::all();

        return response()->json(['status'=>'success',' value'=>$tagging]);

   }

   public function edit(Request $request){

        $id = $request->input('id');
        $name = $request->input('name');

        $tagging = Tagging::find($id);

        $tagging->name = $name;

        $tagging->save();

        return response()->json(['status'=>'success','value'=>'success update']);

   }

   public function destroy(Request $request){

        $id = $request->input('id');
        $tag = Tagging::where('id',$id)->first();
        $tag->delete();

        return response()->json(['status'=>'success','value'=>'success deleted']);


   }

}
