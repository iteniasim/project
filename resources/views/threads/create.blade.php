@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create A New Thread</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <form action="/threads" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="channel_id">Choose A Channel</label>
                            <select name="channel_id" id="channel_id" class="form-control" required>
                                <option value="">Choose one...</option>
                                @foreach ($channels as $channel)
                                <option value="{{ $channel->id }}"
                                    {{ old('channel_id') == $channel->id ? 'selected' : '' }}>{{ $channel->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <lable for="title">Title:</lable>
                            <input type="text" name="title" class="form-control" id="title" value="{{ old('title') }}"
                                required>
                        </div>
                        <div class="form-group">
                            <lable for="body">Body:</lable>
                            <textarea name="body" id="body" class="form-control" rows="5" required>{{ old('body') }}</textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <div class="form-group">
                                <button type="Submit" class="btn btn-primary">Publish</button>
                            </div>
                        </div>

                        @if (count($errors))
                        <ul class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        @endif
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection