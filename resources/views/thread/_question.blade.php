<div class="card" v-if="!editing">
    <div class="card-header">
        <div class="level">
            <span class="flex">
                <img src="{{$thread->owner->avatar_path}}" width="25" alt="{{$thread->owner->name}}" class="mr-1">
                <a href="/profiles/{{$thread->owner->name}}">{{$thread->owner->name}}</a> posted 
                {{$thread->title}}
            </span>
        </div> 
    </div>

    <div class="card-body">
        {{$thread->body}}
    </div>

    @can('update', $thread)
    <div class="card-footer">
        <button class="btn btn-sm btn-secondary" @click="editing = true">Edit</button>
    </div>
    @endcan
</div>

<div class="card" v-else>
    <div class="card-header">
        <div class="level">
            <input type="text" value="{{$thread->title}}" class="form-control">
        </div>
    </div>

    <div class="card-body">
        <div class="form-group">
            <textarea class="form-control" name="" id="" cols="30" rows="10">{{$thread->body}}</textarea>
        </div>
    </div>

    @can('update', $thread)
    <div class="card-footer">
        <div class="level">
            {{-- <button class="btn btn-sm btn-secondary mr-1" @click="editing = true">Edit</button> --}}
            <button class="btn btn-sm btn-primary mr-1" @click="">Update</button>
            <button class="btn btn-sm btn-secondary" @click="editing = false">Cancel</button>
            
            @can('update', $thread)
            <form action="{{$thread->path()}}" method="POST" class="ml-a">
                {{ csrf_field() }}
                {{ method_field('DELETE')}}
    
                <button type="submit" class="btn btn-link">Delete Thread</button>
            </form>
            @endcan
        </div>
    </div>
    @endcan
</div>