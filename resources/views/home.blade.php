@extends('layouts.app')

@section('content')
    <style>
        .wordwrap {
            white-space: pre-wrap;      /* CSS3 */
            white-space: -moz-pre-wrap; /* Firefox */
            white-space: -pre-wrap;     /* Opera <7 */
            white-space: -o-pre-wrap;   /* Opera 7 */
            word-wrap: break-word;      /* IE */
        }
    </style>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span>
                                Personal Info
                            </span>
                        </div>
                    </div>

                    <div class="panel-body">
                        <ul class="list-group">
                            <li class="list-group-item">Name: {{Auth::user()['name']}}</li>
                            <li class="list-group-item">Call count: {{Auth::user()['count_requests']}}</li>
                            <li class="list-group-item">Token: <textarea readonly class="form-control" rows="5">{{$token}}</textarea></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
