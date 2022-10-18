<?php 
use App\Models\User;
use Illuminate\Http\Request;
use Melipayamak\MelipayamakApi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;
use Illuminate\Contracts\Auth\Authenticatable;
use Ybazli\Faker\Facades\Faker;

// NOTE: ALL ROUTES BEGINS WITH LOCALHOST/API/...

// Route::get('/sms', function () {
//     try{
//         $username = '9390565606';
//         $password = 'C#25S';
//         $api = new MelipayamakApi($username,$password);
//         $sms = $api->sms();
//         $to = '09390565606';
//         $from = '50004001565606'; //correct one
//         // $from = '50004001565605';
//         // $text = 'تست وب سرویس ملی پیامک';
//         $text = 'سیستم پیامکی هم با ای پی آی اکی کردم. ورود و عضویت هم ردیفه';
//         $response = $sms->send($to,$from,$text);
//         // $json = json_decode($response);
//         // echo $json->Value; //RecId or Error Number 
//         echo $response;
//         // {"Value":"4786346122764072700","RetStatus":1,"StrRetStatus":"Ok"}
//         // {"Value":"5","RetStatus":9,"StrRetStatus":"InvalidNumber"}
//     }catch(Exception $e){
//         echo $e->getMessage();
//     }
// });
Route::get('users/{id}', function ($id) {
    $user = User::all();
    return response()->json(['status' => 'ok','user' => $user]);
});
Route::get('xy', function () {
    $x = Faker::firstName();
    return $x;
});
Route::get('/home', function()
{
    return response()->json([
        'coms' => 'hello',
        'news' => 'news',
    ]);
});
Route::controller(AuthController::class)->group(
    // ['middleware' => ['cors']],
    function () {
    // Route::post('login', 'login');
    // Route::post('register', 'register');
    // Route::post('logout', 'logout');
    // Route::post('refresh', 'refresh');
    Route::post('reg-getphone', 'register_getphone'); 
    Route::post('reg-verify', 'register_verifycode');
    Route::post('reg-getinfo', 'register_getinfo');
    Route::post('login-getphone', 'login_getphone');
    Route::post('login-verify', 'login_verify');
    
    Route::get('comss', 'all_comms');
    Route::post('add-comm', 'add_comm');
    Route::post('delete-comm', 'delete_comm');
    
});
Route::get('/greeting', function () {
    return 'Hello World';
});
// Route::controller(TodoController::class)->group(function () {
//     Route::get('todos', 'index');
//     Route::post('todo', 'store');
//     Route::get('todo/{id}', 'show');
//     Route::put('todo/{id}', 'update');
//     Route::delete('todo/{id}', 'destroy');
// }); 
?>