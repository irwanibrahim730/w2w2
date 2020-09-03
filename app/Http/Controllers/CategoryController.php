<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller

{

        public function index(){

          return response()->json(['status'=>'success','value'=>'category index']);

        }

        public function store(Request $request){

          $name = $request->input('name');
          $maincategory = $request->input('maincategory');
          $level = $request->input('level');
          $levelone = $request->input('levelone');
          $leveltwo = $request->input('leveltwo');
          $levelthree = $request->input('levelthree');
          $levelfour = $request->input('levelfour');
          $levelfive = $request->input('levelfive');

          $category = new Category;
          $category->name = $name;
          $category->maincategory = $maincategory;
          $category->level = $level;
          $category->levelone = $levelone;
          $category->leveltwo = $leveltwo;
          $category->levelthree = $levelthree;
          $category->levelfour = $levelfour;
          $category->levelfive = $levelfive;

          $category->save();

          return response()->json(['status'=>'succees', 'value'=>'success add category']);

        }

        public function update(Request $request){

          $id = $request->input('id');
          $name = $request->input('name');

          $category = Category::find($id);

          if($category){

            $exist = Category::where('name',$name)->first();

            if($exist){
              
              return response()->json(['status'=>'failed','value'=>'sorry name is already exist']);

            } else{
              
              if($name == null){
                $name = $category->name;
              }

              $category->name = $name;

              $category->save();

              return response()->json(['status'=>'success','value'=>'success update name category']);
              
            }
          
          } else {
            return response()->json(['status'=>'failed','value'=>'category not exist']);
          }

        }

      public function list(Request $request){

        $finalarray = array();

        $maincategory = $request->input('maincategory');
        $parentid = $request->input('parentid');
        

        if($parentid == null && $maincategory != null){

            $list = Category::where('maincategory',$maincategory)->where('level','one')->get();

        } elseif($parentid == null && $maincategory == null){

            $list = Category::where('level','one')->get();

        }
         else {

            $templist = Category::where('maincategory',$maincategory)->where('id',$parentid)->first();

            //getinfotemplist
            $templevel = $templist->level;

            if($templevel == 'one'){
              $list = Category::where('maincategory',$maincategory)->where('level','two')->where('levelone',$parentid)->get();
            } elseif($templevel == 'two'){
              $list = Category::where('maincategory',$maincategory)->where('level','three')->where('leveltwo',$parentid)->get();
            } elseif($templevel == 'three'){
              $list = Category::where('maincategory',$maincategory)->where('level','four')->where('levelthree',$parentid)->get();
            } elseif($templevel == 'four'){
              $list = Category::where('maincategory',$maincategory)->where('level','five')->where('levelfour',$parentid)->get();
            }

           

        }     
        

        if($list){

          
           foreach($list as $data){
            $status = 'notavailable';
              //level
              $level = $data->level;

              //query bawah dia
              if($level == 'one'){
                $exist = Category::where('level','two')->where('levelone',$data->id)->first();
              } elseif($level == 'two'){
                $exist = Category::where('level','three')->where('leveltwo',$data->id)->first();
              } elseif($level == 'three'){
                $exist = Category::where('level','four')->where('levelthree',$data->id)->first();
              } elseif($level == 'four'){
                $exist = Category::where('level','five')->where('levelfour',$data->id)->first();
              } else {
                $exist = null;
              }
         
              if($exist != null){
                $status = 'available';
              }
            
              
              $temparray = [

              'id' => $data->id,
              'name' => $data->name,
              'maincategory' => $data->maincategory,
              'level' => $data->level,
              'levelone' => $data->levelone,
              'leveltwo' => $data->leveltwo,
              'levelthree' => $data->levelthree,
              'levelfour' => $data->levelfour,
              'levelfive' => $data->levelfive,
              'status' => $status,

              ];
            
              array_push($finalarray,$temparray);

            }

          return response()->json(['status'=>'success','value'=>$finalarray]);

        } else {
          return response()->json(['status'=>'failed','value'=>'invalid main category']);
        }


      }

      public function details(Request $request){

        $id = $request->input('id');

        $details = Category::find($id);

        if($details){
          return response()->json(['status'=>'success','value'=>$details]);
        } else {
          return response()->json(['status'=>'failed','value'=>'category not exist']);
        }
        

      }

      public function destroy(Request $request){

        $id = $request->input('id');

        $details = Category::find($id);

        if($details){

          $list = Category::where('id',$id)
                          ->orWhere('levelone',$id)
                          ->orWhere('leveltwo',$id)
                          ->orWhere('levelthree',$id)
                          ->orWhere('levelfour',$id)
                          ->orWhere('levelfive',$id)
                ->delete();
          return response()->json(['status'=>'success','value'=>'success delete']);

        } else {
          return response()->json(['status'=>'failed','value'=>'category not exist']);
        }

      }

      public function level(Request $request){

        $level = $request->input('level');
        $maincategory = $request->input('maincategory');
        $parentid = $request->input('parentid');

        if($parentid == null){

          $list = Category::where('maincategory',$maincategory)->where('level',$level)->get();

        } else {

          if($level == 'two'){
            $list = Category::where('maincategory',$maincategory)->where('levelone',$parentid)->where('level','two')->get();
          } elseif($level == 'three'){
            $list = Category::where('maincategory',$maincategory)->where('leveltwo',$parentid)->where('level','three')->get();
          } elseif($level == 'four'){
            $list = Category::where('maincategory',$maincategory)->where('levelthree',$parentid)->where('level','four')->get();
          } elseif($level == 'five'){
            $list = Category::where('maincategory',$maincategory)->where('levelfour', $parentid)->where('level','five')->get();
          }
          
        }

       return response()->json(['status'=>'success','value'=>$list]);

      }



//     public function addcategory(Request $request)
//     {
 
//         $name = $request->input('name');
//         $idmaincategory = $request->input('idmaincategory');
//         $sub = $request->input('sub');
//         $publishstatus = $request->input('publishstatus');
//         $keyword = $request->input('keyword');
//         $description = $request->input('description');
//         $title = $request->input('title');


        

//         $data = new Category;
//         $data->name = $name;
//         $data->idmaincategory = $idmaincategory;
//         $data->sub = $sub;
//         $data->publishstatus = $publishstatus;
//         $data->keyword = $keyword;
//         $data->description = $description;
//         $data->title = $title;
//         $data->save(); 

//         return response()->json('Category Added');

//     }


//     public function listcategory(Request $request)
//     {
        
//           $idmaincategory = $request->input('idmaincategory');

//           $category = Category::where('idmaincategory',$idmaincategory)->get(); 
//            $finalArray = array();  
        

//            foreach ($category as $categories){

//             if($categories->sub == null){

//           $tempArray = [
//               'id' => $categories->id,
//               'name'=>$categories->name,
//               'publishstatus' => $categories->publishstatus,
//               'keyword' => $categories->keyword,
//               'description' => $categories->description,
//               'title' => $categories->title,
//           ];

//           array_push($finalArray,$tempArray);
//         }
//     }
//           return response()->json($finalArray); 

     
// }

// public function listsubcategory(Request $request)
// {
//     $id = $request->input('id');

//     $category = Category::where('sub',$id)->get(); 
//      $finalArray = array();  
  

//      foreach ($category as $categories){

//       if($categories->sub != null ){

//     $tempArray = [
//         'id' => $categories->id,
//         'name'=>$categories->name,
//         'publishstatus' =>$categories->publishstatus,
//         'keyword' => $categories->keyword,
//         'description' => $categories->description,
//         'title' => $categories->title,
//     ];

//     array_push($finalArray,$tempArray);
//   }
// }
//     return response()->json($finalArray); 


// }


//   public function mainstatus(Request $request)
//   {
//     {
        
//         $idmaincategory = $request->input('idmaincategory');
//         $publishstatus = $request->input('publishstatus');

//         $category = Category::where('idmaincategory',$idmaincategory)->get(); 
//          $finalArray = array();  
      

//          foreach ($category as $categories){
//              if($categories->publishstatus == $publishstatus){

//           if($categories->sub == null){

//         $tempArray = [
//             'id' => $categories->id,
//             'name'=>$categories->name,
//             'publishstatus' => $categories->publishstatus,
//             'keyword' => $categories->keyword,
//             'description' => $categories->description,
//             'title' => $categories->title,
//         ];

//         array_push($finalArray,$tempArray);
//       }
//     }
//   }
//         return response()->json($finalArray); 

   
// }

//   }



//   public function substatus(Request $request)

//     {
//         $id = $request->input('id');
//         $publishstatus = $request->input('publishstatus');
    
//         $category = Category::where('sub',$id)->get(); 
//          $finalArray = array();  
      
    
//          foreach ($category as $categories){
//            if($categories->publishstatus == $publishstatus){
//           if($categories->sub != null ){
    
//         $tempArray = [
//             'id' => $categories->id,
//             'name'=>$categories->name,
//             'publishstatus' =>$categories->publishstatus,
//         ];
    
//         array_push($finalArray,$tempArray);
//       }
//     }
//     }
//         return response()->json($finalArray); 
    
    
//     }
  





// public function delete(Request $request)
// {
//     $id = $request->input('id');

//     $category = Category::where('id',$id)->first();
//     $category->delete();

//     return response()->json('category deleted');



// }

// public function edit(Request $request)
// {

//     $id = $request->input('id');

//     $category = Category::where('id',$id)->first();

//     $idmaincategory = $request->input('idmaincategory');
//     $name = $request->input('name');
//     $sub = $request->input('sub');
//     $publishstatus = $request->input('publishstatus');
//     $keyword = $request->input('keyword');
//     $description = $request->input('description');
//     $title = $request->input('title');

//     if($idmaincategory == null){
//       $idmaincategory = $category->idmaincategory;
//     }

//     if($name == null)
//     {
//       $name = $category->name;
//     }

//     if($sub == null)
//     {
//       $sub = $category->sub;
//     }

//     if($publishstatus == null)
//     {
//       $publishstatus = $category->publishstatus;
//     }

//     if($keyword == null){
//       $keyword = $category->keyword;
//     }

//     if($description == null){
//       $description = $category->description;
//     }

//     if($title == null){
//       $title = $category->title;
//     }

//     $category->idmaincategory = $idmaincategory;
//     $category->name = $name;
//     $category->sub = $sub;
//     $category->publishstatus = $publishstatus;
//     $data->keyword = $keyword;
//     $data->description = $description;
//     $data->title = $title;
//     $category->save();
    
//    return response()->json('category updated');


// }


//     public function detail( Request $request)
//     {
  
//       $id = $request->input('id');

//       $category = Category::where('id',$id)->first();

//       return response()->json($category);



//     } 


}