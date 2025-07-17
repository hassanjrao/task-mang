<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to')->withDefault();
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id')->withDefault();
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class, 'priority_id')->withDefault();
    }

    public function status()
    {
        return $this->belongsTo(TaskStatus::class, 'task_status_id')->withDefault();
    }

    public function subTasks()
    {
        return $this->hasMany(SubTask::class, 'task_id');
    }

    public function attachments()
    {
        return $this->hasMany(TaskAttachment::class, 'task_id');
    }

    public function assignedUsers()
{
    return $this->belongsToMany(User::class, 'task_user', 'task_id', 'user_id');
}

}
