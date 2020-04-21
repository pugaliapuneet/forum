<reply :attributes="{{$reply}}" inline-template>

    <div class="card" id="reply-{{$reply->id}}">
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
            <div v-if="editing">
                <div class="form-group">
                    <textarea class="form-control" v-model="body" name="" id="" cols="30" rows="10" ></textarea>
                </div>

                <button class="btn btn-sm btn-primary" @click="update">Update</button>
                <button class="btn btn-sm btn-link" @click="editing = false">Cancel</button>
            </div>
            <div v-else v-text="body"></div>
        </div>
        
        @can('update', $reply)
            <div class="card-footer level">
                <button class="btn btn-secondary btn-sm mr-1" @click="editing = true">Edit</button>
                <form method="POST" action="/replies/{{$reply->id}}">
                    {{csrf_field()}}
                    {{method_field('DELETE')}}

                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </div>
        @endcan
    </div>

</reply>