<?php

namespace App\Models;

use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory, Userstamps,SoftDeletes;
    protected $guarded = ['id'];
    public $timestamps = true;

    public function tasks(){
        return $this->hasMany(ProjectTask::class);
    }

    public function taskComplete(){
        return $this->hasMany(ProjectTask::class)->where('status','completed');
    }

    public function creator(){
        return $this->belongsTo(User::class, 'id','created_by');
    }

    // public function getProgressAttribute(){

    //     $progress = 0;
    //     if (count($this->tasks) > 0) {
    //     foreach ($this->tasks as $task) {
    //         $progress += $task->progress;
    //     }
    //     $progress = $progress / count($this->tasks);
    //     }
    //     return floor($progress);
    // }

    // public function getProgressPercentAttribute(){
    //     return $this->progress . '%';
    // }

}
