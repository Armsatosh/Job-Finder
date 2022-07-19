<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class JobController extends Controller
{
    /**
     * View Jobs listing.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $jobsList = Cache::rememberForever('jobs', function () {
            return Job::where('user_id', Auth::id())->paginate(5);
        });

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
        $message = 'You can add only two jobs per day';
        $lastDayJobsCount = Job::where('created_at', '>=', Carbon::now()->subDay())->count();
        if ($lastDayJobsCount < 2) {
            Job::create([
                'title' => $request['title'],
                'description' => $request['description'],
                'user_id' => Auth::user()->id,
            ]);
            $message = 'New job created successfully';
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
