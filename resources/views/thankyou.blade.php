@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row">

            <div class="jumbotron text-xs-center">
                <h1 class="display-3">Thank You!</h1>
                <p class="lead"><strong>Your Payment process finished successfully</strong></p>
                <hr>
                <p>
                    Payment Id: {{$payment->paymentId}} <a href="/payments">Click here to see more details</a>
                </p>
                <p class="lead">
                    <a class="btn btn-primary btn-sm" href="/" role="button">Continue to homepage</a>
                </p>
            </div>
        </div>
    </div>
@endsection