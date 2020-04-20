@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="page-header">
                    <h1>
                        {{$profileUser->name}}
                        <small>since {{$profileUser->created_at->diffForHumans()}}</small>
                    </h1>
                </div>
        
                @foreach ($activities as $date => $records)
                    <h3 class="page-header">{{$date}}</h3>
                    @foreach($records as $activity)    
                        @include("profiles.activities.{$activity->type}", compact('activity'))
                    @endforeach
                @endforeach
        
                {{-- {{$threads->links()}} --}}
            </div>
        </div>
    </div>
@endsection