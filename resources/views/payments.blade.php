@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row">
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
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Payment Id</th>
                                <th>Plan</th>
                                <th>Amount</th>
                                <th>Cart</th>
                                <th>Status</th>
                                <th>Created At (GMT 0)</th>
                                <th>Updated At (GMT 0)</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($payments as $payment)
                                <tr>
                                    <td>{{$payment->paymentId}}</td>
                                    <td>{{$payment->plan->name}}</td>
                                    <td>{{$payment->plan->amount}}</td>
                                    <td>{{$payment->cart}}</td>
                                    <td>{{\App\Models\Payment::$status[$payment->status]}}</td>
                                    <td>{{$payment->created_at}}</td>
                                    <td>{{$payment->updated_at}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
    </div>
@endsection