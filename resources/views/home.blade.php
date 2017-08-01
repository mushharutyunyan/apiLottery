@extends('layouts.app')

@section('content')
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

                        <!-- Personal Access Tokens -->
                        <table class="table table-borderless">
                            <thead>
                            <tr>
                                <th></th>
                                <th></th>
                            </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td style="vertical-align: middle;">Name</td>
                                    <td style="vertical-align: middle;">{{Auth::user()['name']}}</td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle;">Call count</td>
                                    <td style="vertical-align: middle;">{{Auth::user()['count_requests']}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                {{--<passport-clients></passport-clients>--}}
                {{--<passport-authorized-clients></passport-authorized-clients>--}}
                <passport-personal-access-tokens></passport-personal-access-tokens>
            </div>
        </div>
    </div>
@endsection
