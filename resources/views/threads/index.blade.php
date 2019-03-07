@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Forum Threads</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    @foreach ($threads as $thread)
                    <article>
                        <div class="d-flex justify-content-between">
                            <div class="h5"><a href="{{ $thread->path() }}">{{ $thread->title }}</a></div>
                            <a href="{{ $thread->path() }}">{{ $thread->replies_count }}
                                {{ str_plural('reply',$thread->replies_count) }}</a>
                        </div>
                        <div>{{ $thread->body }}</div>
                    </article>
                    <hr>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection