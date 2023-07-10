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