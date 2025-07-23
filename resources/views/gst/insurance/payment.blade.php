@extends('layouts.app')
@section('page-title')
    {{__('Payment')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{route('dashboard')}}"><h1>{{__('Dashboard')}}</h1></a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{__('Payment')}}</a>
        </li>
    </ul>
@endsection
@section('card-action-btn')
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="display dataTable cell-border datatbl-advance">
                        <thead>
                        <tr>
                            <th>{{__('Customer')}}</th>
                            <th>{{__('Insurance')}}</th>
                            <th>{{__('Date')}}</th>
                            <th>{{__('Tax')}}</th>
                            <th>{{__('Premium')}}</th>
                            <th>{{__('Total')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($payments as $payment)

                            <tr>
                                <td>{{!empty($payment->insurances->customers)?$payment->insurances->customers->name:'-'}}</td>
                                <td><a href="{{route('insurance.show',\Illuminate\Support\Facades\Crypt::encrypt($payment->insurances->id))}}">{{insurancePrefix().$payment->insurances->insurance_id }}</a> </td>
                                <td>{{dateFormat($payment->payment_date)}}</td>
                                <td>

                                    @foreach(getTax($payment->insurances->policies->tax) as $tax)
                                        <span> {{$tax->tax}} ({{$tax->rate}}%) ({{priceFormat(taxRate($tax->rate,$payment->insurances->premium))}})</span>
                                    @endforeach
                                </td>
                                <td>
                                    {{priceFormat($payment->insurances->premium)}}
                                </td>
                                <td>
                                    {{priceFormat($payment->amount)}}
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

