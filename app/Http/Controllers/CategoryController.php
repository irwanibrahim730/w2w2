<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller

{
    public function addcategory(Request $request)
    {
 
        $name = $request->input('name');
        $idmaincategory = $request->input('idmaincategory');
        $sub = $request->input('sub');


        

        $data = new Category;
        $data->name = $name;
        $data->idmaincategory = $idmaincategory;
        $data->sub = $sub;

        $data->save(); 

        return response()->json('Category Added');

    }


    public function listcategory(Request $request)
    {
        
          $idmaincategory = $request->input('idmaincategory');

          $category = Category::where('idmaincategory',$idmaincategory)->get(); 
           $finalArray = array();  
        

           foreach ($category as $categories){

            if($categories->sub == null){

          $tempArray = [
              'id' => $categories->id,
              'name'=>$categories->name,
          ];

          array_push($finalArray,$tempArray);
        }
    }
          return response()->json($finalArray); 

     
}

public function listsubcategory(Request $request)
{
    $id = $request->input('id');

    $category = Category::where('sub',$id)->get(); 
     $finalArray = array();  
  

     foreach ($category as $categories){

      if($categories->sub != null ){

    $tempArray = [
        'id' => $categories->id,
        'name'=>$categories->name,
    ];

    array_push($finalArray,$tempArray);
  }
}
    return response()->json($finalArray); 


}

public function delete(Request $request)
{
    $id = $request->input('id');

    $category = Category::where('id',$id)->first();
    $category->delete();

    return response()->json('category deleted');



}


}