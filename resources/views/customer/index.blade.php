@extends('layouts.app')
@php
    $profile=asset(Storage::url('upload/profile/'));
@endphp
@section('page-title')
    {{__('Customer')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{route('dashboard')}}"><h1>{{__('Dashboard')}}</h1></a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">
                {{__('Customer')}}
            </a>
        </li>
    </ul>
@endsection
@section('card-action-btn')
    @if(Gate::check('manage customer'))
        <a class="btn btn-primary btn-sm ml-20" href="{{ route('customer.create') }}"> <i
                class="ti-plus mr-5"></i>
            {{__('Create Customer')}}
        </a>
    @endif
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
                            <th>{{__('Email')}}</th>
                            <th>{{__('Phone Number')}}</th>
                            <th>{{__('City')}}</th>
                            <th>{{__('State')}}</th>
                            <th>{{__('Country')}}</th>
                            <th>{{__('Company')}}</th>
                            <th>{{__('Action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($customers as $customer)
                            <tr>
                                <td>
                                    <div class="media">
                                        <div class="img-wrap">
                                            <img class="img-fluid" src="{{asset(Storage::url('upload/profile')).'/'.$customer->profile}}" alt="">
                                        </div>
                                        <div class="media-body">
                                            <h6>{{ $customer->name }}</h6>
                                            <p class="text-light">{{ customerPrefix().$customer->customer->id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $customer->email }} </td>
                                <td>{{ !empty($customer->phone_number)?$customer->phone_number:'-' }} </td>
                                <td>{{ !empty($customer->customer)?$customer->customer->city:'-' }} </td>
                                <td>{{ !empty($customer->customer)?$customer->customer->state:'-' }} </td>
                                <td>{{ !empty($customer->customer)?$customer->customer->country:'-' }} </td>
                                <td>{{ !empty($customer->customer)?$customer->customer->company:'-' }} </td>

                                <td>
                                    <div class="cart-action">
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['customer.destroy', $customer->id]]) !!}
                                        @can('edit customer')
                                            <a class="text-warning" data-bs-toggle="tooltip"
                                               data-bs-original-title="{{__('Detail')}}" href="{{ route('customer.show',\Illuminate\Support\Facades\Crypt::encrypt($customer->id)) }}"
                                              > <i data-feather="eye"></i></a>
                                        @endcan
                                        @can('edit customer')
                                            <a class="text-success" data-bs-toggle="tooltip"
                                               data-bs-original-title="{{__('Edit')}}" href="{{ route('customer.edit',\Illuminate\Support\Facades\Crypt::encrypt($customer->id)) }}"
                                              > <i data-feather="edit"></i></a>
                                        @endcan
                                        @can('delete customer')
                                            <a class=" text-danger confirm_dialog" data-bs-toggle="tooltip"
                                               data-bs-original-title="{{__('Detete')}}" href="#"> <i
                                                    data-feather="trash-2"></i></a>
                                        @endcan
                                        {!! Form::close() !!}
                                    </div>

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
