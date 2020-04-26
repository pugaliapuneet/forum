<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Reply;
use App\User;
use Illuminate\Http\Request;
use App\Rules\SpamFree;
use App\Http\Requests\CreatePostRequest;

class RepliesController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(5);
    }

    public function store($channelId, Thread $thread, CreatePostRequest $form)
    {
        if($thread->locked) {
            return response('Thread is locked', 422);
        }

        $reply = $thread->addReply([
            'body' => request('body'), 
            'user_id' => auth()->id(),
        ]);

        return $reply->load('owner');
    }

    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);
    
        $this->validate(request(), [
            'body' => ['required', new spamFree],
        ]);

        $reply->update(request(['body']));
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);
        
        $reply->delete();

        if(request()->expectsJson()) {
            return response(['status' => 'Reply deleted']);
        }

        return back();
    }
}
