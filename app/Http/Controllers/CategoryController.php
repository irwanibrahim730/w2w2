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
        $publishstatus = $request->input('publishstatus');


        

        $data = new Category;
        $data->name = $name;
        $data->idmaincategory = $idmaincategory;
        $data->sub = $sub;
        $data->publishstatus = $publishstatus;

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
              'publishstatus' => $categories->publishstatus,
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
        'publishstatus' =>$categories->publishstatus,
    ];

    array_push($finalArray,$tempArray);
  }
}
    return response()->json($finalArray); 


}


  public function mainstatus(Request $request)
  {
    {
        
        $idmaincategory = $request->input('idmaincategory');
        $publishstatus = $request->input('publishstatus');

        $category = Category::where('idmaincategory',$idmaincategory)->get(); 
         $finalArray = array();  
      

         foreach ($category as $categories){
             if($categories->publishstatus == $publishstatus){

          if($categories->sub == null){

        $tempArray = [
            'id' => $categories->id,
            'name'=>$categories->name,
            'publishstatus' => $categories->publishstatus,
        ];

        array_push($finalArray,$tempArray);
      }
    }
  }
        return response()->json($finalArray); 

   
}

  }



  public function substatus(Request $request)

    {
        $id = $request->input('id');
        $publishstatus = $request->input('publishstatus');
    
        $category = Category::where('sub',$id)->get(); 
         $finalArray = array();  
      
    
         foreach ($category as $categories){
           if($categories->publishstatus == $publishstatus){
          if($categories->sub != null ){
    
        $tempArray = [
            'id' => $categories->id,
            'name'=>$categories->name,
            'publishstatus' =>$categories->publishstatus,
        ];
    
        array_push($finalArray,$tempArray);
      }
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

public function edit(Request $request)
{

    $id = $request->input('id');

    $category = Category::where('id',$id)->first();

    $idmaincategory = $request->input('idmaincategory');
    $name = $request->input('name');
    $sub = $request->input('sub');
    $publishstatus = $request->input('publishstatus');

    if($idmaincategory == null){
      $idmaincategory = $category->idmaincategory;
    }

    if($name == null)
    {
      $name = $category->name;
    }

    if($sub == null)
    {
      $sub = $category->sub;
    }

    if($publishstatus == null)
    {
      $publishstatus = $category->publishstatus;
    }

    $category->idmaincategory = $idmaincategory;
    $category->name = $name;
    $category->sub = $sub;
    $category->publishstatus = $publishstatus;
    $category->save();
    
   return response()->json('category updated');


}


}