@extends('layouts.app')
@section('page-title')
    {{__('Policy Sub Type')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{route('dashboard')}}"><h1>{{__('Dashboard')}}</h1></a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{__('Policy Sub Type')}}</a>
        </li>
    </ul>
@endsection
@section('card-action-btn')
    @if(Gate::check('create policy sub type'))
        <a class="btn btn-primary btn-sm ml-20 customModal" href="#" data-size="md"
           data-url="{{ route('policy-sub-type.create') }}"
           data-title="{{__('Create Policy Sub Type')}}"> <i class="ti-plus mr-5"></i>{{__('Create Policy Sub Type')}}</a>
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
                            <th>{{__('Type')}}</th>
                            @if(Gate::check('edit policy sub type') || Gate::check('delete policy sub type'))
                                <th>{{__('Action')}}</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($policySubTypes as $policySubType)
                            <tr>
                                <td>{{ $policySubType->title }} </td>
                                <td>{{ !empty($policySubType->types)?$policySubType->types->title:'-' }} </td>
                                @if(Gate::check('edit policy sub type') || Gate::check('delete policy sub type'))
                                    <td>
                                        <div class="cart-action">
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['policy-sub-type.destroy', $policySubType->id]]) !!}
                                            @if(Gate::check('edit policy sub type'))
                                                <a class="text-success customModal" data-bs-toggle="tooltip"
                                                   data-bs-original-title="{{__('Edit')}}" href="#"
                                                   data-url="{{ route('policy-sub-type.edit',$policySubType->id) }}"
                                                   data-title="{{__('Edit Policy Sub Type')}}"> <i data-feather="edit"></i></a>
                                            @endcan
                                            @if(Gate::check('delete policy sub type'))
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

