<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailer;
use App\Product;
use App\User;


class SearchController extends Controller

{

    public function index(){
        return response()->json(['status'=>'success','value'=>'search index']);
    }

    public function resultsearch(Request $request){

        $main = $request->input('main');
        $rating = $request->input('rating');
        $category = $request->input('category');
        $state = $request->input('state');
        $tempminprice = $request->input('minprice');
        $tagging = $request->input('tagging');


        if($tempminprice != null){
           
            $minprice = floatval($tempminprice);
        } else {
            $minprice = 1000000;
        }
      
       

        $data = [
            'main'=>$main,
            'rating' => $rating,
            'category' => $category,
            'state' => $state,
            'minprice' => $minprice,
            'tagging' => $tagging,
        ];

    
        $products = Product::where('product_status','success')
                ->where('availability','!=','expired')
                ->where('publishstatus','yes')
                ->where(function($q) use ($data){
                    $q->Where('mainstatus',$data['main'])
                    ->orWhere('user_state',$data['state'])
                    ->orWhere('product_category',$data['category'])
                    ->orWhere('rating',$data['rating'])
                    ->orWhere('product_price','>=',$data['minprice']);
                })
                ->orderBy('created_at','DESC')
                ->get();
        
        $finalArray = array();

        if($products){

            foreach($products as $product){

                $user = User::where('user_id',$product->user_id)->first();

                //city
                $json_arrays = json_decode($product->city, true);
                $cityArray = array();

                foreach ($json_arrays as $citys){

                    $tempArray = [
                        'city' => $citys,
                    ];
                    
                    array_push($cityArray,$tempArray);

                }

                //postalcode
                $json_arrays = json_decode($product->postalcode, true);
                $postcodeArray = array();

                foreach($json_arrays as $postalcodes){
                    $tempArray = [
                        'postalcode' => $postalcodes,
                    ];
                    array_push($postcodeArray,$tempArray);
                }

                //image
                $json_array = json_decode($product->product_image, true);
                //$json_array = $product->product_image;
                $imageArray = array();

                foreach($json_array as $pic){
                    $url = 'http://codeviable.com/w2w2/public/image';
                    $public = $url . '/' . $pic;

                    $imagetempArray = [
                        'image' => $public,
                    ];

                    array_push($imageArray,$imagetempArray);
                }

                //location
                $json_array = json_decode($product->product_location, true);
                $locationArray = array();

                foreach($json_array as $locate){
                    $locationtempArray = [
                        'location' => $locate,
                    ];

                    array_push($locationArray,$locationtempArray);
                }

                //longitud
                $json_array = json_decode($product->longitud,true);
                $longitudArray = array();

                foreach($json_array as $longitude){
                    $longitudtempArray = [
                        'longitud' => $longitude,
                    ];
                    array_push($longitudArray,$longitudtempArray);
                }

                //latitude
                $json_array = json_decode($product->latitud, true);
                $latitudArray = array();

                foreach($json_array as $latitude){
                    $latitudetempArray = [
                        'latitud' => $latitude,
                    ];
                    array_push($latitudArray,$latitudetempArray);
                }

                //state
                $json_array = json_decode($product->product_state,true);
                $stateArray = array();

                foreach($json_array as $states){
                    $statetempArray = [
                        'state' => $states,
                    ];

                    array_push($stateArray,$statetempArray);
                }

                //tagging
                $json_array = json_decode($product->tagging,true);
                $tagArray = array();

                foreach($json_array as $tags){
                    $tagtempArray = [
                        'tagging' => $tags,
                    ];

                    array_push($tagArray,$tagtempArray);
                }

                $tempArray = [
                    
                    'product_id' => $product->product_id,
                    'product_date' => $product->product_date,
                    'product_name' => $product->product_name,
                    'product_status' => $product->product_status,
                    'product_material' => $product->product_material,
                    'maincategory' => $product->maincategory,
                    'product_category' => $product->product_category,
                    'product_target' => $product->product_target,
                    'product_continuity' => $product->product_continuity,
                    'product_quantity' => $product->product_quantity,
                    'unit' => $product->unit,
                    'availability' => $product->availability,
                    'product_price' => $product->product_price,
                    'product_pricemax' => $product->product_pricemax,
                    'product_period' => $product->product_period,
                    'product_package' =>$product->product_package,
                    'product_location' =>$locationArray,
                    'city' => $cityArray,
                    'postalcode' => $postcodeArray,
                    'latitud' => $latitudArray,
                    'longitud' => $longitudArray,
                    'product_state' => $stateArray,
                    'product_transport' =>$product->product_transport,
                    'product_description' => $product->product_description,
                    'product_image' => $imageArray,
                    'mainstatus' => $product->mainstatus,
                    'website' => $product->website,
                    'user_id' => $product->user_id,
                    'user_type' => $user->user_type,
                    'package_id' => $product->package_id,
                    'company_name' => $product->company_name,
                    'company_email' => $product->company_email,
                    'company_contact' => $product->company_contact,
                    'tagging' => $tagArray,
                    'premiumlist' => $product->premiumlist,
                    'suggestcustomer' => $product->suggestcustomer,
                    'rejectremark' => $product->rejectremark,
                    'name' => $product->name,
                    'contact' => $product->contact,
                    'publishstatus' => $product->publishstatus,
                    'approved_at' => $product->approved_at,
                    'expired_at' => $product->expired_at,
                    'rating' => $product->rating,
                    'created_at' => $product->created_at->format('d M Y - H:i:s'),
                    'updated_at' => $product->updated_at->format('d M Y - H:i:s'),
                ];

                array_push($finalArray,$tempArray);

            }

            return response()->json(['status'=>'true','value'=>$finalArray]);

        } else {
            return response()->json(['status'=>'false','value'=>'products is not exist']);
        }
    

    }

}