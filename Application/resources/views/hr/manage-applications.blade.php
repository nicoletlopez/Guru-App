@extends('hr.dashboard-menu')
@section('title')- Manage Applications @endsection
@section('current') Manage Applications @endsection
@section('current-header') Manage Applications @endsection
@section('manage-applications-active') active @endsection

@section('dashboard-content')

    <div class="job-alerts-item candidates">
        <div class="row">
            <div class="col-md-5">
                <h3 class="alerts-title">Manage applications</h3>
            </div>
        </div>
        <br/>
        @foreach($applicants as $key=>$applicant)
            <div class="box col-md-12">
                <div class="row">
                    <div class="col-md-1">
                        <b style="font-size: 20px">#{{++$key}}</b>
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
                        <a href="#" class="btn btn-common btn-block">Accept Application</a>
                    </div>
                </div>
                <hr/>
                <p style="font-size:16px;">
                    Applied for Job:
                    <a href="/jobs/{{$applicant->pivot->job_id}}/applicants">
                        <b>{{$applicant->job_title}}</b>
                    </a>
                </p>
            </div>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
        @endforeach
        {{ $applicants->links() }}
    </div>

@endsection