<?php
namespace Laraveltyping\Typing;
use Illuminate\Http\Request;
use Input,DB,Auth;
use App\Http\Controllers\Controller;

class TypingController extends Controller
{

    public function index()
    {   
        $id = 111;
        //$id = Auth::id();
        $txtFile = file_get_contents(__DIR__.'\storage\paragraph.txt');
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
        return view('typing::index',compact('sentence','list_speed','list_accuracy'));
    }

    public function test_ajax(Request $request)
    {   
        $txtFile = file_get_contents(__DIR__.'\storage\paragraph.txt');
        $lines = preg_split('/[\n\r]+/', $txtFile);
        $sentence = $lines[array_rand($lines)];
        return response()->json(['sentence'=>$sentence]);
    }

    public function test_data(Request $request)
    {

        //$id = Auth::id();
        $id = 111;
       
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