@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span>
                                Statistics
                            </span>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="row">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul class="list-group">
                                    @foreach ($errors->all() as $error)
                                        <li class="list-group-item">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="/statistic" method="POST">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="col-sm-4"><input class="form-control" placeholder="Start" value="{{$start}}" id="filter_start" name="start"></div>
                            <div class="col-sm-4"><input class="form-control" placeholder="End" value="{{$end}}" id="filter_end" name="end"></div>
                            <div class="col-sm-2"><button class="btn btn-info">Search</button></div>
                        </form>
                    </div>
                    <div style="margin-bottom: 15px;"></div>
                    <ul class="nav nav-tabs" style="margin-left:0px !important">
                        <li class="active"><a data-toggle="tab" href="#global">All</a></li>
                        <li><a data-toggle="tab" href="#user">By Users</a></li>
                    </ul>

                    <div class="tab-content">
                        <div id="global" class="tab-pane fade in active">
                            <div style="margin-bottom: 15px;"></div>
                            <p>All calls count: {{$all}}</p>
                        </div>
                        <div id="user" class="tab-pane fade">
                            <div style="margin-bottom: 15px;"></div>
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Calls</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($user_calls as $calls)
                                    <tr>
                                        <td>{{$calls['user']}}</td>
                                        <td>{{$calls['calls']}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection