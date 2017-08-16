@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span>
                                Users
                            </span>
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
                                <th>Role</th>
                                <th>Email</th>
                                <th>Calls</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{$user->name}}</td>
                                    <td>{{\App\Models\User::$role[$user->role]}}</td>
                                    <td>{{$user->email}}</td>
                                    <td>{{$user->count_requests}}</td>
                                    <td><a href="/user/{{$user->id}}/edit" class="btn btn-default">Edit</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $users->links() }}
                    </div>
                </div>
        </div>
    </div>
@endsection