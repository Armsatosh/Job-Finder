<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Overtrue\LaravelVote\Traits\Voter;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable;
    use CanResetPassword;
    use HasFactory;
    use Voter;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'avatar'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function getUserVotesAttribute()
    {
        $userVotes = Vote::where(['votable_id' => $this->id,'votable_type' => 'App\Models\User'])->count();

        return $userVotes;
    }

    public function getVotedAttribute()
    {
        $userVotes = Vote::query()->where(['votable_id' => $this->id,'votable_type' => 'App\Models\User','user_id' => Auth::user()->id])->exists();

        return $userVotes;
    }

    /**
     * Get the comments for the blog post.
     */
    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    public function appliedJobs(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Job::class, 'users_jobs', 'user_id', 'job_id');
    }
}
