<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\User;

class LoginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function signin(Request $request)
    {
       
         
        $email = $request->input('user_email');
        $password = $request->input('password');

 

         $user = User::where('user_email', $email)->where('password', $password)->first(); 
 
             if ($user == null) {
                return response()->json('User wasnt existed' );
            }

            else 
              {  if($user->user_role == 'admin'){
                       

                       $finalArray = array();  
                       $data = user::all();
                      
                       foreach ($data as $data) {

                        $public = rtrim(app()->basePath('public/image'), '/');
                        $imagename = $data->profilepicture;
                        $dirfile = $public.'/' . $imagename;
                        
                 
            
                        $tempArray = [
                            'id' => $data->user_id,
                            'firstname' => $data->user_fname,
                            'lastname' => $data->user_lname,
                            'companyname' => $data->companyname,
                            'password' => $data->password,
                            'contactnumber' => $data->user_contact,
                            'email' => $data->user_email,
                            'companyregisternumber' => $data->companyregisternumber,
                            'companydesc' => $data->companydesc,
                            'type' => $data->user_type,
                            'profilepicture' => $dirfile,
                            'role' => $data->user_role,
            
                        ];
                    
                        array_push($finalArray, $tempArray);
            
                    }
                    
                        return response()->json($finalArray); 

                       

                }
            }
        
    

                if($user->user_role == 'user')

                {
 
                      if($user->user_type == 'individual')
                      {

                        $finalArray = array();
                      

  
                          $public = rtrim(app()->basePath('public/image'), '/');
                          $imagename = $user->profilepicture;
                          $dirfile = $public.'/' . $imagename;
                          

                          
                          $finalArray = [
                              'id' => $user->user_id,
                             'firstname'=>$user->user_fname,
                             'lastname'=>$user->user_lastname,
                             'password'=>$user->password,
                             'contactnumber'=>$user->user_contact,
                             'email'=>$user->user_email,
                             'profilepicture'=>$dirfile,


                           ];

 

                           return response()->json($finalArray);


                      }

                    

                       if($user->user_type == 'company')
                      {
                      
                        $compArray = array();

                        $public = rtrim(app()->basePath('public/image'), '/');
                        $imagename = $user->profilepicture;
                        $dirfile = $public.'/' . $imagename;

                        $compArray = [
                              'id' => $user->user_id,
                              'company name'=>$user->companyname,
                              'password'=>$user->password,
                              'contactnumber'=>$user->user_contact,
                              'email'=>$user->user_email,
                              'companyregisternumber'=>$user->companyregisternumber,
                              'description'=>$user->companydesc,
                              'profilepicture'=>$dirfile,
                          ];

                          return response()->json($compArray);
                      } 

                    }

                    }
                }

                
             
            


     


        


    

