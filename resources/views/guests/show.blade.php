@extends('layouts.app')
@section('content')

<div class="container">
    <div class="card-group">
        <div class="row">
                <div class="col-sm-12 pt-3">
                    <div class="card">
                        <img class="card-img-top" src="{{ Storage::url($post->img) }}" alt="{{ $post->title }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $post->title }}</h5>
                            <p class="card-text">{{ $post->body }}</p>
                            <p class="card-text"><small class="text-muted">{{ $post->user->name }}</small></p>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
@endsection
