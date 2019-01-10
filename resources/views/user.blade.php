@extends('layouts.master')

@section('title')
    Users
@stop

@section('content')
    <div class="position-ref full-height">
        <div class="profile width">
            {{ Form::open(['url' => 'uploadPhoto', 'files' => true]) }}
                {{ Form::hidden('id', $host->id) }}
                @if (File::exists(public_path('img/users/'. $host->id .'.jpg')))
                    <img src="{{ asset('img/users/'. $host->id .'.jpg') }}">
                @else
                    <img src="{{ asset('img/users/default.jpg') }}">
                @endif
                <div>{{ $host->email }}</div>
                {{ Form::file('photo') }}
                {{Form::submit('Save', ['class' => 'btn btn-success'])}}
            {{ Form::close() }}
            <a href="/login">EXIT</a>
        </div>
        <div class="content width">
            <h1 class="m-b-md">Define Meeting</h1>
            @foreach ($errors->all() as $error)
                <p class="warning">
                    {{ $error }}
                </p>
            @endforeach
            {{ Form::open(['url' => 'addMeeting']) }}
            {{ Form::hidden('id', $host->id) }}
            {{ csrf_field() }}
                <p>
                    {{ Form::text("title","",["class" => "form-control", "placeholder" => "meeting name"]) }}
                </p>
                <p>
                    {{ Form::submit('Define',["class" => "btn btn-default"])}}
                </p>
            {{ Form::close() }}
        </div>
        <div class="content width">
            {{ Form::open(['url' => 'sendInvitation']) }}
            {{ Form::hidden('host_id', $host->id) }}
                <h1 class="m-b-md">Sending Invitation</h1>
                <p>
                    <select id="meeting_list" class="form-control" name="meeting_id">
                        @foreach ($meetings as $meeting)
                            <option value="{{ $meeting->meeting_id }}">
                                {{ $meeting->title }}
                            </option>
                        @endforeach
                    </select>
                </p>
                <p>
                    <select class="form-control" name="guest_id">
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->email }}
                            </option>
                        @endforeach
                    </select>
                </p>
            {{ Form::submit('Send',["class" => "btn btn-default"])}}
            {{ Form::close() }}
            <div class="table-kipper">
                <table id="guests_table" class="table">
                    <tbody>
                        @foreach ($guests as $guest)
                            @if ($guest->guest_id != $host->id)
                                <tr meetId="{{ $guest->meeting_id }}">
                                    <td>
                                        {{ $guest->email }}
                                    </td>
                                    @if ($guest->answer == -1)
                                        <td class="red">Sorry</td>
                                    @elseif($guest->answer == 0)
                                        <td>Pending</td>
                                    @elseif($guest->answer == 1)
                                        <td class="green">Comming</td>
                                    @endif
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="content width">
            {{ Form::open(['url' => 'answerInvitation']) }}
            {{ Form::hidden('host_id', $host->id) }}
                <h1 class="m-b-md">Recived Invitations</h1>
                <div class="table-kipper">
                    <table class="table">
                        <tbody>
                            @foreach ($guests as $guest)
                                @if ($guest->guest_id == $host->id)
                                    <tr>
                                        <td>
                                            {{ $guest->title }}
                                        </td>
                                        <td>
                                            <select class="form-control" name="{{ 'invitation_'.$guest->id }}">
                                                <option value='0' @if ($guest->answer == '0') selected @endif >Pending</option>
                                                <option value='1' @if ($guest->answer == '1') selected @endif >I will certainly come</option>
                                                <option value='-1' @if ($guest->answer == '-1') selected @endif >Sorry, I can't come</option>
                                            </select>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            {{ Form::submit('Answer',["class" => "btn btn-default"])}}
            {{ Form::close() }}
        </div>
    </div>
@stop