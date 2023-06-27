<?php
namespace Laraveltyping\Typing;
use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\Controller;

class TypingController extends Controller
{
   
    public function index(Request $request)
    {   
        

        $user = session()->get('user');
        $id = $user->id;
        $txtFile = file_get_contents(__DIR__.'/storage/paragraph.txt');
        $lines = preg_split('/[\n\r]+/', $txtFile);
        $sentence = $lines[array_rand($lines)];
        $speed = DB::table('typingspeed')->select('speed')->where('user_id',$id)->orderBy('id', 'desc')->take(10)->get()->toArray();
        $accuracy =  DB::table('typingspeed')->select('accuracy')->where('user_id',$id)->orderBy('id', 'desc')->take(10)->get()->toArray();
        $speed = json_encode($speed);
        $speed = json_decode($speed,true);
        $accuracy = json_encode($accuracy);
        $accuracy = json_decode($accuracy,true);
        $list_speed=[];
            $list_accuracy=[];
            foreach($speed as $key=>$val)
            {
                $list_speed[$key]=$val['speed'];
            }
            foreach($accuracy as $key=>$val)
            {
                $list_accuracy[$key]=$val['accuracy'];
            }

            $typing = [];
            $status = [];
            $status = ['status'=>"inactive",'pusher_app_key'=>'','pusher_cluster'=>'','force_tls'=>''];
            $status = (object) ($status);
            $appTheme = ['sidebar_theme'=>'dark','header_color'=>''];
            $appTheme =(object) ($appTheme);
            $sidebarUserPermissions = [];
            $sidebarUserPermissions = ['view_overview_dashboard'=>4,'add_employees'=>4,'view_lead'=>5,'view_clients'=>5,'view_employees'=>5,'view_leave'=>5,'view_attendance'=>5,'view_holiday'=>5,'view_contract'=>5,'view_projects'=>5,'view_tasks'=>5,'view_timelogs'=>5,'view_estimates'=>5,'view_invoices'=>5,'view_payments'=>5,'view_expenses'=>5,'view_lead_proposals'=>5,'view_bankaccount'=>5,'view_tickets'=>5,'view_events'=>5,'view_notice'=>5,'view_task_report'=>5,'view_time_log_report'=>5,'view_finance_report'=>5,'view_income_expense_report'=>5,'view_leave_report'=>5,'view_attendance_report'=>5,'manage_company_setting'=>4];
            //$status = json_decode($status);
         //   $status = json_encode($status,true);
           // dd($status);
           $worksuitePlugins = [];
           $customLink = [];
            $this->checkListCompleted = 5;
            $this->checkListTotal = 6;
            $this->pushSetting = $status;
            $this->user =$user;
            $this->appTheme = $appTheme;
            $this->worksuitePlugins = $worksuitePlugins;
            $this->pusherSettings = $status;
             $this->sentence = $sentence;
             $this->activeTimerCount=0;
             $this->customLink = $customLink;
             $this->unreadMessagesCount = 0;
             $this->sidebarUserPermissions=$sidebarUserPermissions;
             $this->unreadNotificationCount =0;
            $this->pageTitle= "Typing";
             $this->list_speed = $list_speed;
             $this->list_accuracy = $list_accuracy;
             $this->typing = $typing;
            
            
        return view('typing::index',$this->data);
    }

    public function test_ajax(Request $request)
    {   
        $txtFile = file_get_contents(__DIR__.'/storage/paragraph.txt');
        $lines = preg_split('/[\n\r]+/', $txtFile);
        $sentence = $lines[array_rand($lines)];
        return response()->json(['sentence'=>$sentence]);
    }

    public function test_data(Request $request)
    {
        $user = session()->get('user');
        $id = $user->id;
        DB::table('typingspeed')->where('user_id',$id)->insert([
            'speed' => $request->NWPM,
            'user_id' => $id,
            'accuracy' => $request->Accuracy
        ]);
        $speed = DB::table('typingspeed')->select('speed')->where('user_id',$id)->orderBy('id', 'desc')->take(10)->get()->toArray();
        $accuracy =  DB::table('typingspeed')->select('accuracy')->where('user_id',$id)->orderBy('id', 'desc')->take(10)->get()->toArray();
        $speed = json_encode($speed);
        $speed = json_decode($speed,true);
        $accuracy = json_encode($accuracy);
        $accuracy = json_decode($accuracy,true);
        $list_speed=[];
            $list_accuracy=[];
            foreach($speed as $key=>$val)
            {
                $list_speed[$key]=$val['speed'];
            }
            foreach($accuracy as $key=>$val)
            {
                $list_accuracy[$key]=$val['accuracy'];
            }
        return response()->json(['list_speed'=>$list_speed,'list_accuracy'=>$list_accuracy]);
    }
}