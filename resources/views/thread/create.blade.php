@extends('layouts.app')

@section('header')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create a New Thread</div>

                <div class="card-body">
                   <form method="POST" action="/threads">
                        {{csrf_field()}}
                       <div class="form-group">
                            <label for="channel_id">Choose a Channel:</label>
                            <select name="channel_id" id="channel_id" class="form-control" required>
                                <option value="">Choose one...</option>
                                @foreach ($channels as $channel)
                                    <option value="{{$channel->id}}" {{old('channel_id') == $channel->id ? 'selected' : ''}}>
                                        {{$channel->name}}
                                    </option>
                                @endforeach
                            </select>
                       </div>
                       <div class="form-group">
                            <label for="title">Title:</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{old('title')}}" required>
                       </div>
                       <div class="form-group">
                            <label for="title">Body:</label>
                            <wysiwyg name="body"></wysiwyg>
                            {{-- <textarea name="body" id="body" class="form-control" rows="8" required>{{old('body')}}</textarea> --}}
                       </div>
                       <div class="form-group">
                           <div class="g-recaptcha" data-sitekey="6LfIbu4UAAAAAAlSUUSSe1rb1dPBXFsKtb5ZnBwN"></div>
                       </div>
                       <button type="submit" class="btn btn-primary">Publish</button>
                   </form>
                   @if(count($errors))
                        <ul class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{$error}}</li>
                            @endforeach
                        </ul>
                   @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection