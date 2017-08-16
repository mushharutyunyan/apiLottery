@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span>
                                Edit User Calls
                            </span>
                        </div>
                    </div>

                    <div class="panel-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul class="list-group">
                                    @foreach ($errors->all() as $error)
                                        <li class="list-group-item">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        {{ Form::model($user, ['url' => '/user/'.$user->id, 'method' => 'PUT']) }}
                        <div class="form-group">
                            <label for="count_requests">Calls:</label>
                            <input type="text" class="form-control" value="{{Input::old('count_requests') ? Input::old('count_requests') : $user->count_requests}}" name="count_requests" id="count_requests">
                        </div>
                        <button type="submit" class="btn btn-default">Submit</button>
                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection