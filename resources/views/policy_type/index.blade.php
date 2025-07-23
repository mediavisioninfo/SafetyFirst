@extends('layouts.app')
@section('page-title')
    {{__('Policy Type')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{route('dashboard')}}"><h1>{{__('Dashboard')}}</h1></a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{__('Policy Type')}}</a>
        </li>
    </ul>
@endsection
@section('card-action-btn')
    @if(Gate::check('create policy type'))
        <a class="btn btn-primary btn-sm ml-20 customModal" href="#" data-size="md"
           data-url="{{ route('policy-type.create') }}"
           data-title="{{__('Create Policy Type')}}"> <i class="ti-plus mr-5"></i>{{__('Create Policy Type')}}</a>
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
                            <th>{{__('Title')}}</th>
                            @if(Gate::check('edit policy type') || Gate::check('delete policy type'))
                                <th>{{__('Action')}}</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($policyTypes as $policyType)
                            <tr>
                                <td>{{ $policyType->title }} </td>
                                @if(Gate::check('edit policy type') || Gate::check('delete policy type'))
                                    <td>
                                        <div class="cart-action">
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['policy-type.destroy', $policyType->id]]) !!}
                                            @if(Gate::check('edit policy type'))
                                                <a class="text-success customModal" data-bs-toggle="tooltip"
                                                   data-bs-original-title="{{__('Edit')}}" href="#"
                                                   data-url="{{ route('policy-type.edit',$policyType->id) }}"
                                                   data-title="{{__('Edit Policy Type')}}"> <i data-feather="edit"></i></a>
                                            @endcan
                                            @if(Gate::check('delete policy type'))
                                                <a class=" text-danger confirm_dialog" data-bs-toggle="tooltip"
                                                   data-bs-original-title="{{__('Detete')}}" href="#"> <i
                                                        data-feather="trash-2"></i></a>
                                            @endcan
                                            {!! Form::close() !!}
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

