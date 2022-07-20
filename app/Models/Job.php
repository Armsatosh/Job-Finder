<?php declare(strict_types=1);

namespace App\Models;

use App\Events\JobCreated;
use App\Events\JobDeleted;
use App\Events\JobEdited;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use HasFactory;
    use SoftDeletes;

    /*
     * Table name
     */
    protected $table = 'jobs';

    /*
     * Guarded fields for protecting mass assignment vulnerability
     */
    protected $guarded = [];

    protected $dispatchesEvents = [
        'created' => JobCreated::class,
        'updated' => JobEdited::class,
        'deleted' => JobDeleted::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appliedUsers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_jobs', 'job_id', 'user_id');
    }
}
