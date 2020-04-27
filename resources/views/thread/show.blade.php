@extends('layouts.app')

@section('header')
    <link href="/css/vendor/jquery.atwho.css" rel="stylesheet">
@endsection

@section('content')
<thread-view :thread="{{ $thread }}" inline-template>
    <div class="container">
        <div class="row">
            <div class="col-md-8" v-cloak>
                @include('thread._question')
                
                <replies :data="{{$thread->replies}}" 
                         @removed="repliesCount--" 
                         @added="repliesCount++"></replies>
            </div>
    
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        This thread was published {{$thread->created_at->diffForHumans()}} by
                        <a href="#">{{$thread->owner->name}}</a>, and currently has 
                        <span v-text="repliesCount"></span> {{Str::plural('comment', $thread->replies_count)}}.
                        
                        
                        <div>
                            <subscribe-button v-if="signedIn" :active="{{json_encode($thread->isSubscribedTo)}}"></subscribe-button>
                            <button class="btn btn-danger" @click="lock" v-if="authorize('isAdmin')" v-text="locked ? 'Unlock' : 'Lock'"></button>
                        </div>
                    </div>
                </div>
            </div>
    
        </div>
    </div>
</thread-view>
@endsection