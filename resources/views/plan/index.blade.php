@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span>
                                Plans
                            </span>
                            <a href="/plan/create" class="btn btn-default">Create</a>
                        </div>
                    </div>

                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                <ul class="list-group">
                                        <li class="list-group-item">{{ session('status') }}</li>
                                </ul>
                            </div>
                        @endif
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($plans as $plan)
                                    <tr>
                                        <td>{{$plan->name}}</td>
                                        <td>{{$plan->description}}</td>
                                        <td>{{$plan->amount}}</td>
                                        <td>
                                            {!! Form::open(['url' => '/plan/'.$plan->id, 'method' => 'DELETE']) !!}
                                            @if(!$plan->main)
                                            <a class="btn btn-info" href="/plan/makeMain/{{$plan->id}}">Make Main</a>
                                            @endif
                                            <a class="btn btn-warning" href="/plan/{{$plan->id}}/edit">Edit</a>
                                            <button class="btn btn-danger">Delete</button>
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection