<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Package;
use App\Userpack;

class PackageController extends Controller

{
    public function index(){
        $data = Package::all();

       return response()->json($data);

    }

    public function show(Request $request){

        $package_id = $request->input('package_id');
        $data = Package::where('package_id',$package_id)->get();
        return response()->json($data);
    }

    public function store (Request $request){

 
        $data = new Package();
        $data->package_name = $request->input('package_name');
        $data->package_limit = $request->input('package_limit');
        $data->package_duration = $request->input('package_duration');
        $data->package_price = $request->input('package_price');
        $data->premiumlist = $request->input('premiumlist');
        $data->description = $request->input('description');
        $data->save();


    
        return response()->json('Package success added');

        }
    
    


    public function update(Request $request)
    {

        $package_id = $request->input('package_id');

        $data = Package::where('package_id',$package_id)->first();
        $package_name = $request->input('package_name');
        $package_limit = $request->input('package_limit');
        $package_duration = $request->input('package_duration');
        $package_price = $request->input('package_price');
        $premiumlist = $request->input('premiumlist');
        $description = $request->input('description');
        
        if($package_name == null){
           
            $package_name = $data->package_name;

        }

        if($package_limit == null){

            $package_limit = $data->package_limit;
        }

        if($package_duration == null){

            $package_duration = $data->package_duration;
        }

        if($package_price == null){

            $package_price = $data->package_price;
        }

        if($premiumlist == null){

            $premiumlist = $data->premiumlist;
        }

        if($description == null){

            $description = $data->description;
        }

        $data->package_name = $package_name;
        $data->package_limit = $package_limit;
        $data->package_duration = $package_duration;
        $data->package_price = $package_price;
        $data->premiumlist = $premiumlist;
        $data->description = $description;
        $data->save();


    
        return response()->json('package updated');
    }
    
    public function destroy(Request $request){

        $package_id = $request->input('package_id');

        $data = Package::where('package_id',$package_id)->first();
        $data->delete();
    
        return response()->json('packages success deleted');
    }

    public function paiduser(Request $request)

    {
  
        $user_id = $request->input('user_id');
        $userpack = Userpack::where('user_id',$user_id)->get();
        $packArray = array();

          foreach($userpack as $userpackage){
            
            $package_id = $userpackage->package_id;
            $package = Package::where('package_id',$package_id)->get();
              

               foreach($package as $packages){
                 
                $tempArray = [
                    'id' => $userpackage->id,
                    'user_id' => $userpackage->user_id,
                    'package_id' => $userpackage->package_id,
                    'package_name' => $packages->package_name,
                    'limit' => $userpackage->limit, 
                ];

                 array_push($packArray,$tempArray);

            }

          }

        return response()->json($packArray);


    }
    

    public function paidall(Request $request)

    {
  
        $userpack = Userpack::all();
        $packArray = array();

          foreach($userpack as $userpackage){
            
            $package_id = $userpackage->package_id;
            $package = Package::where('package_id',$package_id)->get();
              

               foreach($package as $packages){
                 
                $tempArray = [
                    'id' => $userpackage->id,
                    'user_id' => $userpackage->user_id,
                    'package_id' => $userpackage->package_id,
                    'package_name' => $packages->package_name,
                    'limit' => $userpackage->limit, 
                ];

                 array_push($packArray,$tempArray);

            }

          }

        return response()->json($packArray);


    }


    public function deletepaid(Request $request)
    {
 
          $id = $request->input('id');

          $userpack = Userpack::where('id',$id)->first();
          $userpack->delete();

          return response ('paid package deleted');



    }

}