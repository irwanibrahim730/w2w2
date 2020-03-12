<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Package;

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

        $validator = \validator::make($request->all(), 
            [
                'package_name' => 'required',
                'package_limit' => 'required',
                'package_duration' => 'required',
                'package_price' => 'required',
            ]);

        if ($validator->fails()){

            return response()->json($validator->errors(), 422);
        } else {
    
        $data = new Package();
        $data->package_name = $request->input('package_name');
        $data->package_limit = $request->input('package_limit');
        $data->package_duration = $request->input('package_duration');
        $data->package_price = $request->input('package_price');
        $data->premiumlist = $request->input('premiumlist');
        $data->save();


    
        return response()->json('Package success added');

        }
    
    }


    public function update(Request $request, $package_id)
    {
        $data = Package::where('package_id',$package_id)->first();
        $package_name = $request->input('package_name');
        $package_limit = $request->input('package_limit');
        $package_duration = $request->input('package_duration');
        $package_price = $request->input('package_price');
        $premiumlist = $request->input('premiumlist');
        
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

        $data->package_name = $package_name;
        $data->package_limit = $package_limit;
        $data->package_duration = $package_duration;
        $data->package_price = $package_price;
        $data->premiumlist = $premiumlist;
        $data->save();


    
        return response()->json('package updated');
    }
    
    public function destroy($package_id){
        $data = Package::where('package_id',$package_id)->first();
        $data->delete();
    
        return response()->json('packages success deleted');
    }
    

}