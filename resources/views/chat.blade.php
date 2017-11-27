@extends('layouts.app')

@section('content')
    <chat-pager v-on="{ pagePrevious: goToPrevious, pageNext: goToNext}" :num_page={{$num_page}}></chat-pager>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Chatroom
                        <span id="room_id" display="hidden">{{$room_id}}</span>
                        <span class="badge pull-right">@{{ usersInRoom.length }}</span>
                    </div>

                    <chat-log :messages="messages" v-on:message-deleted="removeMessage"></chat-log>
                    <chat-composer v-on:messagesent="addMessage" id="composer"></chat-composer>
                </div>
            </div>
        </div>
    </div>
@endsection
