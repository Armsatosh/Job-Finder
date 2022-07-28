<?php

namespace Tests\Unit;

use App\Http\Controllers\AuthController;
use App\Models\Job;
use App\Models\User;
use App\Services\JobService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class JobTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */

    public function test_job_apply_with_not_enough_coins()
    {
        $user = User::factory()->create([
            'coins' => 0,
        ]);
        $this->actingAs($user);
        $job = Job::factory()->create([
            'user_id' => $user->id,
        ]);
        $jobService = new JobService();
        $tryApplyJob = $jobService->canUserApply($job);
        $this->assertFalse($tryApplyJob);

    }
    public function test_job_apply_with_not_enough_coins_second_time()
    {
        $user = User::factory()->create([
            'coins' => 1,
        ]);
        $this->actingAs($user);
        $job = Job::factory()->create([
            'user_id' => $user->id,
        ]);
        $jobService = new JobService();
        $tryApplyJobFirstTme = $jobService->canUserApply($job);
        $tryApplyJobSecondTime = $jobService->canUserApply($job);
        $this->assertFalse($tryApplyJobSecondTime);

    }

    public function test_apply_creat_job_with_enough_coins_first_time()
    {
        $user = User::factory()->create([
            'coins' => 10,
        ]);
        $this->actingAs($user);
        $job = Job::factory()->create([
            'user_id' => $user->id,
        ]);
        $jobService = new JobService();
        $tryApplyJobFirstTme = $jobService->canUserApply($job);
        $this->assertTrue($tryApplyJobFirstTme);

    }
    public function test_apply_creat_same_job_secdond_time_with_enough_coins()
    {
        $user = User::factory()->create([
            'coins' => 10,
        ]);
        $this->actingAs($user);
        $job = Job::factory()->create([
            'user_id' => $user->id,
        ]);
        $jobService = new JobService();
        $tryApplyJobFirstTme = $jobService->canUserApply($job);
        $tryApplyJobSecondTime = $jobService->canUserApply($job);
        $this->assertFalse($tryApplyJobSecondTime);

    }
}
