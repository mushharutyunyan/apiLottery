@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span>
                                Create Plan
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
                        {!! Form::open(['url' => '/plan']) !!}
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" class="form-control" name="name" id="name">
                            </div>
                            <div class="form-group">
                                <label for="name">Description:</label>
                                <textarea rows="3" name="description"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="amount">Amount:</label>
                                <input type="text" class="form-control" name="amount" id="amount">
                            </div>
                            <div class="form-group">
                                <label for="calls">Calls:</label>
                                <input type="text" class="form-control" name="calls" id="calls">
                            </div>
                            <div class="checkbox">
                                <label><input name="main" type="checkbox"> Main</label>
                            </div>
                            <button type="submit" class="btn btn-default">Submit</button>
                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection