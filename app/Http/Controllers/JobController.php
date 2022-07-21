<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Mail\JobApplied;
use App\Models\Job;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class JobController extends Controller
{
    /**
     * View Jobs listing.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $jobsList = Job::where('user_id', Auth::id())->paginate(5);

        return view('job.list', compact('jobsList'));
    }

    /**
     * View Create Form.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('job.create');
    }

    /**
     * Create new Job.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|min:3',
            'description' => 'required|string|min:3',

        ]);
        $lastDayJobsCount = Job::with([
            'user' => function ($query) {
                $query->where('coins', '>', 2);
            },
        ])
            ->where('created_at', '>=', Carbon::now()->subDay())
            ->get()
        ;
        $user = Auth::user();
        if ($lastDayJobsCount->count() < 2 && ($user->coins > 2)) {
            $user->decrement('coins', 2);
            Job::create([
                'title' => $request['title'],
                'description' => $request['description'],
                'user_id' => Auth::user()->id,
            ]);
            $message = 'New job created successfully';
        } else {
            $message = ($lastDayJobsCount->count() < 2) ? 'You dont have enough coins.' : 'You can add only two jobs per day.';
        }


        return redirect('/job')
            ->with('flash_notification.message', $message)
            ->with('flash_notification.level', 'success')
        ;
    }

    /**
     * Toggle Status.
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function edit($id)
    {
        $job = Job::findOrFail($id);

        return view('job.edit', compact('job'));
    }

    /**
     * Update Job
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|min:3',
            'description' => 'required|string|min:3',
        ]);
        $job = Job::findOrFail($id);
        $job->title = $request['title'];
        $job->description = $request['description'];
        $job->update();

        return redirect('/job')
            ->with('flash_notification.message', 'Job updated successfully')
            ->with('flash_notification.level', 'success')
        ;
    }

    /**
     * Apply to Job
     *
     * @param Job $job
     * @return  \Illuminate\Http\RedirectResponse
     */
    public function jobApply(Job $job)
    {
        $jobId = $job->id;
        $user = User::whereHas("appliedJobs", function ($query) use ($jobId) {
            $query->where('users_jobs.user_id', Auth::user()->id);
            $query->where('users_jobs.job_id', $jobId);
        }, 0)
            ->where('id', Auth::user()->id)
            ->where(function ($query) {
                $query->where('coins', '>', 0);
            })->first();
        if ($user) {
            $user->decrement('coins');
            $user->appliedJobs()->syncWithoutDetaching($jobId);
            $mailPayload = [
                'title' => $job->title,
                'applicantName' => Auth::user()->name,
                'appliedCount' => $job->appliedUsers()->count(),
                'appliedDate' => date('Y-m-d H:i:s'),

            ];

            Mail::to($job->user->email)
                ->send(new JobApplied($mailPayload))
            ;
            $message = 'Your response has been successfully sent to the vacancy creator.';
        } else {
            $message = (Auth::user()->coins > 0) ? 'You already responded to this job vacancy' : 'You dont have enough coins';
        }

        return redirect('/')
            ->with('flash_notification.message', $message)
            ->with('flash_notification.level', 'success')
        ;
    }

    /**
     * Job like
     *
     * @param Request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function like(Request $request)
    {
        if ($request->type === 'job') {
            $job = Job::find($request->id);
            $user = Auth::user();
            $user->upvote($job);

            return back();
        }

        if ($request->type === 'user') {
            $voteData = [
                'user_id' => Auth::user()->id,
                'votes' => 1,
                'votable_type' => 'App\Models\User',
                'votable_id' => $request->id,
            ];
            Vote::firstOrCreate($voteData);

            return back();
        }
    }

    /**
     * Delete Job.
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $job = Job::findOrFail($id);
        $job->delete();

        return redirect()
            ->route('job.index')
            ->with('flash_notification.message', 'Job deleted successfully')
            ->with('flash_notification.level', 'success')
        ;
    }
}
