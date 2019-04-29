@extends('admin.layouts.app', ['title' => 'Singer Create'])

@section('content')
    <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">Song Create</div>
            <div class="panel-body">
                @include('admin.elements._errors')

                <form method="post" action="{{ route('song.store') }}">
                    <div class="form-group">
                        @csrf

                        <label for="singer_id">Singer:</label>
                        <select name="singer_id" class="form-control">
                            @foreach ($singers as $key => $val)
                                <option value="{{ $key }}">{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" class="form-control" name="title" />
                    </div>
                    <div class="form-group">
                        <label for="lyrics">Lyrics:</label>
                        <textarea class="form-control" name="lyrics" rows="16"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
@endsection
