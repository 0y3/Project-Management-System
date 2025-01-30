<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\ProjectTask;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Notifications\SendTaskReminderNotification;

class tasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command send task reminder email to all users';


    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 0;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        ini_set("memory_limit", "10056M");
        set_time_limit(0);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Retrieve all options...
        $options = $this->options();

        $today = Carbon::today();

        $tasks= ProjectTask::with(['project','assignee'])
                ->where('status', 'in progress')->whereDate('end_date', $today->subDays(1))
                ->latest()->get();
// dd($tasks->toArray());
        if($tasks){
            $bar = $this->output->createProgressBar(count($tasks));
            $bar->start();
            foreach($tasks->chunk(100) as $task){
                foreach ($task as $key => $task_details) {
                    if(!is_null($task_details->assignee->email) && !empty($task_details->assignee->email)){
                        $task_details->assignee->notify(new SendTaskReminderNotification($task_details->assignee,$task_details));
                    }
                    $bar->advance();
                }
            }
            $bar->finish();
        }
    }
}
