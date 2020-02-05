<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Product;
use App\User;

class ProductController extends Controller
{


    public function index(){

        $finalArray = array();
        $products = Product::all();
        

        foreach ($products as $data) {

            $public = rtrim(app()->basePath('public/image'), '/');
            $imagename = $data->product_image;
            $dirfile = $public.'/'. $imagename;

            $tempArray = [

                'product name' => $data->product_name,
                'product status' => $data->product_status,
                'product material' => $data->product_material,
                'product category' => $data->product_category,
                'product target' => $data->product_target,
                'product continuity' => $data->product_continuity,
                'product quantity' => $data->product_quantity,
                'product price' => $data->product_price,
                'product period' => $data->product_period,
                'product package' =>$data->product_package,
                'product location' => $data->product_location,
                'product state' => $data->product_state,
                'product transport' =>$data->product_transport,
                'product description' => $data->product_description,
                'product image' => $dirfile,
            ];
        
        
            array_push($finalArray, $tempArray);
        }

        return response()->json($finalArray);

   
    }

    public function show($product_id)
    {
        $data = Product::find($product_id);

        if ($data == null) {

            return response()->json('data not exist');
        
         } else {
      
            $public = rtrim(app()->basePath('public/image'), '/');
            $imagename = $data->product_image;
             $dirfile = $public.'/'.$imagename;
           
             $tempArray = [

                'product name' => $data->product_name,
                'product status' => $data->product_status,
                'product material' => $data->product_material,
                'product category' => $data->product_category,
                'product target' => $data->product_target,
                'product continuity' => $data->product_continuity,
                'product quantity' => $data->product_quantity,
                'product price' => $data->product_price,
                'product period' => $data->product_period,
                'product package' =>$data->product_package,
                'product location' => $data->product_location,
                'product state' => $data->product_state,
                'product transport' =>$data->product_transport,
                'product description' => $data->product_description,
                'product image' => $dirfile,
             ];


             return response()->json($tempArray);


    }
}

    public function store(Request $request)
    {
        {

           $validator = \validator::make($request->all(),
        [
            'product_name' => 'required',
            'product_material' => 'required',
            'product_category' => 'required',
            'product_price' => 'required',
            'product_image' => 'mimes:jpg,jpeg,png|required', 
        ]);

        if ($validator->fails()) {

			return response()->json($validator->errors(), 422);

        }

        else
        {


            $product_name = $request->input('product_name');
            $product_status = $request->input ('product_status');
            $product_material = $request->input ('product_material');
            $product_category = $request->input ('product_category');
            $product_target = $request->input('product_target');
            $product_continuity = $request->input ('product_continuity');
            $product_quantity = $request->input ('product_quantity');
            $product_price = $request->input ('product_price');
            $product_period = $request->input('product_period');
            $product_package = $request->input('product_package');
            $product_location = $request->input('product_location');
            $product_state = $request->input('product_state');
            $product_transport = $request->input('product_transport');
            $product_description = $request->input('product_description');  
            $product_image = $request->file('product_image'); 


 
              $images=array();

              
                foreach( $product_image as $image){ 

                 $extention = $image->getClientOriginalExtension();
                 $imagename = rand(11111, 99999) . '.' . $extention;
                 $destinationPath = 'image';
                 $image->move($destinationPath, $imagename);
                 $images[] = $imagename;

                }
        
        $file = new Product();
        $file->product_name = $product_name;
        $file->product_status = $product_status;
        $file->product_material = $product_material;
        $file->product_category = $product_category;
        $file->product_target = $product_target;
        $file->product_continuity = $product_continuity;
        $file->product_quantity = $product_quantity;
        $file->product_price = $product_price;
        $file->product_period = $product_period;
        $file->product_package = $product_package;
        $file->product_location = $product_location;
        $file->product_state = $product_state;
        $file->product_transport = $product_state;
        $file->product_description = $product_description;
        $file->product_image = json_encode($images);
        $file->save(); 
        return response()->json('product added');
 
                
    
    }

             
}
    }



    public function update(Request $request, $product_id)
   {
      
    $data=Product::where('product_id',$product_id)->first();
    $product_name = $request->input('product_name');
    $product_status = $request->input ('product_status');
    $product_material = $request->input ('product_material');
    $product_category = $request->input ('product_category');
    $product_target = $request->input('product_target');
    $product_continuity = $request->input ('product_continuity');
    $product_quantity = $request->input ('product_quantity');
    $product_price = $request->input ('product_price');
    $product_period = $request->input('product_period');
    $product_package = $request->input('product_package');
    $product_location = $request->input('product_location');
    $product_state = $request->input('product_state');
    $product_transport = $request->input('product_transport');
    $product_description = $request->input('product_description');
    $product_image = $request->file('product_image');


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

    if ($product_period == null) {
        $product_period = $data->product_period;
    }

    if ($product_package == null) {
        $product_package = $data->product_package;
    }

    if ($product_location == null) {
        $product_location = $data->product_location;
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

    $data->product_name = $product_name;
    $data->product_status = $product_status;
    $data->product_material = $product_material;
    $data->product_category = $product_category;
    $data->product_target = $product_target;
    $data->product_continuity = $product_continuity;
    $data->product_quantity = $product_quantity;
    $data->product_price = $product_price;
    $data->product_period = $product_period;
    $data->product_package = $product_package;
    $data->product_location = $product_location;
    $data->product_state = $product_state;
    $data->product_transport = $product_state;
    $data->product_description = $product_description;
    $data->product_image = json_encode($images);
    $data->save();

    return response()->json('product updated');

 

   } 

   public function destroy($product_id){
    $data = Product::where('product_id',$product_id)->first();
    $data->delete();

    return response()->json('packages success deleted');
}

}
