<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Enquiry;
use App\Notification;
use App\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailer;

class EnquiryController extends Controller

{
    public function store(Request $request)
    {
        

        $name = $request->input('name');
        $email = $request->input('email');
        $category = $request->input('category');
        $description = $request->input('description');

        $data = new Enquiry;
        $data->name = $name;
        $data->email = $email;
        $data->category = $category;
        $data->description = $description;

        $notify = new Notification;
        $notify->email = $email;
        $notify->status = 'new enquiry';
        $notify->type = 'enquiry';

        //get admin email user and pass to them
        $user = User::where('user_role','admin')->get();

        $emailuser = $email;

        $tempmessages = 'Hi admin, there is one enquiry receive from the system, the details are as stated below :';
        $tempemail = 'enquiry email :';
        $tempdesc = 'enquiry content :';
        $tempname = 'enquiry name :';
        $messages = $tempmessages ."\n". $tempname.$name ."\n". $tempemail.$emailuser ."\n". $tempdesc.$description;

        foreach($user as $data){

            Mail::raw( $messages , function ($message) use ($data){

                $message->to($data->user_email);
                $message->from('hafizaldevtest@gmail.com', 'muhamad ijal');
                $message->subject('EcoWaste Market');

            });

        }

        $data->save(); 
        $notify->save();

        return response()->json(['status'=>'success']);

    }


    public function list()
    {
        
           $enquiry = Enquiry::all();
           $finalArray = array();  

           foreach ($enquiry->sortByDesc('created_at') as $enquiries){
          
            $tempArray = [
              'id' => $enquiries->id,
              'name' => $enquiries->name,
              'email' => $enquiries->email,
              'category'=> $enquiries->category,
              'description'=> $enquiries->description,
              'created_at' => $enquiries->created_at->format('d M Y - H:i:s'),
              'updated_at' => $enquiries->updated_at->format('d M Y - H:i:s'),
          ];

           array_push($finalArray,$tempArray);
         }

         return response()->json(['status'=>'success','value'=>$finalArray]);
    

        }

     public function detail(Request $request)
     {
         $id = $request->input('id');

         $enquiry = Enquiry::where('id',$id)->first();

         return response()->json(['status'=>'success','value'=>$enquiry]);

     }
      
    


public function delete(Request $request)
{
    $id = $request->input('id');

    $comments = Enquiry::where('id',$id)->first();
    $comments->delete();

    return response()->json(['status'=>'success']);



}


}