<div class="card">
    <div class="card-header">
        <div class="level">
            <h6 class="flex">
                <a href="/profiles/{{$reply->owner->name}}">
                    {{$reply->owner->name}}
                </a> said 
                {{$reply->created_at->diffForHumans()}}
            </h6>

            <div>
                <form action="/replies/{{$reply->id}}/favorites" method="POST">
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-light" {{$reply->isFavorited() ? 'disabled' : ''}}>
                        {{ $reply->favorites_count }} {{Str::plural('Favorite', $reply->favorites_count)}}
                    </button>
                </form>
            </div>

        </div>
    </div>

    <div class="card-body">
        {{$reply->body}}
    </div>
</div>