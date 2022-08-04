<?php declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JobService
{
    /**
     * @param Job $job
     * @return bool
     */
    public function canUserApply(Job $job):bool
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

            return true;
        }

        return false;
    }

    /**
     * @param Request $request
     * @return string
     */
    public function tryCreateJob(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $enoughCoinRightTime = Job::with([
                'user' => function ($query) {
                    $query->where('coins', '>', 2);
                },
            ])
                ->where('created_at', '>=', Carbon::now()->subDay())
                ->get()
            ;

            if ($enoughCoinRightTime->count() < 2 && ($user->coins > 2)) {
                $user->decrement('coins', 2);
                Job::create([
                    'title' => $request['title'],
                    'description' => $request['description'],
                    'user_id' => Auth::user()->id,
                ]);

                return  'New job created successfully';
            }

            return ($enoughCoinRightTime->count() < 2) ? 'You dont have enough coins.' : 'You can add only two jobs per day.';
        }

        return 'you must first be logged in to be able to create a new job';
    }

    /**
     * @param $jobId
     * @return Carbon|int
     */
    public function delayTime($jobId){
        $appliedJobLastOur = DB::table('users_jobs')
            ->where('job_id', $jobId)
            ->where('created_at', '>=', Carbon::now()->subHours(1)->toDateTimeString())
            ->latest('created_at')->first('created_at');
        if ($appliedJobLastOur ) {
            return Carbon::parse($appliedJobLastOur->created_at)->addHour();
        }
        return 0;
    }
}
