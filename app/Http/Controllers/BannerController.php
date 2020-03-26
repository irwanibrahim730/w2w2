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
        $publishstatus = $request->input('publishstatus');


        $extention = $image->getClientOriginalExtension();
        $imagename = rand(11111, 99999) . '.' . $extention;
        $destinationPath = 'image';
        $image->move($destinationPath, $imagename);


        $data = new Banner;
        $data->title = $title;
        $data->image = $imagename;
        $data->url = $url;
        $data->publishstatus = $publishstatus;
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
              'publishstatus' => $banners->publishstatus,
              'created_at' => $banners->created_at->format('d M Y - H:i:s'),
              'updated_at' => $banners->updated_at->format('d M Y - H:i:s'),
   
          ];

           array_push($finalArray,$tempArray);
         }

         return response()->json($finalArray); 
    

        }


        public function status(Request $request){

            $publishstatus = $request->input('publishstatus');
    
            $datas = Banner::where('publishstatus',$publishstatus)->get();
            $statusArray = array();
          
    
    
    
                foreach($datas as $data ) {
    
                    $public = rtrim(app()->basePath('public/image'), '/');
                    $imagename = $data->news_photo;
                    $dirfile = $public.'/'.$imagename;
                
                    $tempArray = [
                        'id' => $data->id,
                        'title' =>$data->title,
                        'image' => $dirfile,
                        'url'=>$data->url,
                        'publishstatus' => $data->publishstatus,
                        'created_at' => $data->created_at->format('d M Y - H:i:s'),
                        'updated_at' => $data->updated_at->format('d M Y - H:i:s'),
             
                    ];
                     array_push($statusArray,$tempArray);
            }
    
                return response()->json($statusArray);
    
             }
      
    


public function delete(Request $request)
{
    $id = $request->input('id');

    $banners = Banner::where('id',$id)->first();
    $banners->delete();

    return response()->json('banner deleted');



}


}