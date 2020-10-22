@extends('layouts.app')
@section('content')
    <div class="container">
        <form action="{{ route('posts.update', $post->id) }}" method="post">
            @csrf
            @method('PATCH')
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="form-group">
                <label for="exampleInputEmail1">Titolo</label>
                <input input="text" name="title" value ={{ $post->title }} class="form-control" placeholder="Inserisci il titolo">
            </div>
            <div class="form-group">
                <label for="body">Body</label>
                <textarea class="form-control" name="body" rows="3">{{ $post->title }}</textarea>
            </div>
            <div class="form-group">
                @foreach ($tags as $tag)
                    <label for="tag">{{ $tag->name }}</label>
                    {{-- tags[] è un array che prende piu dati selezionati --}}
                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}"{{ ($post->tags->contains($tag->id)) ? 'checked' : '' }}>
                @endforeach
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>


@endsection
