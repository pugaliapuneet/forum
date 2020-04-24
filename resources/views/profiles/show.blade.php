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
                    @can('update', $profileUser)
                        <form action="{{route('avatar', $profileUser)}}" 
                              method="POST" 
                              enctype="multipart/form-data">
                            {{csrf_field()}}
                            <input type="file" name="avatar">
                            <button type="submit" class="btn btn-primary">Add Avatar</button>
                        </form>
                    @endcan
                    <img src="{{asset($profileUser->avatar())}}" width="50" height="50">
                </div>
        
                @forelse ($activities as $date => $records)
                    <h3 class="page-header">{{$date}}</h3>

                    @foreach($records as $activity)
                        @if(view()->exists("profiles.activities.{$activity->type}"))
                            @include("profiles.activities.{$activity->type}", compact('activity'))
                        @endif
                    @endforeach
                @empty
                    <p>There is no activity for this user yet.</p>
                @endforelse
        
                {{-- {{$threads->links()}} --}}
            </div>
        </div>
    </div>
@endsection