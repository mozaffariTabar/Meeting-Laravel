<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use View;
use Auth;
use DB;
use App\User;
use App\Meeting;
use App\Guest;

class usersController extends Controller
{
    public function login (Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'phone' => 'required|min:11|max:11|regex:/(09)[0-9]{9}/'
            ]);
            
        if ($validator->fails()) return back()->withInput($request->all())->withErrors($validator->messages());
           
        $user = DB::table('users')->where('email','=',$request->email)->orWhere('phone','=',$request->phone)->first();

        if ($user) {
            return view('user')
                ->withMeetings(Meeting::where('host_id',$user->id)->orderBy('meeting_id',"DESC")->get())
                ->withGuests(
                    DB::table('guests')
                    ->select('users.email','guests.id','guests.guest_id','guests.answer','guests.meeting_id','meetings.title')
                    ->join('users','users.id','=','guests.guest_id')
                    ->join('meetings','guests.meeting_id','=','meetings.meeting_id')
                    ->where('guest_id','=',$user->id)
                    ->orWhere('guests.host_id','=',$user->id)
                    ->get()
                )
                ->withUsers(User::select('id','email')->where('id','!=',$user->id)->get())
                ->withHost($user);
        } else {
            return back()->withErrors('Use another email or phone');
        }
    }

    public function create (Request $request) {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'phone' => 'required|min:11|max:11|regex:/(09)[0-9]{9}/',
            're-phone' => 'required|same:phone',
            ]);
            
        if ($validator->fails()) return back()->withInput($request->all())->withErrors($validator->messages());
           
        $user = DB::table('users')->where('email','=',$request->email)->orWhere('phone','=',$request->phone)->first();

        if (!$user) {
            User::create([
                'email' => $request->email,
                'phone' => $request->phone
            ]);
            return view('user')
                ->withMeetings([])
                ->withGuests([])
                ->withUsers(User::select('id','email')->where('email','!=',$request->email)->get())
                ->withHost(User::select('id','email')->where('email',$request->email)->get()->first());
        } else {
            return back()->withErrors('Use another email or phone');
        }
    }

    public function photo (Request $request) {
        
        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpg'
        ]);
            
        $image = $request->file('photo');
        $input['imagename'] = $request->id.'.jpg';
        $image->move(public_path('/img/users'), $input['imagename']);
        
        return view('user')
            ->withMeetings(Meeting::where('host_id',$request->id)->orderBy('meeting_id',"DESC")->get())
            ->withGuests(
                DB::table('guests')
                ->select('users.email','guests.id','guests.guest_id','guests.answer','guests.meeting_id','meetings.title')
                ->join('users','users.id','=','guests.guest_id')
                ->join('meetings','guests.meeting_id','=','meetings.meeting_id')
                ->where('guest_id','=',$request->id)
                ->orWhere('guests.host_id','=',$request->id)
                ->get()
            )
            ->withUsers(User::select('id','email')->where('id','!=',$request->id)->get())
            ->withHost(User::select('id','email')->where('id',$request->id)->get()->first());
    }

}
