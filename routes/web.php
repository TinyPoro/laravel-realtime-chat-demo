<?php

use App\Events\MessagePosted;
use App\Room;
use App\Message;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $rooms = Room::all();
    return view('welcome', ['rooms' => $rooms]);
});

//Ngo Phuong Tuan
Route::get('/chat/{room_name}', function ($room_name) {
    $user = Auth::user();
    $rooms = Room::all();

    $room = Room::where('name', $room_name)->first();

    if($room == null) {
        if($user->id == 1) {
            $r = Room::firstOrCreate(array('name' => $room_name));

            return view('chat', ['room_id' => $r->id]);
        }
        else return redirect('/');
    }

    return view('chat', ['rooms' => $rooms, 'room_id' => $room->id]);
})->middleware('auth');

Route::get('/messages', function () {
    return App\Message::with('user')->get();
})->middleware('auth');

Route::post('/messages/{room_id}', function ($room_id) {
    // Store the new message
    $user = Auth::user();
    $room = Room::where('id', $room_id)->first();

    $message = new Message;
    $message->message = request()->get('message');
    $message->user_id = $user->id;
    $message->room_id = $room->id;
    $message->save();

    // Announce that a new message has been posted
    broadcast(new MessagePosted($message, $user))->toOthers();

    return ['status' => 'OK'];
})->middleware('auth');



Auth::routes();

Route::get('/home', 'HomeController@index');
