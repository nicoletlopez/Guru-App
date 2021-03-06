@extends('hr.dashboard-menu')
@section('title')- Manage Applications @endsection
@section('current') Manage Applications @endsection
@section('current-header') Manage Applications @endsection
@section('manage-jobs-active') active @endsection

@section('dashboard-content')

    <div class="job-alerts-item candidates">
        <div class="row">
            <div class="col-md-4">
                <h3 class="alerts-title">Applications for Job</h3>
            </div>
            <div class="col-md-5">
                {!! Form::open(['action'=>["ApplicationsController@search",$id],'method'=>'GET']) !!}
                {{--<input class="form-control" type="text" name="s" placeholder="job title / keywords">--}}
                <div class="form-group">
                    <div class="input-icon">
                        <i class="ti-search"></i>
                        <input id="search-term" type="text" class="form-control"
                               placeholder="Search Applicants" name="search-term" style="height: 45px;">
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            <div class="col-md-3">
                <a href="{{route('applications.index')}}" class="btn btn-success block">
                    Manage Applications
                </a>
            </div>
        </div>
        <h3 class="medium-title col-md-12">{{$job}}</h3>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        @foreach($applicants as $key=>$applicant)
            <div class="box col-md-12">
                <div class="col-md-1">
                    <p style="margin-bottom: 15px">
                        <b style="font-size: 20px;">#{{++$key}}</b>
                    </p>
                    <a target="_blank"
                       href="/applications/{{$applicant->user_id}}/resume/{{$applicant->mainTemplate($applicant->user_id)}}">
                        <span class="ti-envelope" style="font-size: 40px" title="Resume"></span>
                    </a>
                </div>
                <div class="col-md-2">
                    <img src="{{$applicant->user->profile->picture}}" height="80" style="border-radius: 8px;"/>
                </div>
                <div class="col-md-5">
                    <b style="font-size: 20px">{{$applicant->user->name}}</b><br/>
                    Born on <b style="font-size: 15px">{{date('F j, Y', strtotime($applicant->user->profile->dob))}}</b><br/>
                    Lives in <b style="font-size:14px">{{$applicant->user->profile->street_address}},</b><br/>
                    <b style="font-size: 14px">{{$applicant->user->profile->city}} City</b><br/>
                    Contact No. <b style="font-size: 14px">{{$applicant->user->profile->contact_number}}</b>
                </div>
                <div class="col-md-4">
                    <b style="font-size: 14px">Date Applied:</b><br/>
                    <b style="font-size: 14px">{{date('F j, Y \a\t g:i a', strtotime($applicant->pivot->created_at))}}</b><br/>
                    {{--                    <b style="font-size: 12px">{{date('g:i a', strtotime($applicant->pivot->created_at))}}</b>--}}
                    <br/>

                    <a href="/applications/{{$applicant->pivot->job_id}}/{{$applicant->pivot->user_id}}/edit"
                       class="btn btn-common btn-block">
                        Accept Application
                    </a>
                </div>
            </div>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
        @endforeach
    </div>
@endsection