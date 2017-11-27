<?php

use App\Events\MessagePosted;
use App\Events\DeleteMessage;
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
    
    // calculate the number of page
    $paginate = config('app.paginate');
    $num_mess = Message::where('room_id', $room->id)->count();
    $num_page = ceil($num_mess / $paginate);
    
    if($room == null) {
        if($user->id == 1) {
            $r = Room::firstOrCreate(array('name' => $room_name));

            return view('chat', ['rooms' => $rooms, 'room_id' => $r->id, 'num_page' => $num_page]);
        }
        else return redirect('/');
    }

    return view('chat', ['rooms' => $rooms, 'room_id' => $room->id, 'num_page' => $num_page]);
})->middleware('auth');

Route::get('/messages/{room_id}/{cur_page}', function ($room_id, $cur_page) {
    $paginate = config('app.paginate');
    return Message::with('user')->where('room_id', $room_id)->take($paginate)->skip($paginate*($cur_page-1))->get();
})->middleware('auth');

Route::get('/messages/{room_id}', function ($room_id) {
    $paginate = config('app.paginate');
    $num_mess =  Message::with('user')->where('room_id', $room_id)->count();
    $page = ceil($num_mess/$paginate);
    return Message::with('user')->where('room_id', $room_id)->take($paginate)->skip(($page-1)*$paginate)->get();
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

    return ['id' => $message->id];
})->middleware('auth');

Route::post('/deletemsg/{id}', function ($id) {
    $message = Message::where('id', $id)->first();
    
    
    // Announce that a new message has been posted
    broadcast(new DeleteMessage($message))->toOthers();
    $message->delete();
    
    
    
    return ['status' => 'OK'];
})->middleware('auth');



Auth::routes();

Route::get('/home', function() {
    return redirect('/');
});
