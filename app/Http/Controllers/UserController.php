<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailer;
use App\User;
use App\Package;


class UserController extends Controller
{

    public function index(){

        $finalArray = array();
        $user = User::all();


        foreach ($user as $data) {

            $public = rtrim(app()->basePath('public/image'), '/');
            $imagename = $data->profilepicture;
            $dirfile = $public.'/' . $imagename;
            
     

			$tempArray = [
                'user_id' =>$data->user_id,
                'user_fname' => $data->user_fname,
                'user_lname' => $data->user_lname,
                'companyname' => $data->companyname,
                'password' => $data->password,
                'user_contact' => $data->user_contact,
                'user_email' => $data->user_email,
                'companyregisternumber' => $data->companyregisternumber,
                'companydesc' => $data->companydesc,
                'address' => $data->address,
                'occupation' => $data->occupation,
                'job_title' => $data->job_title,
                'user_type' => $data->user_type,
                'profilepicture' => $dirfile,
                'user_role' => $data->user_role,
                'personincharge' => $data->personincharge,
                'phonenumber' => $data->phonenumber,
                'created_at' =>$data->created_at,

			];
        
			array_push($finalArray, $tempArray);

        }
        
            return response()->json($finalArray); 
    
}

    public function show(Request $request){
        
        $user_id = $request->input('user_id');

        $data = User::find($user_id);

        if ($data == null) {

            return response()->json('data not exist');
        
         } else {
      
            $public = rtrim(app()->basePath('public/image'), '/');
            $imagename = $data->profilepicture;
             $dirfile = $public.'/'.$imagename;

             
            
             $tempArray = [

                'user_id' => $data->user_id,
                'user_fname' => $data->user_fname,
                'user_lname' => $data->user_lname,
                'companyname' => $data->companyname,
                'password' => $data->password,
                'user_contact' => $data->user_contact,
                'user_email' => $data->user_email,
                'companyregisternumber' => $data->companyregisternumber,
                'companydesc' => $data->companydesc,
                'address' => $data->address,
                'occupation' => $data->occupation,
                'job_title' => $data->job_title,
                'user_type' => $data->user_type,
                'profilepicture' => $dirfile,
                'user_role' => $data->user_role,
                'personincharge' => $data->personincharge,
                'phonenumber' => $data->phonenumber,
                'created_at' =>$data->created_at,
                'status' => $data->status,

             ];
            
             return response()->json($tempArray);
       
            }

    }
    

    public function store (Request $request){

        $validator = \validator::make($request->all(),
        [
            'password' => 'required',
            'user_email' => 'required',
            
        ]);

        if ($validator->fails()) {

			return response()->json($validator->errors(), 422);

		}

        else{
            
            $user_fname = $request->input('user_fname');
            $user_lname = $request->input('user_lname');
            $companyname = $request->input('companyname');
            $password = $request->input('password');
            $user_contact = $request->input('user_contact');
            $user_email = $request->input('user_email');
            $companyregisternumber = $request->input('companyregisternumber');
            $companydesc = $request->input('companydesc');
            $address = $request->input('address');
            $occupation = $request->input('occupation');
            $job_title = $request->input('job_title'); 
            $user_type = $request->input('user_type');     
            $profilepicture = $request->file('profilepicture');
            $personincharge = $request->input('personincharge');
            $phonenumber = $request->input('phonenumber');

            if($request->hasfile('profilepicture')){
            $extention = $profilepicture->getClientOriginalExtension();
            $imagename = rand(11111, 99999) . '.' . $extention;
            $destinationPath = 'image';
    
            $profilepicture->move($destinationPath, $imagename);

            }

            else if ($profilepicture == null) {
                $imagename = null;

            }

            

         }



    

        $data = new User();
        $data->user_fname = $user_fname;
        $data->user_lname = $user_lname;
        $data->companyname = $companyname;
        $data->password = $password;
        $data->user_contact = $user_contact;
        $data->user_email = $user_email;
        $data->companyregisternumber = $companyregisternumber; 
        $data->companydesc = $companydesc;
        $data->address = $address;
        $data->occupation = $occupation;
        $data->job_title = $job_title;
        $data->user_type = $user_type;
        $data->profilepicture=$imagename;
        $data->user_role = 'user';
        $data->personincharge = $personincharge;
        $data->phonenumber = $phonenumber;
        $data->status = 'active';
        $data->save();
    
        return response()->json('User has been registered');
        
    }


    public function update(Request $request)
    {
        
        $user_id = $request->input('user_id');

        $data = User::where('user_id',$user_id)->first();
        
        $user_fname = $request->input('user_fname');
        $user_lname = $request->input('user_lname');
        $companyname = $request->input('companyname');
        $password = $request->input('password');
        $user_contact = $request->input('user_contact');
        $user_email = $request->input('user_email');
        $companyregisternumber = $request->input('companyregisternumber');
        $companydesc = $request->input('companydesc');
        $address = $request->input('address');
        $occupation = $request->input('occupation');
        $job_title = $request->input('job_title');
        $profilepicture = $request->file('profilepicture'); 
        $personincharge = $request->input('personincharge');
        $phonenumber = $request->input('phonenumber');
        $status = $request->input('status');


       

      if($data->user_type == 'individual'){
        
        if ($user_fname == null) {
            $user_fname = $data->user_fname;
        }
        if ($user_lname == null) {
            $user_lname= $data->user_lname;
        }

        if ($password == null) {
        $password = $data->password;
        }

        if ($user_contact == null) {
        $user_contact= $data->user_contact;
        }

        if ($user_email == null) {
        $user_email = $data->user_email;
        }

        if ($address == null) {
            $address = $data->address;
            }

        if ($occupation == null) {
                $occupation = $data->occupation;
                }
        if ($personincharge == null) {
                $personincharge = $data->personincharge;
                }
            
        if ($phonenumber == null) {
                $phonenumber = $data->phonenumber;
                }

                if ($status == null) {
                    $status = $data->status;
                    }

         if ($profilepicture == null) {
            $imagename = $data->profilepicture;
        } else {

            $extention = $profilepicture->getClientOriginalExtension();
            $imagename = rand(11111, 99999) . '.' . $extention;
            $destinationPath = 'image';
    
            $profilepicture->move($destinationPath, $imagename); 

        }
     
        $data->user_fname = $user_fname;
        $data->user_lname = $user_lname;
        $data->password = $password;
        $data->user_contact = $user_contact;
        $data->user_email = $user_email;
        $data->address = $address;
        $data->occupation = $occupation;
        $data->profilepicture = $imagename;
        $data->personincharge = $personincharge;
        $data->phonenumber = $phonenumber;
        $data->status =$status;
        $data->save();

        return response()->json('User Updated'); 

        

      }

      if($data->user_type == 'company'){
        
        if ($companyname == null) {
            $companyname = $data->companyname;
        }
        if ($password == null) {
            $password= $data->password;
        }

        if ($user_contact == null) {
            $user_contact = $data->user_contact;
        }

        if ($user_email == null) {
        $user_email= $data->user_email;
        }

        if ($companyregisternumber == null) {
        $companyregisternumber = $data->companyregisternumber;
        } 
      
       if($companydesc == null){
       $companydesc = $data->companydesc;
      }

      if($address == null){
        $address = $data->address;
       }

       if($job_title == null){
        $job_title = $data->job_title;
       }

       if ($personincharge == null) {
        $personincharge = $data->personincharge;
        }
    
if ($phonenumber == null) {
        $phonenumber = $data->phonenumber;
        }

        if ($status == null) {
            $status = $data->status;
            }

      if ($profilepicture == null) {
        $imagename = $data->profilepicture;
    } else {

        $extention = $profilepicture->getClientOriginalExtension();
        $imagename = rand(11111, 99999) . '.' . $extention;
        $destinationPath = 'image';

        $profilepicture->move($destinationPath, $imagename); 

    }

      $data->companyname = $companyname;
      $data->password = $password;
      $data->user_contact = $user_contact;
      $data->user_email = $user_email;
      $data->companyregisternumber = $companyregisternumber;
      $data->companydesc = $companydesc;
      $data->address = $address;
      $data->job_title = $job_title;
      $data->profilepicture = $imagename;
      $data->personincharge = $personincharge;
      $data->phonenumber = $phonenumber;
      $data->status =$status;
      $data->save();

      return response()->json('Company Updated');
    }
       
       }



       public function block (Request $request){

        $user_id = $request->input('user_id');

        $data = User::where('user_id',$user_id)->first();
        $data->status = 'blocked';
        $data->save();

 
        $messages = 'Dear '. $data->user_fname. ', your account has been blocked due to inappropiate behaviour';
        Mail::raw( $messages ,function ($message) use($data)
          {
           $message->to($data->user_email);
           $message->from('testemaillumen123@gmail.com', 'Admin of W2W');
           $message->subject('Account Management');


           }); 


        return response()->json('user blocked');


       }

       public function unblock(Request $request){

        $user_id = $request->input('user_id');

        $data = User::where('user_id',$user_id)->first();
        $data->status = 'active';
        $data->save();

        $messages = 'Dear '. $data->user_fname. ', your account has been unblocked';
        Mail::raw( $messages ,function ($message) use($data)
          {
           $message->to($data->user_email);
           $message->from('testemaillumen123@gmail.com', 'Admin of W2W');
           $message->subject('Account Management');


           }); 


        return response()->json('user unblocked');

       }
    

    public function destroy(Request $request){

        $user_id = $request->input('user_id');
        $data = User::where('user_id',$user_id)->first();
        $data->delete();
    
        return response()->json('User deleted');
    }


    public function addpackage(Request $request)
    {
        $user_id = $request->input('user_id');
        $package_name = $request->input('package_name'); 


        $data = User::where('user_id',$user_id)->first();
        $package = Package::where('package_name',$package_name)->first();


        $data->package_id = $package->package_id;
        $data->package_limit = $package->package_limit;
        $data->save();

        
        return response()->json('package added');


    }


  
}
