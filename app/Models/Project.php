<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Project extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'project_name',
        'client_name',
        'description',
        'status',
        'priority',
        'start_date',
        'due_date',
    ];

    protected static function booted()
    {
        static::creating(function ($project) {     // or whatever flag you use
            $project->created_by =  Auth::id(); 
        });

        static::updating(function ($project) {     // or whatever flag you use
            $project->updated_by =  Auth::id(); 
        });

        static::deleting(function ($project) {
            $project->record_status = 'deleted';       // or whatever flag you use
            $project->deleted_by =  Auth::id(); // current user ID
            $project->saveQuietly();
        });
    }
}
