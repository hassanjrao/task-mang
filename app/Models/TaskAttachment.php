<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class TaskAttachment extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $appends = ['file_url'];

    public function getFileUrlAttribute()
    {
        return $this->file_path ? Storage::url($this->file_path) : null;
    }

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id')->withDefault();
    }
}
