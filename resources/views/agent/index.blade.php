@extends('layouts.app')
@php
    $profile=asset(Storage::url('upload/profile/'));
@endphp
@section('page-title')
    {{__('Agent')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{route('dashboard')}}"><h1>{{__('Dashboard')}}</h1></a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">
                {{__('Agent')}}
            </a>
        </li>
    </ul>
@endsection
@section('card-action-btn')
    @if(Gate::check('manage agent'))
        <a class="btn btn-primary btn-sm ml-20" href="{{ route('agent.create') }}"> <i
                class="ti-plus mr-5"></i>
            {{__('Create Agent')}}
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
                            <th>{{__('Agent')}}</th>
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
                        @foreach ($agents as $agent)
                            <tr>
                                <td>
                                    <div class="media">
                                        <div class="img-wrap">
                                            <img class="img-fluid" src="{{asset(Storage::url('upload/profile')).'/'.$agent->profile}}" alt="">
                                        </div>
                                        <div class="media-body">
                                            <h6>{{ $agent->name }}</h6>
                                            <p class="text-light">{{ agentPrefix().$agent->agent->id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $agent->email }} </td>
                                <td>{{ !empty($agent->phone_number)?$agent->phone_number:'-' }} </td>
                                <td>{{ !empty($agent->agent)?$agent->agent->city:'-' }} </td>
                                <td>{{ !empty($agent->agent)?$agent->agent->state:'-' }} </td>
                                <td>{{ !empty($agent->agent)?$agent->agent->country:'-' }} </td>
                                <td>{{ !empty($agent->agent)?$agent->agent->company:'-' }} </td>

                                <td>
                                    <div class="cart-action">
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['agent.destroy', $agent->id]]) !!}
                                        @can('edit agent')
                                            <a class="text-warning" data-bs-toggle="tooltip"
                                               data-bs-original-title="{{__('Detail')}}" href="{{ route('agent.show',\Illuminate\Support\Facades\Crypt::encrypt($agent->id)) }}"
                                              > <i data-feather="eye"></i></a>
                                        @endcan
                                        @can('edit agent')
                                            <a class="text-success" data-bs-toggle="tooltip"
                                               data-bs-original-title="{{__('Edit')}}" href="{{ route('agent.edit',\Illuminate\Support\Facades\Crypt::encrypt($agent->id)) }}"
                                              > <i data-feather="edit"></i></a>
                                        @endcan
                                        @can('delete agent')
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
