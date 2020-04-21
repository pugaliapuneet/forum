@extends('layouts.app')

@section('content')
<thread-view :initial-replies-count="{{$thread->replies_count}}" inline-template>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="level">
                            <span class="flex">
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
                
                <replies :data="{{$thread->replies}}" @removed="repliesCount--"></replies>
    
                {{-- $replies->links()}} --}}
    
                @if(auth()->check())
                    <form action="{{$thread->path().'/replies'}}" method="POST">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <textarea name="body" id="body" class="form-control" placeholder="Have something to say?" rows="5"></textarea>
                        </div>
    
                        <button class="btn btn-default" type="submit">Post</button>
                    </form>
                @else
                    <p class="text-center">Please <a href="{{route('login')}}">sign in</a> to participate in this discussion.</p>
                @endif
            </div>
    
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        This thread was published {{$thread->created_at->diffForHumans()}} by
                        <a href="#">{{$thread->owner->name}}</a>, and currently has 
                        <span v-text="repliesCount"></span> {{Str::plural('comment', $thread->replies_count)}}.
                    </div>
                </div>
            </div>
    
        </div>
    </div>
</thread-view>
@endsection