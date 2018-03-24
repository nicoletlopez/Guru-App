<?php

namespace App\Http\Controllers;

use App\Mail\AcceptJobNotification;
use Illuminate\Http\Request;
use App\Job;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

class ApplicationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = auth()->user()->id;
        $jobs = Job::where('user_id',$id)->get();
        $applicants = new Collection();

        foreach ($jobs as $job){
            $concat = $job->applicants->map(function ($applicant) use ($job){
                $applicant['job_title'] = $job->title;
                return $applicant;
            });

            $applicants = $applicants->concat($concat);
        }

        $applicants = $applicants->sortByDesc('pivot.created_at');

        $context =
            [
                'applicants'=>$this->paginate($applicants)->withPath('applications')
            ];

        return view('hr.manage-applications')->with($context);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $faculty = auth()->user()->faculty;
        $user = $faculty->user;
        $job =  Job::find($request->input('job-id'));
        $job->applicants()->save($faculty);
        $job->save();

        //refresh the cached applications as a new one has been made


        $school = $job->hr->user;

        Mail::to($job->hr->user->email)->queue(new AcceptJobNotification($job, $user, $school));

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function applicants($id){
        $job = Job::find($id);
        $hr = auth()->user()->id;

        if($job->user_id != $hr){
            return redirect()->intended(route('manage-jobs'));
        }

        $applicants = $job->applicants;

        $context =
            [
                'job'=>$job->title,
                'id'=>$job->id,
                'applicants'=>$applicants,
            ];
        return view('jobs.applications.applicants')->with($context);
    }

    public function search(Request $request, $id){
        $job = Job::find($id);
        $needle = $request->input('search-term');

        $faculties = $job->applicants;
        $applicants = array();

        foreach($faculties as $faculty){
            $haystack = $faculty->user->name;
            if(stripos(strtolower($haystack),strtolower($needle))  !== false){
                array_push($applicants,$faculty);
            }
        }

        if(is_null($needle)){
            $applicants = $faculties;
        }else{
            $applicants = collect($applicants);
        }

        $context = [
            'job'=>$job->title,
            'id'=>$job->id,
            'applicants'=>$applicants,
        ];

        return view('jobs.applications.applicants')->with($context);
    }

    //function for paginating Collections that didn't use Laravel ORM
    private function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
