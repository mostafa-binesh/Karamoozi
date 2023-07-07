<?php

namespace App\Http\Controllers;

use App\Http\Resources\admin\StudentEvaluationResource;
use App\Models\IndustrySupervisor as ModelsIndustrySupervisor;
use App\Http\Resources\chats\message as ChatsMessage;
use App\Http\Resources\chats\receive;
use App\Http\Resources\chats\send;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\UserPaginationResource;
use App\Models\Chat;
use App\Models\Company;
use App\Models\Form2s;
use App\Models\Message;
use App\Models\Student;
use App\Models\StudentEvaluation;
use App\Models\WeeklyReport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Hekmatinasser\Verta\Verta;

class TestController extends Controller
{
    public function usersPagination(Request $req)
    {
        // return User::paginate(1);
        // return new UserPaginationResource(User::paginate(1));
        return response()->json(new UserPaginationResource(User::paginate(1)), 200);
        // return response()->json(User::paginate(1),200);
        // return new UserPaginationResource(User::find(1));

    }
    public function enum_test()
    {
        return User::find(2)->student->it();
    }
    public function verta()
    {
        // if entered day wasn't saturday, look for next saturday
        $datetime = verta('2023-01-7');
        echo $datetime->addDays(3);
        if ($datetime->dayOfWeek != 0) {
        }
    }



    public function sender($id)
    {
        $student = Chat::where("sender_id", $id)->get();
        return send::collection($student);
    }
    public function receive($id)
    {
        $student = Chat::where("receiver_id", $id)->get();
        return receive::collection($student);
    }

    public function create_chat(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'title' => 'required',
            'body' => 'required',
            'receiver' => 'different:sender',
            'sender' => 'different:receiver',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ], 400);
        }
        $chat = Chat::create([
            'title' => $req->title,
            'receiver_id' => $req->recevier_id,
            'sender_id' => $req->sender_id,
        ]);
        Message::create([
            'chat_id' => $chat->id,
            'body' => $req->body,
            'sender_id' => $req->sender_id,
        ]);
        return response()->json([
            'message' => 'مکاتبه ایجاد شد',
        ]);
    }
    public function create_message(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'body' => 'required',
            'receiver' => 'different:sender',
            'sender' => 'different:receiver',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ], 400);
        }
        Chat::create([
            'body' => $req->body,
            'receiver_id' => $req->recevier_id,
            'sender_id' => $req->sender_id,
            'chat_id' => $req->chat_id
        ]);
        return response()->json([
            'message' => 'مکاتبه ایجاد شد',
        ]);
    }

    public function get_message($id)
    {
        $messages = Message::where('chat_id', $id)->get();
        return message::collection($messages);
    }
    public function num2word(Request $req)
    {
        // return SELF::number_to_words($req->num);
        $words = array(
            0 => 'صفر',
            1 => 'یک',
            2 => 'دو',
            3 => 'سه',
            4 => 'چهار',
            5 => 'پنج',
            6 => 'شش',
            7 => 'هفت',
            8 => 'هشت',
            9 => 'نه',
            10 => 'ده',
            11 => 'یازده',
            12 => 'دوازده',
            13 => 'سیزده',
            14 => 'چهارده',
            15 => 'پانزده',
            16 => 'شانزده',
            17 => 'هفده',
            18 => 'هجده',
            19 => 'نوزده',
            20 => 'بیست',
            30 => 'سی',
            40 => 'چهل',
            50 => 'پنجاه',
            60 => 'شصت',
            70 => 'هفتاد',
            80 => 'هشتاد',
            90 => 'نود'
        );
        // return 1;
        $num_to_words = new NumberToWords($words);
        $number = 1234.56;
        $words = $num_to_words->convert(8521);
        return $words;
    }
    public function number_to_words($number)
    {
        $words = array(
            0 => 'صفر',
            1 => 'یک',
            2 => 'دو',
            3 => 'سه',
            4 => 'چهار',
            5 => 'پنج',
            6 => 'شش',
            7 => 'هفت',
            8 => 'هشت',
            9 => 'نه',
            10 => 'ده',
            11 => 'یازده',
            12 => 'دوازده',
            13 => 'سیزده',
            14 => 'چهارده',
            15 => 'پانزده',
            16 => 'شانزده',
            17 => 'هفده',
            18 => 'هجده',
            19 => 'نوزده',
            20 => 'بیست',
            30 => 'سی',
            40 => 'چهل',
            50 => 'پنجاه',
            60 => 'شصت',
            70 => 'هفتاد',
            80 => 'هشتاد',
            90 => 'نود'
        );
        if (!is_numeric($number)) {
            return false;
        }

        $num_words = array();

        // Split the number into integer and decimal parts
        $parts = explode('.', $number);

        // Handle the integer part
        $integer_part = $parts[0];
        if ($integer_part < 0) {
            $num_words[] = 'منفی';
            $integer_part = abs($integer_part);
        }

        $places = array(
            1000000000000,
            1000000000,
            1000000,
            1000,
            1
        );

        foreach ($places as $place) {
            if ($integer_part >= $place) {
                $current_place = floor($integer_part / $place);
                $integer_part %= $place;

                if ($current_place < 21) {
                    $num_words[] = $words[$current_place];
                } elseif ($current_place < 100) {
                    $num_words[] = $words[10 * floor($current_place / 10)];
                    if ($current_place % 10 > 0) {
                        $num_words[] = $words[$current_place % 10];
                    }
                } else {
                    $num_words[] = self::number_to_words($current_place, $words) . ' ' . $words[100];
                }

                if ($place > 1) {
                    $num_words[] = $words[$place];
                }
            }
        }
        if (count($parts) > 1 && is_numeric($parts[1])) {
            $decimal_part = $parts[1];
            $num_words[] = 'ممیز';
            for ($i = 0; $i < strlen($decimal_part); $i++) {
                if ($decimal_part[$i] < 21) {
                    $num_words[] = $words[$decimal_part[$i]];
                } elseif ($decimal_part[$i] < 100) {
                    $num_words[] = $words[10 * floor($decimal_part[$i] / 10)];
                    if ($decimal_part[$i] % 10 > 0) {
                        $num_words[] = $words[$decimal_part[$i] % 10];
                    }
                }
            }
        }

        return implode(' ', $num_words);
    }

    public function user_function(Request $req)
    {
        return ModelsIndustrySupervisor::find(1)->industrySupervisorStudents->ss($req);
    }

    public function studentTest()
    {
        $student = Student::findorfail(1);
        return $student->weeklyReport->reports;
    }

    public  function howManyDaysMustWork(Request $req)
    {
        $student = Student::where('student_number', $req->student_number)->firstorfail();
        return $student->howManyDaysMustWork($student->schedule());
    }

    public function allstudent(Request $req)
    {
        return Student::all();
    }

    public  function studentEval($id)
    {
        $student = Student::findorfail($id)->with('studentEvaluations')->first();
        return StudentEvaluationResource::collection($student->studentEvaluations);
    }

    public  function studentEvaluation($id)
    {
        $studentEvalution = StudentEvaluation::findorfail($id)->getRelations();
        return StudentEvaluationResource::collection($studentEvalution);
    }

    public function alluser(Request $req)
    {
        return User::all();
    }

    public function Form2(Request $req)
    {
        return Form2s::all();
    }

    public  function RoleUser(Request $req)
    {
        return User::find(1)->loadRoleInfo();
    }

    public function Null_test(Request $req)
    {
        return null;
    }

    public function allCompany(Request $req)
    {
        return Company::all();
    }

    public  function ReportWeekly(Request $req)
    {
        return WeeklyReport::all();
    }

    public function single_weeklyReport(Request $req, $id)
    {
        return WeeklyReport::where('student_id', $id)->get();
    }

    public function delete_weeklyReports(Request $req)
    {
        return WeeklyReport::where('student_id', $req->student_id)->delete();
    }

    public function dupliq(Request $req)
    {
        // ! it seems two where clouses with same names doesn't work as expected
        $students = Student::where('verified', 0)->where('verified', 1)->get();
        // ! return query would be something like this where verified = 0 and where verified = 1
        return $students;
    }

    public function TstValidation(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'age' => 'required|numeric|between:18,85',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ], 400);
        }
        return "problem solved";
    }

    public function queryTest(Request $req)
    {
        Student::where('id', '>', 1)->orWhere('id', '<', 100)->with('user')->get();
    }
}
