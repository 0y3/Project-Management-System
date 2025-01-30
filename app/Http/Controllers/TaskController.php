<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\ProjectTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TaskController extends Controller
{

    public function index(){
        return view('project.task', sidebarMenuList());
    }

    public function taskCount(){
        $today = Carbon::today();
        $projectTasks = ProjectTask::with(['project'])->where('assignee_id', auth()->user()->id)->get();

        $completeProjectTask = $projectTasks->where('status', 'completed')->count();

        $overdueProjectTask = $projectTasks->filter(function ($task) use ($today) {
            return $task->status === 'in progress' && $task->end_date <= $today;
        })->count();

        $inproProjectTask = $projectTasks->filter(function ($task) use ($today) {
            return $task->status === 'in progress' && $task->end_date > $today;
        })->count();

        $data = response()->json([
            'status' => 'success',
            'message' => 'Task successfully',
            'data' => [
                'completeTask' => $completeProjectTask,
                'overdueTask' => $overdueProjectTask,
                'inproTask' => $inproProjectTask,
            ]
        ], 201);

        return $data;
    }

    public function getProjectTaskData(ProjectTask $project){


        $data = $project->where('assignee_id', auth()->user()->id)->with(['project']);
        // dd($data->get()->toArray());
        return datatables()->eloquent($data)
            ->addColumn('daysRemain',function($data){
                $currentDate = Carbon::today();
                if ($data->end_date >= $currentDate) {
                    return $currentDate->diffInDays($data->end_date);
                }
                return 0;
            })
            ->addColumn('action', function ($data) {
                $status = $data->status !== 'completed' ? '<a href="javascript:void(0);" id="' . $data->id . '" class="dropdown-item completedProjectTask"><i class="icon-checkmark3 text-success"></i>Complete</a>' : '';
                return '
                    <div class="list-icons">
                        <div class="dropdown">
                            <a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu7"></i></a>
                            <div class="dropdown-menu dropdown-menu-right">
                                '.$status.'
                            </div>
                        </div>
                    </div>
                ';
            })
            ->rawColumns(['action','daysRemain'])
            ->toJson();
    }
}
