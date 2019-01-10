<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Meeting;
use App\Guest;
use App\User;
use DB;

class meetingController extends Controller
{
    public function define (Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:1'
            ]);
            
        if ($validator->fails()) return back();
        
        $meeting = Meeting::where('host_id',$request->id)->where('title',$request->title)->first();

        if (!$meeting) {
            Meeting::create([
                'host_id' => (int) $request->id,
                'title' => $request->title
            ]);
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
        } else {
            return back();
        }
    }

    public function send (Request $request) {
        
        $guest = Guest::where('guest_id',$request->guest_id)->where('host_id',$request->host_id)->where('meeting_id',$request->meeting_id)->first();
        
        if (!$guest) {
            Guest::create([
                'guest_id' => $request->guest_id,
                'host_id' => $request->host_id,
                'meeting_id' => $request->meeting_id
            ]);
        }
        return view('user')
            ->withMeetings(Meeting::where('host_id',$request->host_id)->orderBy('meeting_id',"DESC")->get())
            ->withGuests(
                DB::table('guests')
                ->select('users.email','guests.id','guests.guest_id','guests.answer','guests.meeting_id','meetings.title')
                ->join('users','users.id','=','guests.guest_id')
                ->join('meetings','guests.meeting_id','=','meetings.meeting_id')
                ->where('guest_id','=',$request->host_id)
                ->orWhere('guests.host_id','=',$request->host_id)
                ->get()
            )
            ->withUsers(User::select('id','email')->where('id','!=',$request->host_id)->get())
            ->withHost(User::select('id','email')->where('id',$request->host_id)->get()->first());
    }

    public function answer (Request $request) {
        
        foreach ($request->except('_token') as $key => $answer) {
            if ($key != 'host_id') 
                Guest::where('id',str_replace('invitation_','',$key))->update(['answer'=>$answer]);
        }

        return view('user')
        ->withMeetings(Meeting::where('host_id',$request->host_id)->orderBy('meeting_id',"DESC")->get())
        ->withGuests(
            DB::table('guests')
            ->select('users.email','guests.id','guests.guest_id','guests.answer','guests.meeting_id','meetings.title')
            ->join('users','users.id','=','guests.guest_id')
            ->join('meetings','guests.meeting_id','=','meetings.meeting_id')
            ->where('guest_id','=',$request->host_id)
            ->orWhere('guests.host_id','=',$request->host_id)
            ->get()
        )
        ->withUsers(User::select('id','email')->where('id','!=',$request->host_id)->get())
        ->withHost(User::select('id','email')->where('id',$request->host_id)->get()->first());

    }
}
