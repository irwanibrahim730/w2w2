<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Enquiry;

class EnquiryController extends Controller

{
    public function store(Request $request)
    {
        

        $name = $request->input('name');
        $email = $request->input('email');
        $category = $request->input('category');
        $description = $request->input('description');

        $data = new Enquiry;
        $data->name = $name;
        $data->email = $email;
        $data->category = $category;
        $data->description = $description;
        $data->save(); 

        return response()->json('Enquiry Submitted');

    }


    public function list()
    {
        
           $enquiry = Enquiry::all();
           $finalArray = array();  

           foreach ($enquiry as $enquiries){
          
            $tempArray = [
              'id' => $enquiries->id,
              'name' => $enquiries->name,
              'email' => $enquiries->email,
              'category'=> $enquiries->category,
              'description'=> $enquiries->description,
              'created_at' => $enquiries->created_at->format('d M Y - H:i:s'),
              'updated_at' => $enquiries->updated_at->format('d M Y - H:i:s'),
          ];

           array_push($finalArray,$tempArray);
         }

         return response()->json($finalArray); 
    

        }

     public function detail(Request $request)
     {
         $id = $request->input('id');

         $enquiry = Enquiry::where('id',$id)->first();

         return response()->json($enquiry);
     }
      
    


public function delete(Request $request)
{
    $id = $request->input('id');

    $comments = Enquiry::where('id',$id)->first();
    $comments->delete();

    return response()->json('enquiry deleted');



}


}