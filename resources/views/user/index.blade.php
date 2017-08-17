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
                                    <td>
                                        <form>
                                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                                            <a href="/user/{{$user->id}}/edit" class="btn btn-default">Edit</a>
                                            <button type="button" data-id="{{$user->id}}" class="btn btn-default watch-user">Watch</button>
                                        </form>
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $users->links() }}
                    </div>
                </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="userHistoryModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">User History</h4>
                </div>
                <div class="modal-body">
                    <ul class="list-group history-list">
                    </ul>
                    <h4>Payments</h4>
                    <table class="table table-hover payment-history-table">
                        <thead>
                            <tr>
                                <th>Plan</th>
                                <th>Calls</th>
                                <th>PaymentId</th>
                                <th>Cart</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>

@endsection