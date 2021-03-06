<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailer;
use App\User;
use App\Package;
use App\Userpack;
use App\History;
use App\Notification;
use App\Log;


class UserController extends Controller
{

    public function index(Request $request){

        $type = $request->input('type');

        if($type){
            $user = User::where('user_type',$type)->get();
        } else {
            $user = User::all();
        }

        $finalArray = array();

        foreach ($user as $data) {

            $url = 'https://codeviable.com/w2w2/public/image';
            $imagename = $data->profilepicture;
            $public =  $url .'/'. $imagename;

                 

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
                'state' => $data->state,
                'occupation' => $data->occupation,
                'job_title' => $data->job_title,
                'user_type' => $data->user_type,
                'profilepicture' => $public,
                'user_role' => $data->user_role,
                'package_id' => $data->package_id,
                'package_limit' => $data->package_limit,
                'personincharge' => $data->personincharge,
                'phonenumber' => $data->phonenumber,
                'created_at' =>$data->created_at,
                'updated_at' =>$data->updated_at,
                'status' => $data->status,
                'balancetoken' => $data->balancetoken,
                'activitylog' => $data->activitylog,

			];
        
			array_push($finalArray, $tempArray);

        }
            return response()->json(['status'=>'success','value'=>$finalArray]);
    
}

    public function show(Request $request){
        
        $user_id = $request->input('user_id');

        $data = User::find($user_id);

        if ($data == null) {

            return response()->json(['status'=>'error','value'=>'data not exist']);
        
         } else {
      
            $url = 'https://codeviable.com/w2w2/public/image';
            $imagename = $data->profilepicture;
            $public =  $url .'/'. $imagename;

             
            
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
                'state' => $data->state,
                'occupation' => $data->occupation,
                'job_title' => $data->job_title,
                'user_type' => $data->user_type,
                'profilepicture' => $public,
                'user_role' => $data->user_role,
                'package_id' => $data->package_id,
                'package_limit' => $data->package_limit,
                'personincharge' => $data->personincharge,
                'phonenumber' => $data->phonenumber,
                'created_at' =>$data->created_at,
                'updated_at' =>$data->updated_at,
                'status' => $data->status,
                'balancetoken' => $data->balancetoken,
                'activitylog' => $data->activitylog,

             ];

             return response()->json(['status'=>'success','value'=>$tempArray]);
       
            }

    }
    

    public function store (Request $request){

        $validator = \validator::make($request->all(),
        [
            'password' => 'required|min:8',
            'user_email' => 'required',
            
        ]);

        if ($validator->fails()) {

			return response()->json($validator->errors(), 422);

        }else {
            
            $user_fname = $request->input('user_fname');
            $user_lname = $request->input('user_lname');
            $companyname = $request->input('companyname');
            $password = $request->input('password');
            $user_contact = $request->input('user_contact');
            $user_email = $request->input('user_email');
            $companyregisternumber = $request->input('companyregisternumber');
            $companydesc = $request->input('companydesc');
            $address = $request->input('address');
            $state = $request->input('state');
            $occupation = $request->input('occupation');
            $job_title = $request->input('job_title'); 
            $user_type = $request->input('user_type');     
            $profilepicture = $request->file('profilepicture');
            $personincharge = $request->input('personincharge');
            $phonenumber = $request->input('phonenumber');
            $user_role = $request->input('user_role');

            if($request->hasfile('profilepicture')){
                $extention = $profilepicture->getClientOriginalExtension();
                $imagename = rand(11111, 99999) . '.' . $extention;
                $destinationPath = 'image';
        
                $profilepicture->move($destinationPath, $imagename);
            }

            else if ($profilepicture == null) {
                $imagename = null;

            }

            $userExist = User::where('user_email',$user_email)->first();

            if($userExist){
                return response()->json(['status'=>'failed','value'=>'email is exist']);
            } else {

                $emailverification = 'process';
                $encoded = str_rot13($password);

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
                $data->state = $state;
                $data->occupation = $occupation;
                $data->job_title = $job_title;
                $data->user_type = $user_type;
                $data->profilepicture=$imagename;
                $data->user_role = $user_role;
                $data->personincharge = $personincharge;
                $data->phonenumber = $phonenumber;
                $data->status = 'active';
                $data->emailverification = $emailverification;
               
                if($data->user_fname == null){
                    $sendname = $data->companyname;
                } else {
                    $sendname = $data->user_fname;
                }
                //$tempmessages = 'Dear '.$sendname.', '.' success register to Eco Waster Market Please Verify Your Email Before Using Our System';
                $tempmessages = 'Dear ' .$sendname.', '.' Thank you for registration to Eco Waste Market, please click link below to verify you email';
                $link = 'https://codeviable.com/w2w2/public/ver12asdaasaas/verabcsadasdsdfss?id='.$user_email;

                $messages = $tempmessages ."\n".$link;

                Mail::raw( $messages ,function ($message) use($data)
                {
                 $message->to($data->user_email);
                 $message->from('w2wmasteradm@gmail.com', 'W2W Master Admin Waste2Wealth');
                 $message->subject('EcoWaste Market');
      
      
                 }); 

                 $data->save();
            
                return response()->json(['status'=>'success','value'=>'use has been registered']);

            }
         }
    }


    public function update(Request $request)
    {

        //start

        $user_id = $request->input('user_id');
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
        $remember_token = $request->input('remember_token');
        $user_role = $request->input('user_role');
        $personincharge = $request->input('personincharge');
        $phonenumber = $request->input('phonenumber');
        $status = $request->input('status');
        $balancetoken = $request->input('balancetoken');
        $state = $request->input('state');
        
        $userdetails = User::where('user_id',$user_id)->first();
        if($userdetails){

            if($user_fname == null){
                $user_fname = $userdetails->user_fname;
            }
            if($user_lname == null){
                $user_lname = $userdetails->user_lname;
            }
            if($companyname == null){
                $companyname = $userdetails->companyname;
            }
            if($password == null){
                $password = $userdetails->password;
            }
            if($user_contact == null){
                $user_contact = $userdetails->user_contact;
            }
            if($user_email == null){
                $user_email = $userdetails->user_email;
            }
            if($companyregisternumber == null){
                $companyregisternumber = $userdetails->companyregisternumber;
            }
            if($companydesc == null){
                $companydesc = $userdetails->companydesc;
            }
            if($address == null){
                $address = $userdetails->address;
            }
            if($occupation == null){
                $occupation = $userdetails->occupation;
            }
            if($job_title == null){
                $job_title = $userdetails->job_title;
            }
            if($user_type == null){
                $user_type = $userdetails->user_type;
            }
            if($remember_token == null){
                $remember_token = $userdetails->remember_token;
            }
            if($user_role == null){
                $user_role = $userdetails->user_role;
            }
            if($personincharge == null){
                $personincharge = $userdetails->personincharge;
            }
            if($phonenumber == null){
                $phonenumber = $userdetails->phonenumber;
            }
            if($status == null){
                $status = $userdetails->status;
            }
            if($balancetoken == null){
                $balancetoken = $userdetails->balancetoken;
            }
            if($state == null){
                $state = $userdetails->state;
            }

            if ($profilepicture == null) {
                $imagename = $userdetails->profilepicture;
            } else {
    
                $extention = $profilepicture->getClientOriginalExtension();
                $imagename = rand(11111, 99999) . '.' . $extention;
                $destinationPath = 'image';
        
                $profilepicture->move($destinationPath, $imagename); 
    
            }

            $userdetails->user_fname = $user_fname;
            $userdetails->user_lname = $user_lname;
            $userdetails->companyname = $companyname;
            $userdetails->password = $password;
            $userdetails->user_contact = $user_contact;
            $userdetails->user_email = $user_email;
            $userdetails->companyregisternumber = $companyregisternumber;
            $userdetails->companydesc = $companydesc;
            $userdetails->address = $address;
            $userdetails->occupation = $occupation;
            $userdetails->job_title = $job_title;
            $userdetails->user_type = $user_type;
            $userdetails->profilepicture = $imagename;
            $userdetails->remember_token = $remember_token;
            $userdetails->user_role = $user_role;
            $userdetails->personincharge = $personincharge;
            $userdetails->phonenumber = $phonenumber;
            $userdetails->status = $status;
            $userdetails->balancetoken = $balancetoken;
            $userdetails->state = $state;

            $userdetails->save();

            return response()->json(['status'=>'success','value'=>'success update user']);


        } else {
            return response()->json(['status'=>'failed','value'=>'sorry user not exist']);
        }

        //end
        
//         $user_id = $request->input('user_id');

//         $data = User::where('user_id',$user_id)->first();
        
//         $user_fname = $request->input('user_fname');
//         $user_lname = $request->input('user_lname');
//         $companyname = $request->input('companyname');
//         $password = $request->input('password');
//         $user_contact = $request->input('user_contact');
//         $user_email = $request->input('user_email');
//         $companyregisternumber = $request->input('companyregisternumber');
//         $companydesc = $request->input('companydesc');
//         $address = $request->input('address');
//         $state = $request->input('state');
//         $occupation = $request->input('occupation');
//         $job_title = $request->input('job_title');
//         $profilepicture = $request->file('profilepicture'); 
//         $personincharge = $request->input('personincharge');
//         $phonenumber = $request->input('phonenumber');
//         $status = $request->input('status');
//         $user_role = $request->input('user_role');


//         if($data->user_role == 'admin'){
        
//             if ($user_fname == null) {
//                 $user_fname = $data->user_fname;
//             }
    
//             if ($user_role == null) {
//                 $user_role = $data->user_role;
//             }
    
//             if ($user_lname == null) {
//                 $user_lname= $data->user_lname;
//             }
    
//             if ($password == null) {
//             $password = $data->password;
//             }
    
//             if ($user_contact == null) {
//             $user_contact= $data->user_contact;
//             }
    
//             if ($user_email == null) {
//             $user_email = $data->user_email;
//             }
    
//             if ($address == null) {
//                 $address = $data->address;
//                 }

//              if ($state == null) {
//                     $state = $data->state;
//                     }
    
//             if ($occupation == null) {
//                     $occupation = $data->occupation;
//                     }
//             if ($personincharge == null) {
//                     $personincharge = $data->personincharge;
//                     }
                
//             if ($phonenumber == null) {
//                     $phonenumber = $data->phonenumber;
//                     }
    
//                     if ($status == null) {
//                         $status = $data->status;
//                         }
    
//              if ($profilepicture == null) {
//                 $imagename = $data->profilepicture;
//             } else {
    
//                 $extention = $profilepicture->getClientOriginalExtension();
//                 $imagename = rand(11111, 99999) . '.' . $extention;
//                 $destinationPath = 'image';
        
//                 $profilepicture->move($destinationPath, $imagename); 
    
//             }
         
//             $data->user_fname = $user_fname;
//             $data->user_lname = $user_lname;
//             $data->password = $password;
//             $data->user_contact = $user_contact;
//             $data->user_email = $user_email;
//             $data->address = $address;
//             $data->state = $state;
//             $data->occupation = $occupation;
//             $data->profilepicture = $imagename;
//             $data->personincharge = $personincharge;
//             $data->phonenumber = $phonenumber;
//             $data->status =$status;
//             $data->user_role = $user_role;
//             $data->save();
  
//             return response()->json(['status'=>'success','value'=>'admin updated']);
    
//           }

//       if($data->user_type == 'individual'){
        
//         if ($user_fname == null) {
//             $user_fname = $data->user_fname;
//         }

//         if ($user_role == null) {
//             $user_role = $data->user_role;
//         }

//         if ($user_lname == null) {
//             $user_lname= $data->user_lname;
//         }

//         if ($password == null) {
//         $password = $data->password;
//         }

//         if ($user_contact == null) {
//         $user_contact= $data->user_contact;
//         }

//         if ($user_email == null) {
//         $user_email = $data->user_email;
//         }

//         if ($address == null) {
//             $address = $data->address;
//             }

//             if ($state == null) {
//                 $state = $data->state;
//                 }
        

//         if ($occupation == null) {
//                 $occupation = $data->occupation;
//                 }
//         if ($personincharge == null) {
//                 $personincharge = $data->personincharge;
//                 }
            
//         if ($phonenumber == null) {
//                 $phonenumber = $data->phonenumber;
//                 }

//                 if ($status == null) {
//                     $status = $data->status;
//                     }

//          if ($profilepicture == null) {
//             $imagename = $data->profilepicture;
//         } else {

//             $extention = $profilepicture->getClientOriginalExtension();
//             $imagename = rand(11111, 99999) . '.' . $extention;
//             $destinationPath = 'image';
    
//             $profilepicture->move($destinationPath, $imagename); 

//         }
     
//         $data->user_fname = $user_fname;
//         $data->user_lname = $user_lname;
//         $data->password = $password;
//         $data->user_contact = $user_contact;
//         $data->user_email = $user_email;
//         $data->address = $address;
//         $data->state = $state;
//         $data->occupation = $occupation;
//         $data->profilepicture = $imagename;
//         $data->personincharge = $personincharge;
//         $data->phonenumber = $phonenumber;
//         $data->status =$status;
//         $data->user_role = $user_role;
//         $data->save();

//         return response()->json(['status'=>'success','value'=>'User Updated']);

//       }

//       if($data->user_type == 'company'){
        
//         if ($companyname == null) {
//             $companyname = $data->companyname;
//         }
//         if ($password == null) {
//             $password= $data->password;
//         }

//         if ($user_contact == null) {
//             $user_contact = $data->user_contact;
//         }

//         if ($user_email == null) {
//         $user_email= $data->user_email;
//         }

//         if ($companyregisternumber == null) {
//         $companyregisternumber = $data->companyregisternumber;
//         } 
      
//        if($companydesc == null){
//        $companydesc = $data->companydesc;
//       }

//       if($address == null){
//         $address = $data->address;
//        }

//        if ($state == null) {
//         $state = $data->state;
//         }

//        if($job_title == null){
//         $job_title = $data->job_title;
//        }

//        if ($personincharge == null) {
//         $personincharge = $data->personincharge;
//         }
    
// if ($phonenumber == null) {
//         $phonenumber = $data->phonenumber;
//         }

//         if ($status == null) {
//             $status = $data->status;
//             }

//       if ($profilepicture == null) {
//         $imagename = $data->profilepicture;
//     } else {

//         $extention = $profilepicture->getClientOriginalExtension();
//         $imagename = rand(11111, 99999) . '.' . $extention;
//         $destinationPath = 'image';

//         $profilepicture->move($destinationPath, $imagename); 

//     }

//       $data->companyname = $companyname;
//       $data->password = $password;
//       $data->user_contact = $user_contact;
//       $data->user_email = $user_email;
//       $data->companyregisternumber = $companyregisternumber;
//       $data->companydesc = $companydesc;
//       $data->address = $address;
//       $data->state = $state;
//       $data->job_title = $job_title;
//       $data->profilepicture = $imagename;
//       $data->personincharge = $personincharge;
//       $data->phonenumber = $phonenumber;
//       $data->status =$status;
//       $data->save();

//       return response()->json(['status'=>'success','value'=>'Company Updated']);
//     }
       
}



       public function block (Request $request){

        $user_id = $request->input('user_id');
        $adminid = $request->input('adminid');


        $data = User::where('user_id',$user_id)->first();
        $data->status = 'blocked';
        $data->save();

        if($adminid){

            $log = new Log;
            $log->userid = $adminid;
            $log->description = 'block user '. $data->user_fname;

            $log->save();
        }

        $messages = 'Dear '.$data->user_fname.', '.' your account has been blocked';

        Mail::raw( $messages ,function ($message) use($data)
          {
           $message->to($data->user_email);
           $message->from('w2wmasteradm@gmail.com', 'W2W Master Admin Waste2Wealth');
           $message->subject('Account Management');


           }); 

        return response()->json(['status'=>'success','value'=>'user blocked']);

       }

       public function unblock(Request $request){

        $user_id = $request->input('user_id');
        $adminid = $request->input('adminid');

        $data = User::where('user_id',$user_id)->first();
        $data->status = 'active';
        $data->save();

        if($adminid){

            $log = new Log;
            $log->userid = $adminid;
            $log->description = 'unblock user '. $data->user_fname;

            $log->save();

        }

        $messages = 'Dear '.$data->user_fname.', '.' your account has been unblocked';

        Mail::raw( $messages ,function ($message) use($data)
          {
           $message->to($data->user_email);
           $message->from('w2wmasteradm@gmail.com', 'W2W Master Admin Waste2Wealth');
           $message->subject('Account Management');


           }); 

        return response()->json(['status'=>'success','value'=>'User unblocked']);

       }
    

    public function destroy(Request $request){

        $user_id = $request->input('user_id');
        $data = User::where('user_id',$user_id)->first();
        $data->delete();
    
        return response()->json('User deleted');
        return response()->json(['status'=>'success','value'=>'User deleted']);
    }


    public function addpackage(Request $request)
    {
        $user_id = $request->input('user_id');
        $package_id = $request->input('package_id'); 

        $package = Package::where('package_id',$package_id)->first();
        $user = User::where('user_id',$user_id)->first();

        $data = new Userpack;
        $data->user_id = $user_id;
        $data->package_id = $package->package_id;
        $data->limit = $package->package_limit;
        $data->save();

        $token = $user->balancetoken;
        $price = $package->package_price;
        $balance = $token - $price;
        $user->balancetoken = $balance;
        $user->save();

        $history = new History;
        $history->user_id = $user_id;
        $history->type = 'package';
        $history->name = $package->package_name;
        $history->save();

        return response()->json(['status'=>'success','value'=>'package added']);


    }

    public function verificationemail(Request $request){

        $id = $request->input('id');

        $user = User::where('user_email',$id)->first();

        if($user){

            $verification = 'success';
            $user->emailverification = $verification;

            //email config
            $messages = 'Thank you, your registration is activated';

            Mail::raw( $messages ,function ($message) use($user){

                $message->to($user->user_email);
                $message->from('w2wmasteradm@gmail.com', 'W2W Master Admin Waste2Wealth');
                $message->subject('EcoWaste Market');

            });

            //notification config
            $notification = new Notification;
            $notification->user_id = $user->user_id;
            $notification->type = 'registration';
            $notification->status = 'success';

            $user->save();
            $notification->save();

            return redirect()->to('https://codeviable.com/w2w/signin.html');

        }else {
            return response()->json(['status'=>'failed','value'=>'user not exist']);
        }

    }

    public function resendverification(Request $request){

        $email = $request->input('email');

        $user = User::where('user_email',$email)->first();

        if($user){

            $tempmessages = 'Dear '.$user->user_fname.', '.' success register to Eco Waster Market Please Verify Your Email Before Using Our System';
            $link = 'https://codeviable.com/w2w2/public/ver12asdaasaas/verabcsadasdsdfss?id='.$email;

            $messages = $tempmessages ." ".$link;

            Mail::raw( $messages ,function ($message) use($user)
                {

                 $message->to($user->user_email);
                 $message->from('w2wmasteradm@gmail.com', 'W2W Master Admin Waste2Wealth');
                 $message->subject('EcoWaste Market');
      
                 }); 
            
            return response()->json(['status'=>'success']);
        } else {
            return response()->json(['status'=>'failed','value'=>'user not exist']);
        }

    }

    public function resetpassword(Request $request){

        $validator = \validator::make($request->all(),
        [
            'email' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        } else {

            $email = $request->input('email');

            $user = User::where('user_email',$email)->first();

            if($user){

                $password = 'gnasd@123';

                $user->password = $password;
                $user->save();

                $tempmessages = 'Please use default password to access the system and update your password';
                $desc = 'your default password : ';
                $messages = $tempmessages ."\n". $desc .$password;

                Mail::raw( $messages ,function ($message) use($user)
                    {

                    $message->to($user->user_email);
                    $message->from('w2wmasteradm@gmail.com', 'W2W Master Admin Waste2Wealth');
                    $message->subject('EcoWaste Market');
        
                    }); 

                return response()->json(['status'=>'success','value'=>'success reset password']);
            } else {
                return response()->json(['status'=>'success','value'=>'user not exist']);
            }

        }

    }
  
}
