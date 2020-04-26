@extends('layouts.app')

@section('header')
    <link href="/css/vendor/jquery.atwho.css" rel="stylesheet">
@endsection

@section('content')
<thread-view :data-replies-count="{{$thread->replies_count}}" :data-locked="{{$thread->locked}}" inline-template>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="level">
                            <span class="flex">
                                <img src="{{$thread->owner->avatar_path}}" width="25" alt="{{$thread->owner->name}}" class="mr-1">
                                <a href="/profiles/{{$thread->owner->name}}">{{$thread->owner->name}}</a> posted 
                                {{$thread->title}}
                            </span>
                            
                            @can('update', $thread)
                            <form action="{{$thread->path()}}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE')}}
    
                                <button type="submit" class="btn btn-link">Delete Thread</button>
                            </form>
                            @endcan
                        </div>
                    </div>
    
                    <div class="card-body">
                        {{$thread->body}}
                    </div>
                </div>
                
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
                            <button class="btn btn-danger" @click="locked = true" v-if="authorize('isAdmin') && !locked">Lock</button>
                        </div>
                    </div>
                </div>
            </div>
    
        </div>
    </div>
</thread-view>
@endsection