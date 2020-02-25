<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Product;
use App\Package;
use App\User;

class ProductController extends Controller
{


    public function index(){
        
       
        $finalArray = array();
        $products = Product::all();
                    

            
            foreach ($products as $product){

                $json_array = json_decode($product->product_image, true);
                $imageArray = array();
                
                        foreach ($json_array as $pic)
                        {
                            $public = rtrim(app()->basePath('public/image'), '/');
                            $imagepath = $public.'/'.$pic;
                            
          
                            $imagetempArray = [
                                'image' => $imagepath,
                            ];
          
                            array_push($imageArray,$imagetempArray);
                        }
               
                 $tempArray = [
          
                    'product_id' => $product->product_id,
                    'product_date' => $product->product_date,
                    'product_name' => $product->product_name,
                    'product_status' => $product->product_status,
                    'product_material' => $product->product_material,
                    'product_category' => $product->product_category,
                    'product_target' => $product->product_target,
                    'product_continuity' => $product->product_continuity,
                    'product_quantity' => $product->product_quantity,
                    'product_price' => $product->product_price,
                    'product_pricemax' => $product->product_pricemax,
                    'product_period' => $product->product_period,
                    'product_package' =>$product->product_package,
                    'product_location' => $product->product_location,
                    'latitud' => $product->latitud,
                    'longitud' => $product->longitud,
                    'product_state' => $product->product_state,
                    'product_transport' =>$product->product_transport,
                    'product_description' => $product->product_description,
                    'product_image' => $imageArray,
                    'mainstatus' => $product->mainstatus,
                    'website' => $product->website,
                    'user_id' => $product->user_id,
                    'company_name' => $product->company_name,
                    'company_email' => $product->company_email,
                    'company_contact' => $product->company_contact,
                 ];
                 
                array_push($finalArray,$tempArray);
                        
                }

             return response()->json($finalArray);   
            }
 
   
    



    public function show(Request $request)

    {
        $product_id = $request->input('product_id');
        $finalArray = array();
        $products = Product::where('product_id',$product_id)->get();

        foreach ($products as $product){

            $json_array = json_decode($product->product_image, true);
            $imageArray = array();
            
                    foreach ($json_array as $pic)
                    {
                        $public = rtrim(app()->basePath('public/image'), '/');
                        $imagepath = $public.'/'.$pic;
                        
      
                        $imagetempArray = [
                            'image' => $imagepath,
                        ];
      
                        array_push($imageArray,$imagetempArray);
                    }
           
             $tempArray = [
      
                'product_id' => $product->product_id,
                'product_date' => $product->product_date,
                'product_name' => $product->product_name,
                'product_status' => $product->product_status,
                'product_material' => $product->product_material,
                'product_category' => $product->product_category,
                'product_target' => $product->product_target,
                'product_continuity' => $product->product_continuity,
                'product_quantity' => $product->product_quantity,
                'product_price' => $product->product_price,
                'product_pricemax' => $product->product_pricemax,
                'product_period' => $product->product_period,
                'product_package' =>$product->product_package,
                'product_location' => $product->product_location,
                'latitud' => $product->latitud,
                'longitud' => $product->longitud,
                'product_state' => $product->product_state,
                'product_transport' =>$product->product_transport,
                'product_description' => $product->product_description,
                'product_image' => $imageArray,
                'mainstatus' => $product->mainstatus,
                'website' => $product->website,
                'user_id' => $product->user_id,
                'company_name' => $product->company_name,
                'company_email' => $product->company_email,
                'company_contact' => $product->company_contact,
             ];
             
            array_push($finalArray,$tempArray);
              }        
             return response()->json($finalArray); 


    
}

    public function store(Request $request)
    {
        {

           $validator = \validator::make($request->all(),
        [
            'product_name' => 'required',
            'product_category' => 'required',
            'product_package' => 'required',
            

        ]);

        if ($validator->fails()) {

			return response()->json($validator->errors(), 422);

        }

        else
        {

            $product_date = $request->input('product_date');
            $product_name = $request->input('product_name');
            $product_material = $request->input ('product_material');
            $product_category = $request->input ('product_category');
            $product_target = $request->input('product_target');
            $product_continuity = $request->input ('product_season');
            $product_quantity = $request->input ('product_quantity');
            $product_price = $request->input ('product_price');
            $product_pricemax = $request->input('product_pricemax');
            $product_period = $request->input('product_period');
            $product_package = $request->input('product_package');
            $product_location = $request->input('product_location');
            $longitud = $request->input('longitud');
            $latitud = $request->input('latitud');
            $product_state = $request->input('product_state');
            $product_transport = $request->input('product_transport');
            $product_description = $request->input('product_description');  
            $product_image = $request->file('product_image');
            $mainstatus = $request->input('mainstatus');
            $website = $request->input('website');
            $user_id = $request->input('user_id');  
            $package_id = $request->input('package_id');   
            $product_status = 'processed';
            $company_name = $request->input('company_name');
            $company_email = $request->input('company_email');
            $company_contact = $request->input('company_contact');

 
              $images=array();

              
                foreach( $product_image as $image){ 

                 $extention = $image->getClientOriginalExtension();
                 $imagename = rand(11111, 99999) . '.' . $extention;
                 $destinationPath = 'image';
                 $image->move($destinationPath, $imagename);
                 $images[] = $imagename;

                }
        
        $file = new Product();
        $file->product_date = $product_date;
        $file->product_name = $product_name;
        $file->product_status = $product_status;
        $file->product_material = $product_material;
        $file->product_category = $product_category;
        $file->product_target = $product_target;
        $file->product_continuity = $product_continuity;
        $file->product_quantity = $product_quantity;
        $file->product_price = $product_price;
        $file->product_pricemax = $product_pricemax;
        $file->product_period = $product_period;
        $file->product_package = $product_package;
        $file->product_location = $product_location;
        $file->latitud = $latitud;
        $file->longitud = $longitud;
        $file->product_state = $product_state;
        $file->product_transport = $product_transport;
        $file->product_description = $product_description;
        $file->product_image = json_encode($images);
        $file->mainstatus = $mainstatus;
        $file->website = $website;
        $file->user_id = $user_id;
        $file->package_id = $package_id;   
        $file->company_name = $company_name;
        $file->company_email = $company_email;
        $file->company_contact = $company_contact;
        $file->save(); 

        return response()->json('product added');
 
                
    
    }

             
}
    }



    public function update(Request $request)
   {
    
    $product_id = $request->input('product_id');
      
    $data=Product::where('product_id',$product_id)->first();
    
    $product_date = $request->input('product_date');
    $product_name = $request->input('product_name');
    $product_material = $request->input ('product_material');
    $product_category = $request->input ('product_category');
    $product_target = $request->input('product_target');
    $product_continuity = $request->input ('product_season');
    $product_quantity = $request->input ('product_quantity');
    $product_price = $request->input ('product_price');
    $product_pricemax = $request->input ('product_pricemax');
    $product_period = $request->input('product_period');
    $product_package = $request->input('product_package');
    $product_location = $request->input('product_location');
    $longitud = $request->input('longitud');
    $latitud = $request->input('latitud');
    $product_state = $request->input('product_state');
    $product_transport = $request->input('product_transport');
    $product_description = $request->input('product_description');  
    $product_image = $request->file('product_image');
    $mainstatus = $request->input('mainstatus');
    $website = $request->input('website');
    $user_id = $request->input('user_id');  
    $package_id = $request->input('package_id');   
    $product_status = $request->input('product_status');
    $company_name = $request->input('company_name');
    $company_email = $request->input('company_email');
    $company_contact = $request->input('company_contact');


    if ($product_date == null) {
        $product_date = $data->product_date;
    }

    if ($product_name == null) {
        $product_name = $data->product_name;
    }

    if ($product_status == null) {
        $product_status = $data->product_status;
    }

    if ($product_material == null) {
        $product_category = $data->product_category;
    }

    if ($product_target == null) {
        $product_target = $data->product_target;
    }

    if ($product_continuity == null) {
        $product_continuity = $data->product_continuity;
    }

    if ($product_quantity == null) {
        $product_quantity = $data->product_quantity;
    }

    if ($product_price == null) {
        $product_price = $data->product_price;
    }

    if ($product_pricemax == null) {
        $product_pricemax = $data->product_pricemax;
    }

    if ($product_period == null) {
        $product_period = $data->product_period;
    }

    if ($product_package == null) {
        $product_package = $data->product_package;
    }

    if ($product_location == null) {
        $product_location = $data->product_location;
    }

    if($latitud == null) {
        $latitud = $data->latitud;
    }

    if($longitud == null)
    {
        $longitud = $data->longitud;
    }

    if ($product_state == null) {
        $product_state = $data->product_state;
    }

    if ($product_transport == null) {
        $product_transport = $data->product_transport;
    }

    if ($product_description == null) {
        $product_description = $data->product_description;
    }

    if ($product_image == null) {
        $images = $data->product_image;
    } 
    else {

        $images=array();
        $product_image = $request->file('product_image'); 
                 
        foreach( $product_image as $image){ 

         $extention = $image->getClientOriginalExtension();
         $imagename = rand(11111, 99999) . '.' . $extention;
         $destinationPath = 'image';
         $image->move($destinationPath, $imagename);
         $images[] = $imagename;

        }
    }

    if ($mainstatus == null)
    {
        $mainstatus = $data->mainstatus;
    }
    
    if($website == null){

        $website = $data->website;
    }

    if($company_name == null){

        $company_name = $data->company_name;
    }

    if($company_email == null){

        $company_email = $data->company_email;
    }

    if($company_contact == null){

        $company_contact = $data->company_contact;
    }

    $data->product_date = $product_date;
    $data->product_name = $product_name;
    $data->product_status = $product_status;
    $data->product_material = $product_material;
    $data->product_category = $product_category;
    $data->product_target = $product_target;
    $data->product_continuity = $product_continuity;
    $data->product_quantity = $product_quantity;
    $data->product_price = $product_price;
    $data->product_pricemax = $product_pricemax;
    $data->product_period = $product_period;
    $data->product_package = $product_package;
    $data->product_location = $product_location;
    $data->latitud = $latitud;
    $data->longitud = $longitud;
    $data->product_state = $product_state;
    $data->product_transport = $product_state;
    $data->product_description = $product_description;
    $data->product_image = json_encode($images);
    $data->mainstatus = $mainstatus;
    $data->website = $website;
    $data->company_name = $company_name;
    $data->company_email = $company_email;
    $data->company_contact = $company_contact;
    $data->save();

    return response()->json('product updated');

 

   } 

   public function destroy(Request $request){
 
    $product_id = $request->input('product_id');
      
    $data = Product::where('product_id',$product_id)->first();
    $data->delete();

    return response()->json('product success deleted');
}



  public function listcategory(Request $request)
  {
    
      $product_category = $request->input('product_category');
      $finalArray = array();
      $products = Product::where('product_category',$product_category)->get();

      foreach ($products as $product){

      $json_array = json_decode($product->product_image, true);
      $imageArray = array();
      
              foreach ($json_array as $pic)
              {
                  $public = rtrim(app()->basePath('public/image'), '/');
                  $imagepath = $public.'/'.$pic;
                  

                  $imagetempArray = [
                      'image' => $imagepath,
                  ];

                  array_push($imageArray,$imagetempArray);
              }
     
       $tempArray = [
        'product_id' => $product->product_id,
        'product_date' => $product->product_date,
        'product_name' => $product->product_name,
        'product_status' => $product->product_status,
        'product_material' => $product->product_material,
        'product_category' => $product->product_category,
        'product_target' => $product->product_target,
        'product_continuity' => $product->product_continuity,
        'product_quantity' => $product->product_quantity,
        'product_price' => $product->product_price,
        'product_pricemax' => $product->product_pricemax,
        'product_period' => $product->product_period,
        'product_package' =>$product->product_package,
        'product_location' => $product->product_location,
        'latitud' => $product->latitud,
        'longitud' => $product->longitud,
        'product_state' => $product->product_state,
        'product_transport' =>$product->product_transport,
        'product_description' => $product->product_description,
        'product_image' => $imageArray,
        'mainstatus' => $product->mainstatus,
        'website' => $product->website,
        'user_id' => $product->user_id,
        'company_name' => $product->company_name,
        'company_email' => $product->company_email,
        'company_contact' => $product->company_contact,
       ];
       
      array_push($finalArray,$tempArray);
        }        
       return response()->json($finalArray); 

    } 


    public function listuserproduct(Request $request)

    {
        $user_id = $request->input('user_id');
        $finalArray = array();
        $products = Product::where('user_id',$user_id)->get();

        foreach ($products as $product){

            $json_array = json_decode($product->product_image, true);
            $imageArray = array();
            
                    foreach ($json_array as $pic)
                    {
                        $public = rtrim(app()->basePath('public/image'), '/');
                        $imagepath = $public.'/'.$pic;
                        
      
                        $imagetempArray = [
                            'image' => $imagepath,
                        ];
      
                        array_push($imageArray,$imagetempArray);
                    }
           
             $tempArray = [
      
                'product_id' => $product->product_id,
                'product_date' => $product->product_date,
                'product_name' => $product->product_name,
                'product_status' => $product->product_status,
                'product_material' => $product->product_material,
                'product_category' => $product->product_category,
                'product_target' => $product->product_target,
                'product_continuity' => $product->product_continuity,
                'product_quantity' => $product->product_quantity,
                'product_price' => $product->product_price,
                'product_pricemax' => $product->product_pricemax,
                'product_period' => $product->product_period,
                'product_package' =>$product->product_package,
                'product_location' => $product->product_location,
                'latitud' => $product->latitud,
                'longitud' => $product->longitud,
                'product_state' => $product->product_state,
                'product_transport' =>$product->product_transport,
                'product_description' => $product->product_description,
                'product_image' => $imageArray,
                'mainstatus' => $product->mainstatus,
                'website' => $product->website,
                'user_id' => $product->user_id,
                'company_name' => $product->company_name,
                'company_email' => $product->company_email,
                'company_contact' => $product->company_contact,
             ];
             
            array_push($finalArray,$tempArray);
              }        
             return response()->json($finalArray); 
      

    }

    public function productstatus(Request $request)
    {
        $product_status = $request->input('product_status');
        $finalArray = array();
        $products = Product::where('product_status',$product_status)->get();


        foreach ($products as $product){

            $json_array = json_decode($product->product_image, true);
            $imageArray = array();
            
                    foreach ($json_array as $pic)
                    {
                        $public = rtrim(app()->basePath('public/image'), '/');
                        $imagepath = $public.'/'.$pic;
                        
      
                        $imagetempArray = [
                            'image' => $imagepath,
                        ];
      
                        array_push($imageArray,$imagetempArray);
                    }
           
             $tempArray = [
      
                'product_id' => $product->product_id,
                'product_date' => $product->product_date,
                'product_name' => $product->product_name,
                'product_status' => $product->product_status,
                'product_material' => $product->product_material,
                'product_category' => $product->product_category,
                'product_target' => $product->product_target,
                'product_continuity' => $product->product_continuity,
                'product_quantity' => $product->product_quantity,
                'product_price' => $product->product_price,
                'product_pricemax' => $product->product_pricemax,
                'product_period' => $product->product_period,
                'product_package' =>$product->product_package,
                'product_location' => $product->product_location,
                'latitud' => $product->latitud,
                'longitud' => $product->longitud,
                'product_state' => $product->product_state,
                'product_transport' =>$product->product_transport,
                'product_description' => $product->product_description,
                'product_image' => $imageArray,
                'mainstatus' => $product->mainstatus,
                'website' => $product->website,
                'user_id' => $product->user_id,
                'company_name' => $product->company_name,
                'company_email' => $product->company_email,
                'company_contact' => $product->company_contact,
             ];
             
            array_push($finalArray,$tempArray);
              }        
             
              return response()->json($finalArray); 



    }


    public function mainstatus(Request $request)
    {
        $mainstatus = $request->input('mainstatus');
        $finalArray = array();
        $products = Product::where('mainstatus',$mainstatus)->get();


        foreach ($products as $product){

            $json_array = json_decode($product->product_image, true);
            $imageArray = array();
            
                    foreach ($json_array as $pic)
                    {
                        $public = rtrim(app()->basePath('public/image'), '/');
                        $imagepath = $public.'/'.$pic;
                        
      
                        $imagetempArray = [
                            'image' => $imagepath,
                        ];
      
                        array_push($imageArray,$imagetempArray);
                    }
           
             $tempArray = [
      
                'product_id' => $product->product_id,
                'product_date' => $product->product_date,
                'product_name' => $product->product_name,
                'product_status' => $product->product_status,
                'product_material' => $product->product_material,
                'product_category' => $product->product_category,
                'product_target' => $product->product_target,
                'product_continuity' => $product->product_continuity,
                'product_quantity' => $product->product_quantity,
                'product_price' => $product->product_price,
                'product_pricemax' => $product->product_pricemax,
                'product_period' => $product->product_period,
                'product_package' =>$product->product_package,
                'product_location' => $product->product_location,
                'latitud' => $product->latitud,
                'longitud' => $product->longitud,
                'product_state' => $product->product_state,
                'product_transport' =>$product->product_transport,
                'product_description' => $product->product_description,
                'product_image' => $imageArray,
                'mainstatus' => $product->mainstatus,
                'website' => $product->website,
                'user_id' => $product->user_id,
                'company_name' => $product->company_name,
                'company_email' => $product->company_email,
                'company_contact' => $product->company_contact,
             ];
             
            array_push($finalArray,$tempArray);
              }        
             
              return response()->json($finalArray); 



    }




  }






