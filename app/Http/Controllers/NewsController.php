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

                'title' => $data->news_title,
                'description' => $data->news_desc,
                'photo' => $dirfile,


			];
        
			array_push($finalArray, $tempArray);

        }
        
            return response()->json($finalArray); 
    }
    public function show($news_id){
        $data = News::find($news_id);

        if ($data == null) {

            return response()->json('data not exist');
        
         } else {
      
            $public = rtrim(app()->basePath('public/image'), '/');
            $imagename = $data->news_photo;
            $dirfile = $public.'/'.$imagename;

            $tempArray = [
            'news_title' => $data->news_title,
            'news_desc' => $data->news_desc,
            'news_photo' => $dirfile,
            ];

            return response()->json($tempArray);

         }

            


    }

    public function store (Request $request){

        $validator = \validator::make($request->all(),
        [
            'news_title' => 'required',
            'news_desc' => 'required',
            'news_photo' => 'mimes:jpg,jpeg,png|required',
        ]);


        if ($validator->fails()) {

			return response()->json($validator->errors(), 422);

		}

        else{

            $news_title = $request->input('news_title');
			$news_desc = $request->input('news_desc');
			$news_photo = $request->file('news_photo');

        }

        $extention = $news_photo->getClientOriginalExtension();
        $imagename = rand(11111, 99999) . '.' . $extention;
        $destinationPath = 'image';

        $news_photo->move($destinationPath, $imagename);


        $data = new News;
        $data->news_title = $news_title;
        $data->news_desc = $news_desc;
        $data->news_photo = $imagename;
        $data->save();
        

        return response()->json("News successfull added");

         }

        public function update(Request $request, $news_id)
        {

            $data = News::where('news_id',$news_id)->first();
            $news_title = $request->input('news_title');
            $news_desc = $request->input('news_desc');
            $news_photo = $request->file('news_photo');

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


        $data->news_title = $news_title;
		$data->news_desc = $news_desc;
		$data->news_photo = $imagename;

		$data->save();

		return response()->json('news  successfull updated');
    }
    
        
        public function destroy($news_id){
            $data = News::where('news_id',$news_id)->first();
            $data->delete();
        
            return response()->json('News deleted');
        }



    }



