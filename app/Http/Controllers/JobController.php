<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Mail\JobApplied;
use App\Models\Job;
use App\Models\Vote;
use App\Services\JobService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class JobController extends Controller implements JobInterface
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
    public function store(Request $request, JobService $jobService)
    {
        $this->validate($request, [
            'title' => 'required|string|min:3',
            'description' => 'required|string|min:3',

        ]);
        $JobCreatingStatus = $jobService->tryCreateJob($request);

        return redirect('/job')
            ->with('flash_notification.message', $JobCreatingStatus)
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
    public function jobApply(Job $job, JobService $jobService)
    {
        $tryApplyJob = $jobService->canUserApply($job);
        if ($tryApplyJob) {
            $this->sendMail($job, $this->getPayload($job));
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
        $user = Auth::user();
        if ($user) {
            if ($request->type === 'job') {
                $job = Job::find($request->id);
                $user->upvote($job);

                return back();
            }

            if ($request->type === 'user') {
                $voteData = [
                    'user_id' => $user->id,
                    'votes' => 1,
                    'votable_type' => 'App\Models\User',
                    'votable_id' => $request->id,
                ];
                Vote::firstOrCreate($voteData);

                return back();
            }
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
        Job::findOrFail($id)->delete();

        return redirect()
            ->route('job.index')
            ->with('flash_notification.message', 'Job deleted successfully')
            ->with('flash_notification.level', 'success')
        ;
    }

    /**
     * @param Job $job
     * @return array
     */
    public function getPayload(Job $job): array
    {
        $mailPayload = [
            'title' => $job->title,
            'applicantName' => Auth::user()->name,
            'appliedCount' => $job->appliedUsers()->count(),
            'appliedDate' => date('Y-m-d H:i:s'),

        ];

        return $mailPayload;
    }

    /**
     * @param Job $job
     * @param array $mailPayload
     * @return void
     */
    public function sendMail(Job $job, array $mailPayload): void
    {
        Mail::to($job->user->email)
            ->send(new JobApplied($mailPayload))
        ;
    }
}
