<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Services\JobService;
use Illuminate\Http\Request;

interface JobInterface
{
    /**
     * Apply to Job
     *
     * @param Job $job
     * @return  \Illuminate\Http\RedirectResponse
     */
    public function jobApply(Job $job, JobService $jobService);

    /**
     * Job like
     *
     * @param Request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function like(Request $request);
}