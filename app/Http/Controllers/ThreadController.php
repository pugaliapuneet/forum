<?php

namespace App\Http\Controllers;

use App\Filters\ThreadFilters;
use App\Thread;
use App\Channel;
use Illuminate\Http\Request;
use App\Rules\SpamFree;
use App\Trending;
use App\Rules\Recaptcha;

class ThreadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel, ThreadFilters $filters, Trending $trending)
    {
        $threads = $this->getThreads($channel, $filters);

        if (request()->wantsJson()) {
            return $threads;
        }

        return view('thread.index', [
            'threads' => $threads,
            'trending' => $trending->get(),
        ]);
    }

    public function getThreads($channel, $filters)
    {
        $threads = Thread::with('channel')->latest()->filter($filters);

        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }

        $threads = $threads->paginate(25);

        return $threads;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('thread.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Recaptcha $recaptcha)
    {
        request()->validate([
            'title' => ['required', new spamFree],
            'body' => ['required', new spamFree],
            'channel_id' => 'required|exists:channels,id',
            'g-recaptcha-response' => [$recaptcha],
        ]);

        $thread = Thread::create([
            'user_id' => auth()->id(),
            'channel_id' => request('channel_id'),
            'title' => request('title'),
            'body' => request('body'),
        ]);

        if(request()->wantsJson()) {
            return response($thread, 201);
        }

        return redirect($thread->path())
            ->with('flash', 'Your thread has been published!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show($channel, Thread $thread, Trending $trending)
    {
        if (auth()->check()) {
            auth()->user()->readThread($thread);
        }

        $trending->push($thread);

        $thread->increment('visits');

        return view("thread.show", compact('thread'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    public function update($channel, Thread $thread)
    {
        $this->authorize('update', $thread);

        $thread->update(request()->validate([
            'title' => ['required', new spamFree],
            'body' => ['required', new spamFree],
        ]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy($channel, Thread $thread)
    {
        $this->authorize('update', $thread);

        $thread->delete();

        if (request()->wantsJson()) {
            return response([], 204);
        }

        return redirect('/threads');
    }
}
