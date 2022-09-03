<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;

class AdminController extends Controller
{
    function index(){
//dd('2022-09-03 17:02:07'   ==   Carbon::parse('today')->format('Y-m-d') );
        $new_users = User::whereDate('created_at' ,  Carbon::parse('today')->format('Y-m-d'))->where([['type' , 1], ['is_active', 1 ]])->count();
        $all_users = User::where([['type' , '1'], ['is_active', 1 ]])->count();
        $blocked_users = User::where( 'type' , '1' )->where( 'is_active', 0 )->count();
        $admins = User::where('type' , '0')->count();

        return view('dashboards.admins.index', compact('new_users'  , 'all_users'  , 'blocked_users' , 'admins') );
       }

       function profile(){
           return view('dashboards.admins.profile');
       }
       function users(){
        $users = User::where('type' , '1')->get();
           return view('dashboards.admins.users' , compact('users'));
       }

       function updateInfo(Request $request){

               $validator = \Validator::make($request->all(),[
                   'name'=>'required',
                   'email'=> 'required|email|unique:users,email,'.Auth::user()->id,
                   'favoritecolor'=>'required',
               ]);

               if(!$validator->passes()){
                   return response()->json(['status'=>0,'error'=>$validator->errors()->toArray()]);
               }else{
                    $query = User::find(Auth::user()->id)->update([
                         'name'=>$request->name,
                         'email'=>$request->email,
                         'favoriteColor'=>$request->favoritecolor,
                    ]);

                    if(!$query){
                        return response()->json(['status'=>0,'msg'=>'Something went wrong.']);
                    }else{
                        return response()->json(['status'=>1,'msg'=>'Your profile info has been update successfuly.']);
                    }
               }
       }

       function updatePicture(Request $request){
           $path = 'users/images/';
           $file = $request->file('admin_image');
           $new_name = 'UIMG_'.date('Ymd').uniqid().'.jpg';

           //Upload new image
           $upload = $file->move(public_path($path), $new_name);

           if( !$upload ){
               return response()->json(['status'=>0,'msg'=>'Something went wrong, upload new picture failed.']);
           }else{
               //Get Old picture
               $oldPicture = User::find(Auth::user()->id)->getAttributes()['picture'];

               if( $oldPicture != '' ){
                   if( \File::exists(public_path($path.$oldPicture))){
                       \File::delete(public_path($path.$oldPicture));
                   }
               }

               //Update DB
               $update = User::find(Auth::user()->id)->update(['picture'=>$new_name]);

               if( !$upload ){
                   return response()->json(['status'=>0,'msg'=>'Something went wrong, updating picture in db failed.']);
               }else{
                   return response()->json(['status'=>1,'msg'=>'Your profile picture has been updated successfully']);
               }
           }
       }


       function changePassword(Request $request){
           //Validate form
           $validator = \Validator::make($request->all(),[
               'oldpassword'=>[
                   'required', function($attribute, $value, $fail){
                       if( !\Hash::check($value, Auth::user()->password) ){
                           return $fail(__('The current password is incorrect'));
                       }
                   },
                   'min:8',
                   'max:30'
                ],
                'newpassword'=>'required|min:8|max:30',
                'cnewpassword'=>'required|same:newpassword'
            ],[
                'oldpassword.required'=>'Enter your current password',
                'oldpassword.min'=>'Old password must have atleast 8 characters',
                'oldpassword.max'=>'Old password must not be greater than 30 characters',
                'newpassword.required'=>'Enter new password',
                'newpassword.min'=>'New password must have atleast 8 characters',
                'newpassword.max'=>'New password must not be greater than 30 characters',
                'cnewpassword.required'=>'ReEnter your new password',
                'cnewpassword.same'=>'New password and Confirm new password must match'
            ]);

           if( !$validator->passes() ){
               return response()->json(['status'=>0,'error'=>$validator->errors()->toArray()]);
           }else{

            $update = User::find(Auth::user()->id)->update(['password'=>\Hash::make($request->newpassword)]);

            if( !$update ){
                return response()->json(['status'=>0,'msg'=>'Something went wrong, Failed to update password in db']);
            }else{
                return response()->json(['status'=>1,'msg'=>'Your password has been changed successfully']);
            }
           }
       }


    public function manage_block($id)
    {
        $user =  User::findOrFail($id);
        if ($user->is_active == 1) {
            $is_active = 0;
        }else{
            $is_active = 1;
        }
        $user->update(['is_active'=>$is_active]);

        return back()->with('successMsg',"change status to $is_active successfully");
    }
    // destroy
    public function destroy($id)
    {
        $user =  User::findOrFail($id);
        $user->subscriptions()->delete();
        $user->delete();
        return back()->with('successMsg','deleted succfully');
    }


    // index
    public function search(Request $request)
    {
        $search_word= $request->searchword;
        $users = User::where([['name', 'like', '%' . $search_word . '%'],['type','1']])->orWhere([['email', 'like', '%' . $search_word . '%'],['type','1']])->get();
        return view('dashboards.admins.users',compact('users','search_word'));
    }


}
