@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @include('thread._list')

            {{$threads->render()}}
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Search
                </div>
                <div class="card-body">
                    <form method="GET" action="/threads/search">
                        <div class="form-group">
                            <input class="form-control" type="text" placeholder="Search for something..." name="q">
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit">Search</button>
                        </div>
                    </form>
                </div>
            </div>

            @if(count($trending))
            <div class="card">
                <div class="card-header">
                    Trending Threads
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($trending as $thread)
                            <li class="list-group-item">
                                <a href="{{$thread->path}}">
                                    {{$thread->title}}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection