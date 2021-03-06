<?php

namespace App\Http\Controllers;

use App\Faculty;
use App\Hr;
use App\Mail\AcceptApplicationNotification;
use App\Mail\AcceptJobNotification;
use App\Subject;
use App\User;
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
        $hr = auth()->user()->id;
        $jobs = Job::where('hr_id', $hr)->get();
        $applicants = new Collection();

        foreach ($jobs as $job) {
            $concat = $job->pendingApplicants->map(function ($applicant) use ($job) {
                $applicant['job_title'] = $job->title;
                return $applicant;
            });

            $applicants = $applicants->concat($concat);
        }

        $applicants = $applicants->sortByDesc('pivot.created_at');

        $context =
            [
                'key' => 0,
                'hr_id' => $hr,
                'applicants' => $this->paginate($applicants)->withPath('applications')
            ];
//        return var_dump($applicants);
        return view('applications.application-pending')->with($context);
    }

    public function acceptedApplications()
    {
        $hr = auth()->user()->id;
        $jobs = Job::where('hr_id', $hr)->get();
        $applicants = new Collection();

        foreach ($jobs as $job) {
            $concat = $job->acceptedApplicants->map(function ($applicant) use ($job) {
                $applicant['job_title'] = $job->title;
                return $applicant;
            });

            $applicants = $applicants->concat($concat);
        }

        $applicants = $applicants->sortByDesc('pivot.updated_at');

        $context =
            [
                'key' => 0,
                'hr_id' => $hr,
                'applicants' => $this->paginate($applicants)->withPath('applications')
            ];

        return view('applications.application-accepted')->with($context);
    }

    public function hire($job_id,$faculty_id)
    {
        //get job row
        $job = Job::find($job_id);

        //register person as employee
        $hr = auth()->user()->hr;
        $faculty = Faculty::find($faculty_id);
        $hr->employees()->save($faculty);

        //updates subject faculty_id field
        $hr_id = auth()->user()->id;
        $subject = Subject::whereJob($job_id)->first();
        $subject->faculty_id = $faculty_id;
        $subject->save();

        //deletes job after a person is hired
        $job->applicants()->detach();
        $job->delete();

        return redirect('/applications/'.$hr_id.'/accepted');
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!auth()->guest()) {
            if (!(auth()->user()->profile)) {
                return redirect()->route('profile.create')->with('error', 'Create a Profile First');
            } elseif (count(auth()->user()->faculty->resumes) == 0) {
                return redirect()->route('resumes.create')->with('error', 'Create a Resume First');
            }

            $faculty = auth()->user()->faculty;
            $user = $faculty->user;
            $job = Job::find($request->input('job-id'));
            $job->applicants()->save($faculty);
            $job->save();

            //refresh the cached applications as a new one has been made


            $school = $job->hr->user;

            Mail::to($job->hr->user->email)->queue(new AcceptJobNotification($job, $user, $school));

            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function applicants($id)
    {
        $job = Job::find($id);
        $hr = auth()->user()->id;

        if ($job->hr_id != $hr) {
            return redirect()->intended(route('manage-jobs'));
        }

        $applicants = $job->applicants;

        $context =
            [
                'job' => $job->title,
                'id' => $job->id,
                'applicants' => $applicants,
            ];
        return view('jobs.applications.applicants')->with($context);
    }

    public function search(Request $request, $id)
    {
        $job = Job::find($id);
        $needle = $request->input('search-term');

        $faculties = $job->applicants;
        $applicants = array();

        foreach ($faculties as $faculty) {
            $haystack = $faculty->user->name;
            if (stripos(strtolower($haystack), strtolower($needle)) !== false) {
                array_push($applicants, $faculty);
            }
        }

        if (is_null($needle)) {
            $applicants = $faculties;
        } else {
            $applicants = collect($applicants);
        }

        $context = [
            'job' => $job->title,
            'id' => $job->id,
            'applicants' => $applicants,
        ];

        return view('jobs.applications.applicants')->with($context);
    }

    public function updateApplication($job_id, $faculty_id)
    {
        $job = Job::find($job_id);
        $user = User::find($faculty_id);
        $school = User::find($hr = auth()->user()->id);
        $application = $job->applicants->where('user_id', $faculty_id)->first();
        $application->pivot->accepted = true;
        $application->pivot->save();

        Mail::to($job->applicants->where('user_id',$faculty_id)->first()->user->email)
            ->queue(new AcceptApplicationNotification($job, $user, $school));

        return $this->index();
    }

    //function for paginating Collections that didn't use Laravel ORM
    private function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
