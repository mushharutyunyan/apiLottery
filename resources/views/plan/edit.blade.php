@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span>
                                Edit Plan
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
                        {{ Form::model($plan, ['url' => '/plan/'.$plan->id, 'method' => 'PUT']) }}
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" value="{{Input::old('name') ? Input::old('name') : $plan->name}}" name="name" id="name">
                        </div>
                            <div class="form-group">
                                <label for="name">Description:</label>
                                <textarea rows="3" name="description">{{Input::old('description') ? Input::old('description') : $plan->description}}</textarea>
                            </div>
                        <div class="form-group">
                            <label for="amount">Amount:</label>
                            <input type="text" class="form-control" name="amount" value="{{Input::old('amount') ? Input::old('amount') : $plan->amount}}" id="amount">
                        </div>
                        <div class="form-group">
                            <label for="calls">Calls:</label>
                            <input type="text" class="form-control" name="calls" value="{{Input::old('calls') ? Input::old('calls') : $plan->calls}}" id="calls">
                        </div>
                        <div class="checkbox">
                            @if(Input::old('main'))
                            <label><input name="main" type="checkbox" checked> Main</label>
                            @else
                                @if($plan->main)
                                    <label><input name="main" type="checkbox" checked> Main</label>
                                @else
                                    <label><input name="main" type="checkbox"> Main</label>
                                @endif
                            @endif
                        </div>
                        <button type="submit" class="btn btn-default">Submit</button>
                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection