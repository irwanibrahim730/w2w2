<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\News;

class NewsController extends Controller

{
    public function index(){

        $finalArray = array();
        $news = News::all();
        

        foreach ($news as $data) {

            $public = rtrim(app()->basePath('public/image'), '/');
            $imagename = $data->news_photo;
            $dirfile = $public.'/'. $imagename;
   
     

			$tempArray = [

                'news_id' => $data->news_id,
                'news_title' => $data->news_title,
                'news_desc' => $data->news_desc,
                'shortdesc' => $data->shortdesc,
                'news_photo' => $dirfile,
                'published_at' =>$data->published_at,
                'publishstatus' => $data->publishstatus,
                'created_at' => $data->created_at->format('d M Y - H:i:s'),
                'updated_at' => $data->updated_at->format('d M Y - H:i:s'),


			];
        
			array_push($finalArray, $tempArray);

        }
        
            return response()->json($finalArray); 
    }
    
    public function show(Request $request){

        $news_id = $request->input('news_id');

        $data = News::where('news_id',$news_id)->first();

        if ($data == null) {

            return response()->json('data not exist');
        
         } else {
      
            $public = rtrim(app()->basePath('public/image'), '/');
            $imagename = $data->news_photo;
            $dirfile = $public.'/'.$imagename;

            $tempArray = [
                'news_id' => $data->news_id,
                'news_title' => $data->news_title,
                'news_desc' => $data->news_desc,
                'shortdesc' => $data->shortdesc,
                'news_photo' => $dirfile,
                'published_at' =>$data->published_at,
                'publishstatus' => $data->publishstatus,
                'created_at' => $data->created_at->format('d M Y - H:i:s'),
                'updated_at' => $data->updated_at->format('d M Y - H:i:s'),
            ];

            return response()->json($tempArray);

         }

            


    }

    public function status(Request $request){

        $publishstatus = $request->input('publishstatus');

        $datas = News::where('publishstatus',$publishstatus)->get();
        $statusArray = array();
      



            foreach($datas as $data ) {

                $public = rtrim(app()->basePath('public/image'), '/');
                $imagename = $data->news_photo;
                $dirfile = $public.'/'.$imagename;
            
                $tempArray = [
                'news_id' => $data->news_id,
                'news_title' => $data->news_title,
                'news_desc' => $data->news_desc,
                'shortdesc' => $data->shortdesc,
                'news_photo' => $dirfile,
                'published_at' =>$data->published_at,
                'publishstatus' => $data->publishstatus,
                'created_at' => $data->created_at->format('d M Y - H:i:s'),
                'updated_at' => $data->updated_at->format('d M Y - H:i:s'),
            ];
                 array_push($statusArray,$tempArray);
        }

            return response()->json($statusArray);

         }

            


    

    public function store (Request $request){


            $news_title = $request->input('news_title');
            $news_desc = $request->input('news_desc');
            $shortdesc = $request->input('shortdesc');
            $news_photo = $request->file('news_photo');
            $published_at = $request->input('published_at');
            $publishstatus = $request->input('publishstatus');



          $extention = $news_photo->getClientOriginalExtension();
          $imagename = rand(11111, 99999) . '.' . $extention;
          $destinationPath = 'image';
          $news_photo->move($destinationPath, $imagename);


        $data = new News;
        $data->news_title = $news_title;
        $data->news_desc = $news_desc;
        $data->shortdesc = $shortdesc;
        $data->news_photo = $imagename;
        $data->published_at = $published_at;
        $data->publishstatus = $publishstatus;
        $data->save();
        

        return response()->json("News successfull added");

         }

        public function update(Request $request)
        {
            $news_id = $request->input('news_id');

            $data = News::where('news_id',$news_id)->first();
            $news_title = $request->input('news_title');
            $news_desc = $request->input('news_desc');
            $news_photo = $request->file('news_photo');
            $published_at = $request->input('published_at');
            $shortdesc = $request->input('shortdesc');
            $publishstatus = $request->input('publishstatus');

            $extention = $news_photo->getClientOriginalExtension();
            $imagename = rand(11111, 99999) . '.' . $extention;
            $destinationPath = 'image';

            $news_photo->move($destinationPath, $imagename);

            if ($news_title == null) {
                $news_title = $data->news_title;
            }
            if ($news_desc == null) {
                $news_desc = $data->news_desc;
            }
            if ($news_photo == null) {
                $news_photo = $data->news_photo;
             }

            if ($published_at == null) {
              $published_at = $data->published_at;
             }

             if ($shortdesc == null) {
                $shortdesc = $data->shortdesc;
               }

            if ($publishstatus == null) {
                $publishstatus = $data->publishstatus;
               }






        $data->news_title = $news_title;
		$data->news_desc = $news_desc;
        $data->news_photo = $imagename;
        $data->published_at = $published_at;
        $data->shortdesc = $shortdesc;
        $data->publishstatus = $publishstatus;
		$data->save();

		return response()->json('news  successfull updated');
    }
    
        
        public function destroy(Request $request){
            
            $news_id = $request->input('news_id');
            $data = News::where('news_id',$news_id)->first();
            $data->delete();
        
            return response()->json('News deleted');
        }



    }



