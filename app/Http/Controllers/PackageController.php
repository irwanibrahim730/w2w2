<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Package;
use App\Userpack;
use DB;

class PackageController extends Controller

{
    public function index(){

        $packageArray = array();

        $package = Package::orderBy('arangenum','ASC')->get();

        foreach($package as $data){

            $tempArray = [
                'package_id' => $data->package_id,
                'package_name' => $data->package_name,
                'package_duration' => $data->package_duration,
                'package_token'=> $data->package_price,
                'package_premiumlist' => $data->premiumlist,
                'package_arangenum' => $data->arangenum,
            ];
        
            array_push($packageArray,$tempArray);

        }

        return response()->json(['status'=>'success','value'=>$packageArray]);
       
    }

    public function show(Request $request){

        $package_id = $request->input('package_id');
        $data = Package::where('package_id',$package_id)->get();

        return response()->json(['status'=>'success','value'=>$data]);
    }

    public function store (Request $request){

        $lastData = DB::table('packages')->latest('arangenum')->first();
        $lastArrange = $lastData->arangenum + 1;

        $data = new Package();
        $data->package_name = $request->input('package_name');
        $data->package_duration = $request->input('package_duration');
        $data->package_price = $request->input('package_token');
        $data->premiumlist = $request->input('premiumlist');
        $data->arangenum = $lastArrange;

        $data->save();

        return response()->json(['status'=>'success','value'=>'Package success added']);

        }
    
    public function rearangepackage(Request $request){

        $rearrangenum = $request->input('rearrangenum');

        $num = $rearrangenum;

        $temp = (json_decode($num));
        
        foreach($temp as $data){
        
            $packagedetails = Package::where('package_id',$data->id)->first();
            $packagedetails->arangenum = $data->num;
            $packagedetails->save();

        }

        return response()->json(['status'=>'success','value'=>'success update arrange num']);
    }
    
    


    public function update(Request $request)
    {

        $package_id = $request->input('package_id');

        $data = Package::where('package_id',$package_id)->first();
        $package_name = $request->input('package_name');
        $package_duration = $request->input('package_duration');
        $package_price = $request->input('package_price');
        $premiumlist = $request->input('premiumlist');
        
        if($package_name == null){
           
            $package_name = $data->package_name;

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
        $data->package_duration = $package_duration;
        $data->package_price = $package_price;
        $data->premiumlist = $premiumlist;
        $data->save();


        return response()->json(['status'=>'success','value'=>'Package updated']);
    }
    
    public function destroy(Request $request){

        $package_id = $request->input('package_id');

        $data = Package::where('package_id',$package_id)->first();
        $data->delete();
    
        return response()->json(['status'=>'success','value'=>'Package success deleted']);
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

        return response()->json(['status'=>'success','value'=>$packArray]);


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

        return response()->json(['status'=>'success','value'=>$packArray]);


    }


    public function deletepaid(Request $request)
    {
 
          $id = $request->input('id');

          $userpack = Userpack::where('id',$id)->first();
          $userpack->delete();

          return response()->json(['status'=>'success','value'=>'Paid package deleted']);



    }

}