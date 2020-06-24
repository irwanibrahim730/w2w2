<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Category;

class DummyCategoryController extends Controller

{
    public function index(){
        return response(['status'=>'success','value'=>'dummy category engine']);
    }

    public function add(Request $request){

        $validator = \validator::make($request->all(),
        [
            'name' => 'required',
            'maincategory' => 'required',
            'parentid' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        } else {

            $name = $request->input('name');
            $maincategory = $request->input('maincategory');
            $parentid = $request->input('parentid');

            $category = new Category;
            $category->name = $name;
            $category->idmaincategory = $maincategory;
            $category->parentid = $parentid;

            $category->save();

            return response()->json(['status'=>'success','value'=>'success add new category']);

        }


    }

    public function list(Request $request){

        $maincategory = $request->input('maincategory');
        $parentarray = array();
        $finalarray = array();

        $category = Category::where('idmaincategory',$maincategory)->get();

        foreach($category as $data){

            if($data->parentid == '0'){
                array_push($parentarray,$data);
            }

        }

        foreach($parentarray as $parent){

            $childarray = array();
            $tempfinal = array();

            foreach($category as $child){
                $thirdarray = array();

                //thirdarray
                foreach($category as $third){
                    $fourtharray = array();
                    //fourtharray
                    foreach($category as $fourth){
                        if($third->id == $fourth->parentid){
                            $tempa = [
                                'fourthid' => $fourth->id,
                                'fourthname' => $fourth->name,
                            ];
                            array_push($fourtharray,$tempa);
                        }
                    }

                    $tempthird = [
                        'thirdid' => $third->id,
                        'thirdinfo' => $third->name,
                        'fourth' => $fourtharray,
                    ];

                    if($child->id == $third->parentid){
                        array_push($thirdarray,$tempthird);
                    }
                }

                $tempchild = [
                    'secondif' => $child->id,
                    'secondinfo' => $child->name,
                    'third' => $thirdarray,
                ];

                if($parent->id == $child->parentid){
                    array_push($childarray,$tempchild);
                }

            }

            $tempfinal = [
                'parentid' => $parent->id,
                'parentinfo' => $parent->name,
                'second' => $childarray,
            ];  
         
            array_push($finalarray,$tempfinal);
        }
        return response()->json(['status'=>'success','value'=>$finalarray]);

    }

    public function delete(Request $request){

        $id = $request->input('id');

        $category = Category::find($id);
        $category->delete();

        return response()->json(['status'=>'success','view'=>'success category deleted']);

    }

    public function edit(Request $request){

        $id = $request->input('id');
        $name = $request->input('name');
        $maincategory = $request->input('maincategory');
        $parentid = $request->input('parentid');

        $category = Category::find($id);

        if($category){

            if($name == null){
                $name = $category->name;
            }

            if($maincategory == null){
                $maincategory = $category->idmaincategory;
            }

            if($parentid == null){
                $parentid = $category->parentid;
            } 

            $category->name = $name;
            $category->idmaincategory = $maincategory;
            $category->parentid = $parentid;

            $category->save();

            return response()->json(['status'=>'success','value'=>'category success update']);

        } else {
            return response()->json(['status'=>'error','value'=>'sorry category not exist']);
        }

    }

}
