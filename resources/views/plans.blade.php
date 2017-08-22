@extends('layouts.main')

@section('content')
    <div id="wrapper" class="clearfix">
        <div id="content">
            <div class="content-wrap">
                <div class="container clearfix">
                    <div class="dotted-divider"></div>
                    <h2 id="pricing-signup" class="center">Choose your Plan</h2>
                    <div class="pricing pricing2 clearfix">
                        @foreach($plans as $plan)
                        @if($plan->main)
                            <?php $active = 'best-price'; ?>
                            <?php $active_button = 'inverse'; ?>
                        @else
                            <?php $active = ''; ?>
                            <?php $active_button = ''; ?>
                        @endif
                        <div class="pricing-wrap {{$active}}">

                            <div class="pricing-inner">
                                <div class="pricing-title"><h4>{{$plan->name}}<span>{{$plan->description}}</span></h4></div>
                                <div class="pricing-price">${{explode(".",$plan->amount)[0]}}<span class="price-sub">{{explode(".",$plan->amount)[1]}}</span></div>
                                <div class="pricing-features">
                                    <ul>
                                        <li></li>
                                        <li><span>{{$plan->calls}}</span> Calls</li>
                                        <li></li>
                                    </ul>
                                </div>
                                <div class="pricing-action">
                                    <form method="POST" action="{{ url('/payment/payWithPaypal') }}">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="plan_id" value="{{$plan->id}}">
                                        <button class="simple-button {{$active_button}}">Get Started</button>
                                    </form>
                                </div>

                            </div>

                        </div>
                        @endforeach

                    </div>


                </div>


            </div>
        </div>
    </div>
@endsection







