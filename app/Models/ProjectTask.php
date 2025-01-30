<?php

namespace App\Models;

use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectTask extends Model
{
    use HasFactory, Userstamps,SoftDeletes;
    protected $guarded = ['id'];
    public $timestamps = true;

    public function project(){
        return $this->belongsTo(Project::class);
    }

    public function assignee(){
        return $this->belongsTo(User::class,'assignee_id');
    }

    // public function getProgressAttribute(){
    //     if(count($this->steps) > 0) {
    //     $progress = floor((count($this->StepsDone) / count($this->steps)) * 100);
    //     } else {
    //     $progress = 0;
    //     }
    //     return $progress;
    // }
}
