<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailer;
use App\Product;
use Carbon\Carbon;
use App\User;
use App\Notification;
use App\Package;
use App\Userpack;
use App\Reserve;
use App\Enquiry;
use DB;

class AdminController extends Controller

{


    public function approve(Request $request)
    {

        $product_id = $request->input('product_id');
        $suggestcustomer = $request->input('suggestcustomer');
        $tagging = $request->input('tagging');

        $tag=array();
        foreach($tagging as $taggings)
        {
            $tag[] = $taggings;
        }


        $products = Product::find($product_id);

          if($products){

            $user = User::find($products->user_id);
            $package = Package::find($products->package_id);

            $curtime = Carbon::now()->toDateTimeString();

            $expiration = $package->package_duration;
            $date = $curtime;
            $expirationdate = date('Y-m-d H:i:s', strtotime($date. ' + ' . $expiration));

            $products->product_status = 'success';
            $products->suggestcustomer = $suggestcustomer;
            $products->tagging = json_encode($tag);
            $products->approved_at = $curtime;
            $products->expired_at = $expirationdate; 
            $products->publishstatus = 'yes';

            $approved = 'approved';

            $notify = new Notification;
            $notify->status = $approved;
            $notify->item = $products->product_name;
            $notify->user_id = $user->user_id;
            $notify->product_id = $product_id;
            $notify->type = 'approval';

            $messages = 'your product, '.$products->product_name.'  has been approved';

            Mail::raw( $messages ,function ($message) use($user)
              {
                $message->to($user->user_email);
                $message->from('testemaillumen123@gmail.com', 'Admin of W2W');
                $message->subject('Product Approval');
                }); 
            
            $products->save(); 
            $notify->save();
        
            return response()->json(['status'=>'success','value'=>'product approved']);

          } else {

            return response()->json(['status'=>'failed','value'=>'product not exist']);

          }

        }
      
         public function reject(Request $request)
         {
  
            $product_id = $request->input('product_id');
            $rejectremark = $request->input('rejectremark');


            $products = Product::where('product_id',$product_id)->first(); 
            $user = User::where('user_id',$products->user_id)->first();

            $products->product_status = 'rejected';
            $products->rejectremark = $rejectremark;

            $rejected = 'rejected';

            //get token back
            $packageDetail = Package::find($products->package_id);
            $amountToken = $packageDetail->package_price;

            $addToken = $user->balancetoken + $amountToken;

            $user->balanceToken = $addToken;

            $notify = new Notification;
            $notify->status = $rejected;
            $notify->user_id = $user->user_id;
            $notify->product_id = $product_id;
            $notify->item = $products->product_name;
            $notify->type = 'approval';

            $products->save();
            $notify->save();
            $user->save();

            $messages = 'your product, '.$products->product_name.'  has been rejected';

            Mail::raw( $messages ,function ($message) use($user)
              {
               $message->to($user->user_email);
               $message->from('testemaillumen123@gmail.com', 'Admin of W2W');
               $message->subject('Product Has Been Rejected');
   
   
               }); 

            return response()->json(['status'=>'success','value'=>'product rejected']);
 

         }

         public function dashboard(Request $request){

            $finalArray = array();

            //product sold
            $product = Reserve::where('status','sold')->orWhere('status','completed')->get();
            $productsold = count($product);

            //waste sold
            $waste = Reserve::where('category','waste')
                            ->where(function($q){
                                $q->where('status','completed')
                                  ->orWhere('status','sold');
                            })
                        ->get();
            $wastesold = count($waste);

            //waste reserved
            $wasteres = Reserve::where('category','waste')
                                ->where('status','reserved')
                                ->get();
            $wastereserved = count($wasteres);
            
            //service sold
            $service = Reserve::where('category','service')
                            ->where(function($q){
                                $q->where('status','completed')
                                ->orWhere('status','sold');
                            })
                        ->get();
            $servicesold = count($service);

            //service reserved
            $serviceres = Reserve::where('category','service')
                                ->where('status','reserved')
                                ->get();
            $servicereserved = count($serviceres);

            //technology sold
            $technology = Reserve::where('category','technology')
                            ->where(function($q){
                                $q->where('status','completed')
                                ->where('status','reserved');
                            })
                        ->get();
            $technologysold = count($technology);

            //technology reserved
            $technologyres = Reserve::where('category','technology')
                                ->where('status','reserved')
                                ->get();
            $technologyreserved = count($technologyres);

            //number of user register individual
            $individual = User::where('user_type','individual')->get();
            $userindividuals = count($individual);

            //number of user register company
            $company = User::where('user_type','company')->get();
            $usercompanies = count($company);

            //number of product active waste
            $tempactivewaste = Product::where('product_status','success')->where('maincategory','waste')->get();
            $activewaste = count($tempactivewaste);

            //number of product active service
            $tempactiveservice = Product::where('product_status','success')->where('maincategory','service')->get();
            $activeservice = count($tempactiveservice);

            //number of product active technology
            $tempactivetechnology = Product::where('product_status','success')->where('maincategory','technology')->get();
            $activetechnology = count($tempactivetechnology);

            //list of pending task
            $arraypendingtask = array();
            $temppendingproduct = Product::where('product_status','processed')
                                ->orderBy('created_at','DESC')
                                ->get();

            foreach($temppendingproduct as $data){

                $temparray = [
                    'id' => $data->product_id,
                    'product_name' => $data->product_name,
                    'product_category' => $data->maincategory,
                    'created_at' => $data->created_at,
                ];

                array_push($arraypendingtask,$temparray);

            }

            //list of notifications
            $arraynotifications = array();

            $tempnoti = Enquiry::orderBy('created_at','DESC')->get();
            
            foreach($tempnoti as $data){

                $temparray = [

                    'id' => $data->id,
                    'category' => $data->category,
                    'description' => $data->description,

                ];

                array_push($arraynotifications,$temparray);

            }

            $finalArray = [

                'productsold' => $productsold,
                'wastesold' => $wastesold,
                'wastereserved' => $wastereserved,
                'servicesold' => $servicesold,
                'servicereserved' => $servicesold,
                'technologysold' => $technologysold,
                'technologyreserved' => $technologyreserved,
                'userindividuals' => $userindividuals,
                'usercompanies' => $usercompanies,
                'activewaste' => $activewaste,
                'activeservice' => $activeservice,
                'activetechnology' => $activetechnology,
                'pendingtask' => $arraypendingtask,
                'notifications' => $arraynotifications,

            ];

            return response()->json(['status'=>'success','value'=>$finalArray]);
            
         }

         public function notificationlist(Request $request){

            $arraynotificationlist = array();
            $tempproduct = Product::where('product_status','processed')
                        ->orderBy('created_at','DESC')
                        ->get();
            
            foreach($tempproduct as $data){

                $temparray = [

                    'id' => $data->product_id,
                    'product_name' => $data->product_name,
                    'product_category' => $data->product_category,
                    'created_at' => $data->created_at,

                ];

                array_push($arraynotificationlist,$temparray);

            }

            $countarray = count($arraynotificationlist);

            $finalarray = array();

            $finalarray = [
                'count' => $countarray,
                'notification' => $arraynotificationlist,
            ];

            return response()->json(['status'=>'success','value'=>$finalarray]);


         }

         public function tempdashboard(Request $request)

         {

          $totalarray = array();

           //user

           $user = User::all();
           $users = count($user);

           $individual = User::where('user_type','individual')->get();
           $individuals = count($individual);

           $company = User::where('user_type','company')->get();
           $companies = count($company);


           //sold product
           $sold = Reserve::where('status','completed')->get();
           $solds = count ($sold);

           $waste = Reserve::where('status','completed')->where('category','waste')->get();
           $wasted = count($waste);

           $service = Reserve::where('status','completed')->where('category','service')->get();
           $services = count($service);



           $technology = Reserve::where('status','completed')->where('category','technology')->get();
           $technologies = count($technology);



           //approved product

           $product = Product::where('product_status','success')->get();
           $products = count($product);


           $productwaste = Product::where('product_status','success')->where('maincategory','waste')->get();
           $productwasted = count($productwaste);
           
           
           $productservice = Product::where('product_status','success')->where('maincategory','service')->get();
           $productserviced = count($productservice);


           $producttechnology = Product::where('product_status','success')->where('maincategory','technology')->get();
           $producttechnologies = count($producttechnology);


           $temparray = [

             'total_user' => $users,
             'total_individual' => $individuals,
             'total_company' => $companies,
             'total_sold_product' => $solds,
             'total_sold_waste' => $wasted,
             'total_sold_technology' => $technologies,
             'total_sold_services' => $services,
             'total_approved_product' => $products,
             'total_approved_waste' => $productwasted,
             'total_approved_service' => $productserviced,
             'total_approved_technology' => $producttechnologies,

           ];

           array_push($totalarray,$temparray);
          
  
           
           return response()->json($totalarray);           
      
         }



         public function dashstate(Request $request)
         {

          $state = $request->input('state');
          $statearray = array();
          $states = User::where('state',$state)->get();
          $stated = count($states);

            $temparray =[
             'total_user' => $stated,
            ] ;

            array_push($statearray,$temparray);
            
           return response()->json($statearray);

         }

         public function tempstatecategory(Request $request){

            $state = $request->input('state');

            $tempsellerwaste = DB::table('products')
                    ->select('user_id', DB::raw('count(*) as total'))
                    ->groupBy('user_id')
                    ->where('user_state',$state)
                    ->where('maincategory','waste')
                    ->get();
            
            $tempsellerservice = DB::table('products')
                        ->select('user_id', DB::raw('count(*) as total'))
                        ->groupBy('user_id')
                        ->where('user_state',$state)
                        ->where('maincategory','service')
                        ->get();
            
            $tempsellertechnology = DB::table('products')
                            ->select('user_id', DB::raw('count(*) as total'))
                            ->groupBy('user_id')
                            ->where('user_state',$state)
                            ->where('maincategory','technology')
                            ->get();
            
            $countsellerwaste = count($tempsellerwaste);
            $sellerwaste = [
                'count' => $countsellerwaste,
                'user_id' => $tempsellerwaste,
            ];

            $countsellerservice = count($tempsellerservice);
            $sellerservice = [
                'count' => $countsellerservice,
                'user_id' => $tempsellerservice,
            ];

            $countsellertechnology = count($tempsellertechnology);
            $sellertechnology = [
                'count' => $countsellertechnology,
            ];



            $sellerarray = [

                'waste' => $countsellerwaste,
                'service' => $countsellerservice,
                'technology' => $countsellertechnology,
                
            ];

            $arraybuyer = array();
            $buyer = Reserve::where('status','completed')->orWhere('status','sold')->get();
            
            foreach($buyer as $data){

                $productdetails = Product::where('product_id',$data->product_id)->first();
                
                if($productdetails->user_state == $state){
                    
                  $temparray = [
                    'id' => $data->id,
                    'buyer_id' => $data->buyer_id,
                    'product_id' => $data->product_id,
                    'state' => $productdetails->user_state,
                    'maincategory' => $productdetails->maincategory,
                  ];

                    array_push($arraybuyer,$temparray);

                }

            }

            $tempbuyerwaste = array();
            $tempbuyerservice = array();
            $tempbuyertechnology = array();

            foreach($arraybuyer as $dataa){

                $tempdata = [
                    'id' => $dataa['id'],
                    'buyer_id' => $dataa['buyer_id'],
                    'product_id' => $dataa['product_id'],
                    'state' => $dataa['state'],
                    'maincategory' => $dataa['maincategory'],
                ];
                
               
                if($dataa['maincategory'] == 'waste'){
                    array_push($tempbuyerwaste,$tempdata);
                }

                elseif($dataa['maincategory'] == 'service'){
                    array_push($tempbuyerservice,$tempdata);
                }

                elseif($dataa['maincategory'] == 'technology'){
                    array_push($tempbuyertechnology,$tempdata);
                }

            }

            $countbuyerwaste = count($tempbuyerwaste);
            $countbuyerservice = count($tempbuyerservice);
            $countbuyertechnology = count($tempbuyertechnology);
            
            $finalbuyyerarray = array();

            $finalbuyyerarray = [

                'waste' => $countbuyerwaste,
                'service' => $countbuyerservice,
                'technology' => $countbuyertechnology,

            ];

            $finalstatearray = array();

            $finalstatearray = [

                'seller' => $sellerarray,
                'buyer' => $finalbuyyerarray,

            ];
           
            return response()->json(['status'=>'success','value'=>$finalstatearray]);
         }

         public function listproduct(Request $request){

            $mainstatus = $request->input('mainstatus');
            $status = $request->input('status');
            $finalArray = array();

            if($status){
              $products = Product::where('mainstatus',$mainstatus)
                                ->where('product_status',$status)
                                ->orderBy('created_at','DESC')  
                                ->get();
            } else {
              $products = Product::where('mainstatus',$mainstatus)
                                  ->orderBy('created_at','DESC')
                                  ->get();
            }

            foreach ($products as $product){

              $user_id = $product->user_id;
              $users = User::where('user_id',$user_id)->first();
      
              $json_arrays = json_decode($product->city, true);
              $cityArray = array();
          
                      foreach ($json_arrays as $citys)
                      {
                          $tempArray = [
                                  'city' => $citys,
                                  ];
          
                          array_push($cityArray,$tempArray);
          
                              }
              $json_arrays = json_decode($product->postalcode, true);
              $postcodeArray = array();
          
                      foreach ($json_arrays as $postcodes)
                      {
                          $tempArray = [
                                  'postalcode' => $postcodes,
                                  ];
          
                          array_push($postcodeArray,$tempArray);
          
                              }  
      
              $json_array = json_decode($product->product_image, true);
              //$json_array = $product->product_image;
              $imageArray = array();
              
                      foreach ($json_array as $pic)
                      {
                      $url = 'https://codeviable.com/w2w2/public/image';
                      $public =  $url .'/'. $pic;
                          
      
                          $imagetempArray = [
                              'image' => $public,
                          ];
      
                          array_push($imageArray,$imagetempArray);
                      }
          
                      $json_arrays = json_decode($product->product_location, true);
                      $locationArray = array();
      
                              foreach ($json_arrays as $locate)
                              {
                                  $locationtempArray = [
                                      'location' => $locate,
                                  ];
      
                                  array_push($locationArray,$locationtempArray);
      
                              }
      
                      
      
                      $json_longitud = json_decode($product->longitud, true);
                      $longitudArray = array();
                  
                                  foreach ($json_longitud as $longitude)
                                  {
                                      $longitudtempArray = [
                                          'longitud' => $longitude,
                                      ];
                  
                                          array_push($longitudArray,$longitudtempArray);
                  
                                      }
      
                      $json_latitud = json_decode($product->latitud, true);
                      $latitudArray = array();
                                  
                                  foreach ($json_latitud as $latitude)
                                  {
                                      $latitudtempArray = [
                                              'latitud' => $latitude,
                                      ];
                                  
                                          array_push($latitudArray,$latitudtempArray);
                                  
                                      }
      
                      $json_state = json_decode($product->product_state, true);
                      $stateArray = array();
                                                  
                                  foreach ($json_state as $states)
                                  {
                                      $statetempArray = [
                                          'state' => $states,
                                      ];
                                                  
                                          array_push($stateArray,$statetempArray);
                                                  
                                      }
                      
                  
                                      $json_tag = json_decode($product->tagging, true);
                                      $tagArray = array();
                                                                                  
                                          foreach ($json_tag as $tags)
                                          {
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
                                          'user_type' => $users->user_type,
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
                                          'created_at' => $product->created_at->format('d M Y - H:i:s'),
                                          'updated_at' => $product->updated_at->format('d M Y - H:i:s'),
                                      ];
                      
                      array_push($finalArray,$tempArray);
  
                  }
                return response()->json(['status'=>'success','value'=>$finalArray]);
         }

         public function addadmin(Request $request){

            $validator = \validator::make($request->all(),
            [
                'user_fname' => 'required',
                'user_lname' => 'required',
                'user_email' => 'required',
                'password' => 'required',
            ]);

            if($validator->fails()){
                return response()->json($validator->errors(), 422);
            } else {

                $user_fname = $request->input('user_fname');
                $user_lname = $request->input('user_lname');
                $user_email = $request->input('user_email');
                $password = $request->input('password');

                $userexist = User::where('user_email',$user_email)->first();

                if($userexist){
                    return response()->json(['status'=>'failed','value'=>'email is exist']);
                } else {

                    $user_type = 'admin';
                    $user_role = 'admin';
                    $status = 'active';

                    $user = new User;
                    $user->user_fname = $user_fname;
                    $user->user_lname = $user_lname;
                    $user->user_email = $user_email;
                    $user->password = $password;
                    $user->user_type = $user_type;
                    $user->user_role = $user_role;
                    $user->status = $status;

                    $user->save();

                    return response()->json(['status'=>'success','value'=>'success add admin']);

                }

            }

         }

         public function listadmin(){

            $user = User::where('user_type','admin')
                        ->orderBy('created_at','DESC')
                        ->get();

            return response()->json(['status'=>'success','value'=>$user]);

         }

         public function destroy(Request $request){
             $id = $request->input('id');

             $user = User::find($id);
             $user->delete();
             return response()->json(['status'=>'success','value'=>'success delete']);
         }

         public function editadmin(Request $request){
             $id = $request->input('id');
             $user_fname = $request->input('user_fname');
             $user_lname = $request->input('user_lname');
             $user_email = $request->input('user_email');
             $password = $request->input('password');

             $existuser = null;

             $user = User::find($id);

             if($user_fname == null){
                 $user_fname = $user->user_fname;
             }
             if($user_lname == null){
                 $user_lname = $user->user_lname;
             }
             if($password == null){
                 $password = $user->password;
             }

             if($user_email == null){
                 $user_email = $user->user_email;
             } else {

                $existuser = User::where('user_email',$user_email)->first();

             }

             if($existuser){
                 return response()->json(['status'=>'failed','value'=>'email is exist']);
             } else {

                $user->user_fname = $user_fname;
                $user->user_lname = $user_lname;
                $user->user_email = $user_email;
                $user->password = $password;

                $user->save();

                return response()->json(['status'=>'success','value'=>'update admin user']);

             }


         }

         public function detailadmin(Request $request){

            $id = $request->input('id');

            $user = User::find($id);

            $tempuser = [
                'user_id' => $user->user_id,
                'user_fname' => $user->user_fname,
                'user_lname' => $user->user_lname,
                'user_email' => $user->user_email,
                'password' => $user->password,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ];

            return response()->json(['status'=>'success','value'=>$tempuser]);

         }
}
    
    
