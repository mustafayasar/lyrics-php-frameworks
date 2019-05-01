@extends('admin.layouts.app', ['title' => 'Song Edit'])

@section('content')
    <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">Song Edit</div>
            <div class="panel-body">
                @include('admin.elements._errors')

                <form method="post" action="{{ route('song.update', $song->id) }}">
                    <div class="form-group">
                        @csrf
                        @method('PATCH')

                        <label for="singer_id">Singer:</label>
                        <select name="singer_id" class="form-control">
                            @foreach ($singers as $key => $val)
                                <option value="{{ $key }}" @if ($key == $song->singer_id) selected @endif>{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" class="form-control" name="title" value="{{ $song->title }}" />
                    </div>
                    <div class="form-group">
                        <label for="title">Slug:</label>
                        <input type="text" class="form-control" name="slug" value="{{ $song->slug }}" />
                    </div>
                    <div class="form-group">
                        <label for="lyrics">Lyrics:</label>
                        <textarea class="form-control" name="lyrics" rows="16">{{ $song->lyrics }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="hit">Hit:</label>
                        <input type="text" class="form-control" name="hit" value="{{ $song->hit }}" disabled />
                    </div>
                    <div class="form-group">
                        <label for="name">Status:</label>
                        <select name="status" class="form-control">
                            @foreach ($statuses as $key => $val)
                                <option value="{{ $key }}" @if ($key == $song->status) selected @endif>{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
@endsection
