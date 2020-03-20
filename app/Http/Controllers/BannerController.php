<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Banner;

class BannerController extends Controller

{
    public function store(Request $request)
    {
        

        $title = $request->input('title');
        $image = $request->file('image');
        $url = $request->input('url');


        $extention = $image->getClientOriginalExtension();
        $imagename = rand(11111, 99999) . '.' . $extention;
        $destinationPath = 'image';
        $image->move($destinationPath, $imagename);


        $data = new Banner;
        $data->title = $title;
        $data->image = $imagename;
        $data->url = $url;
        $data->save(); 

        return response()->json('Banner Submitted');

    }


    public function list()
    {
        
           $banner = Banner::all();
           $finalArray = array();  

           foreach ($banner as $banners){
            
            $public = rtrim(app()->basePath('public/image'), '/');
            $imagepath = $public.'/'.$banners->image;

            $tempArray = [
              'id' => $banners->id,
              'title' =>$banners->title,
              'image' => $imagepath,
              'url'=>$banners->url,
   
          ];

           array_push($finalArray,$tempArray);
         }

         return response()->json($finalArray); 
    

        }
      
    


public function delete(Request $request)
{
    $id = $request->input('id');

    $banners = Banner::where('id',$id)->first();
    $banners->delete();

    return response()->json('banner deleted');



}


}