<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Project;
use App\Models\ProjectTask;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\ProjectRequest;
use App\Notifications\SendTaskNotification;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(){
        $this->middleware('permission:View Project',['only' => ['index','projectTaskIndex']]);
        $this->middleware('permission:Create Project',['only' => ['create','store']]);
        $this->middleware('permission:Edit Project',['only' => ['edit','update']]);
        $this->middleware('permission:Delete Project',['only' => ['destroy']]);
    }

    public function index(){
        return view('project.index', array_merge( sidebarMenuList()));
    }

    public function projectTaskIndex($project_id){
        $users = User::select('id', 'name')->get();
        $project = Project::with(['creator','tasks','taskcomplete' ])->find($project_id);
        return view('project.project_task', array_merge(compact('users','project'), sidebarMenuList()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectRequest $request, Project $p)
    {
        try {
            DB::beginTransaction();


            $storeProject = $p->create($request->validated());

            $data = response()->json([
                'status' => 'success',
                'message' => 'Project created successfully'
            ], 201);

            DB::commit();
        } catch (Exception $err) {
            DB::rollback();
            $data = response()->json([
                'status' => 'error',
                'message' => 'Project not created.',
                'stack' => $err
            ], 500);
        }

        return $data;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Project $p)
    {
        $data = $p->find($id);
        return response()->json(['status' => 'success', 'project' => $data]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectRequest $request,$id)
    {
        try {
            DB::beginTransaction();

            $project = Project::find($id);
            $project->update($request->validated());
            $data = response()->json([
                'status' => 'success',
                'message' => 'Project Updated successfully'
            ], 201);

            DB::commit();
        } catch (Exception $err) {
            DB::rollback();
            $data = response()->json([
                'status' => 'error',
                'message' => 'Project not Updated.',
                'stack' => $err
            ], 500);
        }

        return $data;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($project_setup, Project $p)
    {
        $projectInst = $p->find($project_setup);
        // $deleteproject = $projectInst->delete();

        // if (!$deleteproject) return  response()->json(['status' => 'error', 'message' => 'Project was not deleted successfully']);

        return response()->json(['status' => 'success', 'message' => 'Project was deleted successfully']);
    }

    public function getProjectData(Project $project){

        $data = $project->with([
            'creator',
            'tasks:id,project_id,assignee_id,name',
            'tasks.assignee:id,name',
            'taskcomplete'
        ])
        ->select('id', 'name', 'description', 'start_date', 'end_date');
        // dd($data->get()->toArray();
        return datatables()->eloquent($data)
            ->addColumn('assignee', function($data){
                if ($data->tasks && count($data->tasks) > 0) {
                    $assigneeNames = array_map(function($task) {
                        return $task['assignee']['name'];
                    }, $data->tasks->toArray());

                    // Remove duplicates
                    $uniqueAssigneeNames = array_unique($assigneeNames);

                    return implode(", ", $uniqueAssigneeNames);
                }
                return '';
            })
            ->addColumn('tasksCount', function($data){
                if ($data->tasks && count($data->tasks) > 0) {
                    return count($data->tasks);
                }
                return 0;
            })
            ->addColumn('daysRemain',function($data){
                $currentDate = Carbon::today();
                if ($data->end_date >= $currentDate) {
                    return $currentDate->diffInDays($data->end_date);
                }
                return 0;
            })
            ->addColumn('project', function($data){
                $task = count($data->tasks);
                $task_comp = count($data->taskcomplete);

                $task_per = (100*$task_comp)/$task;

                $task_per = round($task_per) < 1 ? 1 : round($task_per);
                $color = 'bg-danger';
                if($task_per >= 30){$color = 'bg-warning';}
                if($task_per >= 60){$color = 'bg-teal';}
                if($task_per >= 85){$color = 'bg-success';}
                return '
                <div>
                    <a href="'.route('project.task',['id'=>$data->id]).'" class="text-default font-weight-bold">'.$data->name.'</a>
                    <div class="text-muted font-size-sm mb-1">
                        <i class="icon-calendar2 text-blue mr-1"></i>'
                        .Carbon::parse($data->start_date)->format('F d, Y').' - '.Carbon::parse($data->end_date)->format('F d, Y').
                    '</div>
                    <div class="progress rounded-round">
                        <div class="progress-bar '.$color.'" style="width: '.$task_per.'%">
                            <span class="font-weight-bolder">'.round($task_per).'% Complete</span>
                        </div>
                    </div>
                </div>
                ';
            })
            ->addColumn('projectMgr', function($data){
                return $data->creator->name;
            })
            ->addColumn('action', function ($data) {
                return '
                    <div class="list-icons">
                        <div class="dropdown">
                            <a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu7"></i></a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="javascript:void(0);" id="' . $data->id . '" class="dropdown-item editProject"><i class="icon-pencil mr-1"></i>Edit</a>
                                <a href="javascript:void(0);" id="' . $data->id . '" class="dropdown-item resendEmail" ><i class="icon-trash mr-1 text-danger"></i>Delete Project</a>
                                <div class="dropdown-divider"></div>
                                <a href="'.route('project.task',['id'=>$data->id]).'" id="' . $data->id . '" class="dropdown-item newTask"><i class="icon-history mr-1"></i>View Tasks</a>
                                <!-- <a href="javascript:void(0);" id="' . $data->id . '" class="dropdown-item deleteUser"><i class="icon-plus3 mr-1 text-blue"></i>Add Task</a>
                                <div class="dropdown-divider"></div>
                                <a href="javascript:void(0);" id="' . $data->id . '" class="dropdown-item resendEmail" ><i class="icon-mailbox mr-1"></i>Delete Project</a> -->
                            </div>
                        </div>
                    </div>
                ';
            })
            ->rawColumns(['assignee', 'action','tasksCount','projectMgr','project','daysRemain'])
            ->toJson();
    }

    public function getProjectTaskData(Request $request,ProjectTask $project,$project_id=null){
        // Apply server-side search
        // $query = ProjectTask::query();
        // $query->with(['assignee'])->where('project_id',$project_id??$request->id);
        // if ($request->has('search') && $request->search['value'] != '') {
        //     $searchTerm = $request->search['value'];
        //     $query->where(function($q) use ($searchTerm) {
        //         $q->where('name', 'LIKE', "%{$searchTerm}%")
        //             ->orWhere('start_date', 'LIKE', "%{$searchTerm}%")
        //             ->orWhere('end_date', 'LIKE', "%{$searchTerm}%")
        //             ->orWhereHas('assignee', function($q2) use ($searchTerm) {
        //                 $q2->where('name', 'LIKE', "%{$searchTerm}%");
        //             });
        //     });
        // }
        // $data = $query;

        $data = $project->where('project_id',$project_id??$request->id)->with(['assignee']);
        // ->select('id', 'name', 'description', 'start_date', 'end_date','assignee_id');
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
                                <a href="javascript:void(0);" id="' . $data->id . '" class="dropdown-item editProjectTask"><i class="icon-pencil"></i>Edit</a>
                                <a href="javascript:void(0);" id="' . $data->id . '" class="dropdown-item deleteProjectTask" ><i class="icon-trash text-danger"></i>Delete</a>
                            </div>
                        </div>
                    </div>
                ';
            })
            ->rawColumns(['action','daysRemain'])
            ->toJson();
    }

    public function storeProjectTask(ProjectRequest $request, ProjectTask $p){
        try {
            DB::beginTransaction();


            $storeProjectTask = $p->create(array_merge(
                $request->validated(),
                ['project_id' => $request->project_id],
            ));

            $data = response()->json([
                'status' => 'success',
                'message' => 'Task created successfully'
            ], 201);

            // $validated = $request->validated();
            # Send notification email to the user
            $user = User::find($storeProjectTask->assignee_id);
            $user->first()->notify(new SendTaskNotification($user,$storeProjectTask));

            DB::commit();
        } catch (Exception $err) {
            DB::rollback();
            $data = response()->json([
                'status' => 'error',
                'message' => 'Task not created.',
                'stack' => $err
            ], 500);
        }

        return $data;
    }

    public function editProjectTask($id, ProjectTask $p){
        $data = $p->find($id);
        return response()->json(['status' => 'success', 'task' => $data]);
    }

    public function updateProjectTask(ProjectRequest $request,$id){
        try {
            DB::beginTransaction();

            $project = ProjectTask::find($id);
            $project->update($request->validated());
            $data = response()->json([
                'status' => 'success',
                'message' => 'Task Updated successfully'
            ], 201);

            DB::commit();
        } catch (Exception $err) {
            DB::rollback();
            $data = response()->json([
                'status' => 'error',
                'message' => 'Task not Updated.',
                'stack' => $err
            ], 500);
        }

        return $data;
    }

    public function destroyProjectTask($id, ProjectTask $p)
    {
        $projectInst = $p->find($id);
        $deleteproject = $projectInst->delete();

        if (!$deleteproject) return  response()->json(['status' => 'error', 'message' => 'Task was not deleted successfully']);

        return response()->json(['status' => 'success', 'message' => 'Task was deleted successfully']);
    }

    public function completeProjectTask(Request $request,$id)
    {
        try {
            DB::beginTransaction();

            $project = ProjectTask::find($id);
            $project->status = 'completed';
            $project->save();


            $projectTask = ProjectTask::where('project_id',$project->project_id)->get();
            $task = count($projectTask);
            $task_comp = count($projectTask->where('status','completed'));
            $task_per = (100*$task_comp)/$task;
            $task_per = round($task_per) < 1 ? 1 : round($task_per);
            $color = 'bg-danger';
            if($task_per >= 30){$color = 'bg-warning';}
            if($task_per >= 60){$color = 'bg-teal';}
            if($task_per >= 85){$color = 'bg-success';}

            $dataPer =[
                'task_per' => $task_per,
                'color' => $color,
            ];

            $data = response()->json([
                'status' => 'success',
                'message' => 'Task Completed successfully',
                'data' => $dataPer,
            ], 201);

            DB::commit();
        } catch (Exception $err) {
            DB::rollback();
            $data = response()->json([
                'status' => 'error',
                'message' => 'Task not Updated.',
                'stack' => $err
            ], 500);
        }

        return $data;
    }
}
