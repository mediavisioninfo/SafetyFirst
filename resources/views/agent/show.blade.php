@extends('layouts.app')
@section('page-title')
    {{agentPrefix()}}{{$agent->agent->agent_id}} {{__('Detail')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{route('dashboard')}}"><h1>{{__('Dashboard')}}</h1></a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{route('agent.index')}}">{{__('Agent')}}</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">  {{agentPrefix()}}{{$agent->agent->agent_id}} {{__('Detail')}}</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4>  {{agentPrefix()}}{{$agent->agent->agent_id}}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-lg-4">
                            <div class="detail-group">
                                <h6>{{__('Name')}}</h6>
                                <p class="mb-20">{{ $agent->name }} </p>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div class="detail-group">
                                <h6>{{__('Email')}}</h6>
                                <p class="mb-20">{{ $agent->email }} </p>
                            </div>
                        </div>

                        <div class="col-md-4 col-lg-4">
                            <div class="detail-group">
                                <h6>{{__('Phone Number')}}</h6>
                                <p class="mb-20">
                                    <span> {{ $agent->phone_number }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-4 col-lg-4">
                            <div class="detail-group">
                                <h6>{{__('City')}}</h6>
                                <p class="mb-20">
                                    <span> {{ $agent->agent->city }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div class="detail-group">
                                <h6>{{__('State')}}</h6>
                                <p class="mb-20">
                                    <span> {{ $agent->agent->state }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div class="detail-group">
                                <h6>{{__('Country')}}</h6>
                                <p class="mb-20">
                                    <span> {{ $agent->agent->country }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div class="detail-group">
                                <h6>{{__('Zip Code')}}</h6>
                                <p class="mb-20">
                                    <span> {{ $agent->agent->zip_code }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div class="detail-group">
                                <h6>{{__('Address')}}</h6>
                                <p class="mb-20">
                                    <span> {{ $agent->agent->address }}</span>
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

                        <div class="col-md-6 col-lg-6">
                            <div class="detail-group">
                                <h6>{{__('Company')}}</h6>
                                <p class="mb-20">
                                    <span>  {{ $agent->agent->company }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6">
                            <div class="detail-group">
                                <h6>{{__('Tax Number')}}</h6>
                                <p class="mb-20">
                                    <span> {{ $agent->agent->tax_number }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-12">
                            <div class="detail-group">
                                <h6>{{__('Notes')}}</h6>
                                <p class="mb-20">
                                    <span> {{ $agent->agent->notes }}</span>
                                </p>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

