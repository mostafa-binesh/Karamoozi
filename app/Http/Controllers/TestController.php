<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\UserPaginationResource;
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
        // echo $datetime->addDay()->addDay()->addDay();
        // echo $datetime->addDay()->addDay();
        echo $datetime->addDays(3);
        if ($datetime->dayOfWeek != 0) {
        }
        // if now friday
        // echo ;
    }
}
