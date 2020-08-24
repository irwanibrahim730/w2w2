<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Product;
use Carbon\Carbon;
use DB;

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

        $listexpired = DB::table('products')
                ->where('publishstatus','yes')
                ->whereDate('expired_at', '<', Carbon::now()->toDateString())
                ->get();
     
        //update publishstatus (yes);
        if($listexpired){

          foreach($listexpired as $data){

             $product = Product::where('product_id',$data->product_id)->first();

             $product->publishstatus = 'no';
             $product->save();

          }

        }

       


         $user = User::where('user_email', $email)->where('password', $password)->first(); 
 
             if ($user == null) {
                return response()->json(['status'=>'failed','value'=>'User wasnt existed']);
            }

            if ($user->status == 'blocked' ) { 
                return response()->json(['status'=>'failed','value'=>'User blocked']);
            }

            if ($user->emailverification == 'process') {
                return response()->json(['status'=>'failed','value'=>'please verifi your email first']);
            }

            else 
              {
                if($user->status == 'active') {
                
                if($user->user_role == 'admin' || $user->user_role == 'superadmin'){
                       

                       $finalArray = array();  
                  

                       $url = 'https://codeviable.com/w2w2/public/image';
                       $imagename = $user->profilepicture;
                       $public =  $url .'/'. $imagename;
                        
                 
            
                        $tempArray = [
                            'user_id' => $user->user_id,
                            'user_fname' => $user->user_fname,
                            'user_lname' => $user->user_lname,
                            'companyname' => $user->companyname,
                            'password' => $user->password,
                            'user_contact' => $user->user_contact,
                            'user_email' => $user->user_email,
                            'companyregisternumber' => $user->companyregisternumber,
                            'companydesc' => $user->companydesc,
                            'user_type' => $user->user_type,
                            'profilepicture' => $public,
                            'user_role' => $user->user_role,

            
                        ];
                    
                        array_push($finalArray, $tempArray);
                        return response()->json(['status'=>'success','value'=>$finalArray]);
            
                    }
                    
                        

                       

                
            
        
    

                if($user->user_role == 'user')

                {
 
                      if($user->user_type == 'individual')
                      {

                        $finalArray = array();
                      

  
                        $url = 'https://codeviable.com/w2w2/public/image';
                        $imagename = $user->profilepicture;
                        $public =  $url .'/'. $imagename;
                          

                          
                          $tempArray = [
                             'user_id' => $user->user_id,
                             'user_fname'=>$user->user_fname,
                             'user_lname'=>$user->user_lname,
                             'password'=>$user->password,
                             'contactnumber'=>$user->user_contact,
                             'email'=>$user->user_email,
                             'profilepicture'=>$public,
                             'user_type' =>$user->user_type,
                             'personincharge' => $user->personincharge,
                             'phonenumber' => $user->phonenumber,
                             'user_type' => $user->user_type,
                             'created_at' => $user->created_at,
                             'user_role' => $user->user_role,
                             'balance_token' => $user->balancetoken,


                           ];
                           array_push($finalArray, $tempArray);
 
                           return response()->json(['status'=>'success','value'=>$finalArray]);


                      }

                    

                       if($user->user_type == 'company')
                      {
                      
                        $compArray = array();

                        $url = 'https://codeviable.com/w2w2/public/image';
                        $imagename = $user->profilepicture;
                        $public =  $url .'/'. $imagename;

                        $tempArray = [
                              'user_id' => $user->user_id,
                              'companyname'=>$user->companyname,
                              'password'=>$user->password,
                              'user_contact'=>$user->user_contact,
                              'user_email'=>$user->user_email,
                              'companyregisternumber'=>$user->companyregisternumber,
                              'companydesc'=>$user->companydesc,
                              'profilepicture'=>$public,
                              'user_type' =>$user->usertype,
                              'personincharge' => $user->personincharge,
                              'phonenumber' => $user->phonenumber,
                              'user_type' => $user->user_type,
                              'created_at' => $user->created_at,
                              'user_role' => $user->user_role,
                              'balance_token' => $user->balancetoken,
                          ];

                          array_push($compArray, $tempArray);

                          return response()->json(['status'=>'success','value'=>$compArray]);
                      } 

                    }

                    }
                  }
                }
            }
        

                

                
             
            


     


        


    

