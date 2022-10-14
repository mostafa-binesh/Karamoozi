<?php 
use App\Models\User;
use Illuminate\Http\Request;
use Melipayamak\MelipayamakApi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;
use Illuminate\Contracts\Auth\Authenticatable;


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
Route::controller(AuthController::class)->group(function () {
    // Route::post('login', 'login');
    // Route::post('register', 'register');
    // Route::post('logout', 'logout');
    // Route::post('refresh', 'refresh');
    Route::post('reg-getphone', 'register_getphone');
    Route::post('reg-verify', 'register_verifycode');
    Route::post('reg-getinfo', 'register_getinfo');
    Route::post('login-getphone', 'login_getphone');
    Route::post('login-verify', 'login_verify');

});

// Route::controller(TodoController::class)->group(function () {
//     Route::get('todos', 'index');
//     Route::post('todo', 'store');
//     Route::get('todo/{id}', 'show');
//     Route::put('todo/{id}', 'update');
//     Route::delete('todo/{id}', 'destroy');
// }); 




?>