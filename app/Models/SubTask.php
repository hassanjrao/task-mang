<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubTask extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id')->withDefault();
    }

}
