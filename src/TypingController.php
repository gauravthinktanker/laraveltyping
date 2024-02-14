<?php
namespace Laraveltyping\Typing;
use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\Controller;
use App\Models\User;
class TypingController extends Controller
{
   
    public function index(Request $request)
    {   
        

         $user = session()->get('user');
         $sidebar_user_perms = session()->get('sidebar_user_perms');
         $pusher_settings = session()->get('pusher_settings');
         $push_setting = session()->get('push_setting');
         $id = $user->id;
         $appTheme = session()->get('admin_theme');
         $sidebarUserPermissions = $sidebar_user_perms;
         $this->currentRouteName = "typing";
         $worksuitePlugins = [];
         $customLink = [];
         $this->checkListCompleted = 5;
         $this->checkListTotal = 6;
         $this->pushSetting = $push_setting;
         $this->user = $user;
         $this->appTheme = $appTheme;
         $this->worksuitePlugins = $worksuitePlugins;
         $this->pusherSettings = $pusher_settings;
         $this->activeTimerCount = 0;
         $this->customLink = $customLink;
         $this->unreadMessagesCount = 0;
         $this->sidebarUserPermissions = $sidebarUserPermissions;
         $this->unreadNotificationCount = 0;
         $this->pageTitle = "Typing";

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

             $this->sentence = $sentence;
             $this->list_speed = $list_speed;
             $this->list_accuracy = $list_accuracy;
            
            
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
        date_default_timezone_set('Asia/Kolkata');
        DB::table('typingspeed')->where('user_id',$id)->insert([
            'speed' => $request->NWPM,
            'user_id' => $id,
            'accuracy' => $request->Accuracy,
            'created_at'=>date('Y-m-d h:i:s')
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
    public function typingSpeed(Request $request)
    {

        $user = session()->get('user');
        $sidebar_user_perms = session()->get('sidebar_user_perms');
        $pusher_settings = session()->get('pusher_settings');
        $push_setting = session()->get('push_setting');
        $id = $user->id;
        $appTheme = session()->get('admin_theme');
        $sidebarUserPermissions = $sidebar_user_perms;
        $this->currentRouteName = "typing-speed";
        $worksuitePlugins = [];
        $customLink = [];
        $this->checkListCompleted = 5;
        $this->checkListTotal = 6;
        $this->pushSetting = $push_setting;
        $this->user = $user;
        $this->appTheme = $appTheme;
        $this->worksuitePlugins = $worksuitePlugins;
        $this->pusherSettings = $pusher_settings;
        $this->activeTimerCount = 0;
        $this->customLink = $customLink;
        $this->unreadMessagesCount = 0;
        $this->sidebarUserPermissions = $sidebarUserPermissions;
        $this->unreadNotificationCount = 0;
        $this->pageTitle = "Typing Master";

        if (isset($request->month)) {
            $month = intval($request['month']) + 1;
            $user_id = $request->user;
        } else {
            $user_id = $request->user;
            $month = date('m');
        }
        // dd($user);

        if (isset($request->year)) {
            $year = $request->year;
        } else {
            $year = date('Y');
        }

        if ($request->ajax()) {
    $where_str = "1 =?";
    $where_param = array('1');

    if ($request->has('search.value')) {
        $search = $request->search['value'];
        $where_str .= " and (users.name like \"%{$search}%\")";
    }

    $columns = array('speed', 'accuracy', 'users.name', 'date');

    if ($user_id != null) {
      
       // Logic for when $user_id is not null (specific user)
    $average_speed_accuracy = DB::table('typingspeed')
        ->selectRaw('user_id, MAX(speed) as max_speed, accuracy, MAX(created_at) as created_at')
        ->whereRaw('MONTH(created_at) = ? AND YEAR(created_at) = ?', [$month, $year])
        ->groupBy('user_id');

    $average_speed_accuracy = DB::table('users')
        ->joinSub($average_speed_accuracy, 'ts', function ($join) {
            $join->on('users.id', '=', 'ts.user_id');
        })
        ->selectRaw('users.id as user_id, ts.max_speed as speed, ts.accuracy, MAX(DATE_FORMAT(ts.created_at, "%d/%m/%Y")) as date, users.name')
        ->where('users.status', 'active')
        ->where('users.id', $user_id); // Filter by specific user ID
    } else {
        $average_speed_accuracy = DB::table('typingspeed')
            ->selectRaw('user_id, MAX(speed) as max_speed, accuracy, MAX(created_at) as created_at')
            ->whereRaw('MONTH(created_at) = ? AND YEAR(created_at) = ?', [$month, $year])
            ->groupBy('user_id');

        $average_speed_accuracy = DB::table('users')
            ->joinSub($average_speed_accuracy, 'ts', function ($join) {
                $join->on('users.id', '=', 'ts.user_id');
            })
            ->selectRaw('users.id as user_id, ts.max_speed as speed, ts.accuracy, MAX(DATE_FORMAT(ts.created_at, "%d/%m/%Y")) as date, users.name')
            ->where('users.status', 'active')
            ->groupBy('users.id', 'users.name');
    }

    $average_speed_accuracy_count = $average_speed_accuracy->count();

    if ($request->has('start') && $request->get('length') != '-1') {
        $average_speed_accuracy = $average_speed_accuracy->take($request->get('length'))
            ->skip($request->get('start'));
    }

    if ($request->has('order')) {
        $columns = ['user_id', 'speed', 'accuracy', 'date', 'users.name'];

        foreach ($request->input('order') as $order) {
            $column = $columns[$order['column']];
            if ($column == 'date') {
                $average_speed_accuracy  = $average_speed_accuracy->orderByRaw('MAX(DATE_FORMAT(created_at, "%Y-%m-%d")) ' . $order['dir']);
            } else {
                $average_speed_accuracy  = $average_speed_accuracy->orderBy($column, $order['dir']);
            }
        }
    }

    $average_speed_accuracy  = $average_speed_accuracy->get()->toArray();

    $array_data = json_decode(json_encode($average_speed_accuracy), true);

    foreach ($array_data as $key => $value) {
        $team_member = DB::table("users")->select('name')->where('id', $value['user_id'])->where('status', 'active')->first();

        $array_data[$key]['user_id'] = $team_member ? $team_member->name : '';
    }

    $response['iTotalDisplayRecords'] = $average_speed_accuracy_count;
    $response['iTotalRecords'] = $average_speed_accuracy_count;
    $response['sEcho'] = intval($request->get('sEcho'));
    $response['aaData'] = $array_data;

    return $response;
}



        $all_users = [];
        if ($user->roles[0]['name'] == 'admin') {
            $logged_user_id = '';

            $all_users =User::withRole('employee')->where('status', 'active')->orderBy('name')->pluck('name', 'id')->toArray();

        }

        return view('typing::typing_speed', ['all_users' => $all_users], $this->data);
    }
}