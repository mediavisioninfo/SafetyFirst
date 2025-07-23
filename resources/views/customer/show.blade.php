@extends('layouts.app')
@section('page-title')
    {{customerPrefix()}}{{$customer->customer->customer_id}} {{__('Detail')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{route('dashboard')}}"><h1>{{__('Dashboard')}}</h1></a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{route('customer.index')}}">{{__('Customer')}}</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">  {{customerPrefix()}}{{$customer->customer->customer_id}} {{__('Detail')}}</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4>  {{customerPrefix()}}{{$customer->customer->customer_id}}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-lg-4">
                            <div class="detail-group">
                                <h6>{{__('Name')}}</h6>
                                <p class="mb-20">{{ $customer->name }} </p>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div class="detail-group">
                                <h6>{{__('Email')}}</h6>
                                <p class="mb-20">{{ $customer->email }} </p>
                            </div>
                        </div>

                        <div class="col-md-4 col-lg-4">
                            <div class="detail-group">
                                <h6>{{__('Phone Number')}}</h6>
                                <p class="mb-20">
                                    <span> {{ $customer->phone_number }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-4 col-lg-4">
                            <div class="detail-group">
                                <h6>{{__('Gender')}}</h6>
                                <p class="mb-20">
                                    <span>  {{ $customer->customer->gender }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div class="detail-group">
                                <h6>{{__('City')}}</h6>
                                <p class="mb-20">
                                    <span> {{ $customer->customer->city }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div class="detail-group">
                                <h6>{{__('State')}}</h6>
                                <p class="mb-20">
                                    <span> {{ $customer->customer->state }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div class="detail-group">
                                <h6>{{__('Country')}}</h6>
                                <p class="mb-20">
                                    <span> {{ $customer->customer->country }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div class="detail-group">
                                <h6>{{__('Zip Code')}}</h6>
                                <p class="mb-20">
                                    <span> {{ $customer->customer->zip_code }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div class="detail-group">
                                <h6>{{__('Address')}}</h6>
                                <p class="mb-20">
                                    <span> {{ $customer->customer->address }}</span>
                                </p>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4>{{__('Additional Detail')}}</h4>
                </div>
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-4 col-lg-4">
                            <div class="detail-group">
                                <h6>{{__('Company')}}</h6>
                                <p class="mb-20">
                                    <span>  {{ $customer->customer->company }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div class="detail-group">
                                <h6>{{__('Tax Number')}}</h6>
                                <p class="mb-20">
                                    <span> {{ $customer->customer->tax_number }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div class="detail-group">
                                <h6>{{__('Date of Birth')}}</h6>
                                <p class="mb-20">
                                    <span> {{ $customer->customer->dob }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div class="detail-group">
                                <h6>{{__('Age')}}</h6>
                                <p class="mb-20">
                                    <span> {{ $customer->customer->age }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div class="detail-group">
                                <h6>{{__('Marital Status')}}</h6>
                                <p class="mb-20">
                                    <span> {{ $customer->customer->marital_status }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div class="detail-group">
                                <h6>{{__('Blood Group')}}</h6>
                                <p class="mb-20">
                                    <span> {{ $customer->customer->blood_group }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div class="detail-group">
                                <h6>{{__('Height')}}</h6>
                                <p class="mb-20">
                                    <span> {{ $customer->customer->height }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div class="detail-group">
                                <h6>{{__('Weight')}}</h6>
                                <p class="mb-20">
                                    <span> {{ $customer->customer->weight }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div class="detail-group">
                                <h6>{{__('Notes')}}</h6>
                                <p class="mb-20">
                                    <span> {{ $customer->customer->notes }}</span>
                                </p>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

