<div class="job-alerts-item candidates">
    <h3 class="alerts-title">My Guru Drive</h3>
    <hr/>
    {!! Form::open(['method'=>'post']) !!}
    {{Form::text('documentSpaceName','',['class'=>'','required','placeholder'=>'Folder Name'])}}
    {{Form::submit('Create',['class'=>''])}}
    {!! Form::close() !!}
    <br/>
    <div class="row">
        <div class="col-md-12">
            <!-- this div can be repeated-->
            @if(count($documentSpaces)>0)
                @foreach($documentSpaces as $documentSpace)
                    <a href="/document-spaces/{{$documentSpace->id}}">
                        <div style="" class="col-md-3 col-sm-3 col-xs-12 f-category">
                            {!! Form::open(['action'=>['DocumentSpacesController@destroy',$documentSpace->id],'method'=>'POST']) !!}
                            @include('inc.button-delete')
                            {!! Form::close() !!}
                            <div class="icon">
                                <i class="ti-folder"></i>
                            </div>
                            <div style="word-wrap:break-word;" class="">
                                <h3>{!! str_limit($documentSpace->title,28,'...') !!}</h3>
                            </div>
                            <span class="badge">{{count($documentSpace->documents)}} Files</span>
                        </div>
                    </a>
                @endforeach
            @else
                <h5>No Folders.</h5>
        @endif
        <!--END-->
        </div>
    </div>
</div>
@include('inc.prompt-delete')