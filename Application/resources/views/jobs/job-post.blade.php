@extends('base')
@section('title')- Post a Job @endsection
@section('current') Post a Job @endsection
@section('current-header') Post a Job @endsection

@section('content')
    @include('inc.header')

    <div class="modal-dialog modal-lg">
        @include('inc.messages')
        <a href="{{url()->previous()}}" class="btn btn-primary"><i class="ti-arrow-left"></i> Go Back</a>
        <div class="modal-content">

            <div class="modal-header">
                <!--
                <button type="button" class="close" data-dismiss="modal"><span
                            aria-hidden="true">×</span>
                </button>
                -->
                <h3 class="modal-title" id="myModalLabel">Post a Job</h3>
            </div>
            {!! Form::open(['action'=>'JobsController@store','class'=>'form-ad','method'=>'POST','enctype'=>'multipart/form-data']) !!}
            <div class="modal-body">

                <div class="form-group">
                    {{Form::label('title','Job Title',['class'=>'control-label'])}}<span class="required">*</span>
                    {{Form::text('title','',['class'=>'form-control','required'])}}
                </div>
                <!--
                <div class="form-group">
                    <label class="control-label">Location <span>(optional)</span></label>
                    <input type="text" class="form-control" placeholder="e.g. Manila">
                </div>
                -->
                <div class="form-group">
                    {{Form::label('type','Job Type',['class'=>'control-label'])}}<span class="required">*</span>
                    <div class="radio">
                        <label style="color:black;">{{Form::radio('type','FT',['type'=>"radio"])}}Full-Time</label>
                    </div>
                    <div class="radio">
                        <label style="color:black;">{{Form::radio('type','PT',['type'=>"radio"])}}Part-Time</label>
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('subject','Subject/s',['class'=>'control-label'])}}<span class="required">*</span>
                    @if(count($subjects)>0)
                        @foreach($subjects as $subject)
                            <div class="checkbox">
                                <label style="color:black;">{{Form::checkbox('subjects[]',$subject->id,false,['type'=>"checkbox"])}}{{$subject->name}}</label>
                            </div>
                        @endforeach
                    @else
                        <br/>
                        <br/>
                        <h6>There are no Free Subjects</h6>
                        <br/>
                        <a href="#" class="btn btn-success">Create Subject</a>
                        <br/>
                    @endif
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        {{Form::label('min-salary','Minimum Salary (PHP)',['class'=>'control-label'])}}<span
                                class="required">*</span>
                        {{Form::number('min-salary','',['min'=>'1.00','step'=>'.01','class'=>'form-control','required'])}}
                    </div>
                    <div class="form-group col-md-6">
                        {{Form::label('max-salary','Maximum Salary (PHP)',['class'=>'control-label'])}}<span
                                class="required">*</span>
                        {{Form::number('max-salary','',['min'=>'1.00','step'=>'.01','class'=>'form-control','required'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::label('description','Description',['class'=>'control-label'])}}<span
                            class="required">*</span>
                    {{Form::textarea('description','',['id'=>'editor0','class'=>'form-control','placeholder'=>'Job Description','required'])}}
                </div>
                <!--
                <div class="form-group">
                    <label class="control-label">Closing Date <span>(optional)</span></label>
                    <input type="text" class="form-control" placeholder="yyyy-mm-dd">
                    <p class="note">Deadline for new applicants.</p>
                </div>
                -->

            </div>
            <div class="modal-footer">
                <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
                {{Form::submit('Post your Job',['class'=>'btn btn-common'])}}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection