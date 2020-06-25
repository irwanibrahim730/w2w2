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
        $publishstatus = 'publishstatus';

        if($image){

            $extention = $image->getClientOriginalExtension();
            $imagename = rand(11111, 99999) . '.' . $extention;
            $destinationPath = 'image';
            $image->move($destinationPath, $imagename);

        } else {

            $imagename = null;
        }


        $data = new Banner;
        $data->title = $title;
        $data->image = $imagename;
        $data->url = $url;
        $data->publishstatus = $publishstatus;
        $data->save(); 

        return response()->json(['status'=>'success','value'=>'banner submitted']);

    }


    public function list()
    {
    
           $banner = Banner::orderBy('created_at','DESC')->get();
           $finalArray = array();  

           foreach ($banner as $banners){
            
            $url = 'https://codeviable.com/w2w2/public/image';
            $imagename = $banners->image;
            $public =  $url .'/'. $imagename;

            $tempArray = [
              'id' => $banners->id,
              'title' =>$banners->title,
              'image' => $public,
              'url'=>$banners->url,
              'publishstatus' => $banners->publishstatus,
              'created_at' => $banners->created_at->format('d M Y - H:i:s'),
              'updated_at' => $banners->updated_at->format('d M Y - H:i:s'),
   
          ];

           array_push($finalArray,$tempArray);
         }

         return response()->json(['status'=>'success','value'=>$finalArray]);
    

        }


        public function status(Request $request){

            $publishstatus = $request->input('publishstatus');
    
            $datas = Banner::where('publishstatus',$publishstatus)
                        ->orderBy('created_at', 'DESC')
                        ->get();
            $statusArray = array();
          
    
    
    
                foreach($datas as $data ) {
    
                    $url = 'https://codeviable.com/w2w2/public/image';
                    $imagename = $data->image;
                    $public =  $url .'/'. $imagename;
                
                    $tempArray = [
                        'id' => $data->id,
                        'title' =>$data->title,
                        'image' => $public,
                        'url'=>$data->url,
                        'publishstatus' => $data->publishstatus,
                        'created_at' => $data->created_at->format('d M Y - H:i:s'),
                        'updated_at' => $data->updated_at->format('d M Y - H:i:s'),
             
                    ];
                     array_push($statusArray,$tempArray);
            }
                return response()->json(['status'=>'success','value'=>$statusArray]);
    
             }
      
    


public function delete(Request $request)
{
    $id = $request->input('id');

    $banners = Banner::where('id',$id)->first();
    $banners->delete();

    return response()->json(['status'=>'status','value'=>'banner deleted']);

}


   public function detail(Request $request)
   {

     $id = $request->input('id');

     $banner = Banner::where('id',$id)->get();
     $finalArray = array();  

     foreach ($banner as $banners){
      
      $url = 'https://codeviable.com/w2w2/public/image';
      $imagename = $banners->image;
      $public =  $url .'/'. $imagename;

      $tempArray = [
        'id' => $banners->id,
        'title' =>$banners->title,
        'image' => $public,
        'url'=>$banners->url,
        'publishstatus' => $banners->publishstatus,
        'created_at' => $banners->created_at->format('d M Y - H:i:s'),
        'updated_at' => $banners->updated_at->format('d M Y - H:i:s'),

    ];

     array_push($finalArray,$tempArray);
   }

    return response()->json(['status'=>'success','value'=>$finalArray]);


   }


   public function edit(Request $request)
   {
    $id = $request->input('id');

    
    $title = $request->input('title');
    $image = $request->file('image');
    $url = $request->input('url');
    $publishstatus = $request->input('publishstatus');

    $data = Banner::where('id',$id)->first();

    if ($title == null) {
        $title = $data->title;
    }
    if($request->hasfile('image')){
        $extention = $image->getClientOriginalExtension();
        $imagename = rand(11111, 99999) . '.' . $extention;
        $destinationPath = 'image';
        $image->move($destinationPath, $imagename);
        }
        
        
            else if ($image == null) {
                $imagename = $data->image;
        }

        

    if ($url == null) {
        $url = $data->url;
     }

    if ($publishstatus == null) {
      $publishstatus = $data->publishstatus;
     }

     $data->title = $title;
     $data->image = $imagename;
     $data->url = $url;
     $data->publishstatus = $publishstatus;
     $data->save();

     return response()->json(['status'=>'success','value'=>'news successfull updated']);

   }


}