@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span>
                                Whoops, something like wrong
                            </span>
                        </div>
                    </div>
                    <div class="panel-body">
                        <span class="error">{{Session::get('error')}}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection