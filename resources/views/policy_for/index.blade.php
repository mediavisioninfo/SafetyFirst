@extends('layouts.app')
@section('page-title')
    {{__('Policy For')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{route('dashboard')}}"><h1>{{__('Dashboard')}}</h1></a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{__('Policy For')}}</a>
        </li>
    </ul>
@endsection
@section('card-action-btn')
    @if(Gate::check('create policy for'))
        <a class="btn btn-primary btn-sm ml-20 customModal" href="#" data-size="md"
           data-url="{{ route('policy-for.create') }}"
           data-title="{{__('Create Policy For')}}"> <i class="ti-plus mr-5"></i>{{__('Create Policy For')}}</a>
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
                            <th>{{__('Policy Buy For')}}</th>
                            <th>{{__('Policy Type')}}</th>
                            @if(Gate::check('edit policy for') || Gate::check('delete policy for'))
                                <th>{{__('Action')}}</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($policyFors as $policyFor)
                            <tr>
                                <td>{{ $policyFor->buying_for }} </td>
                                <td>{{ !empty($policyFor->types)?$policyFor->types->title:'-' }} </td>
                                @if(Gate::check('edit policy for') || Gate::check('delete policy for'))
                                    <td>
                                        <div class="cart-action">
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['policy-for.destroy', $policyFor->id]]) !!}
                                            @if(Gate::check('edit policy for'))
                                                <a class="text-success customModal" data-bs-toggle="tooltip"
                                                   data-bs-original-title="{{__('Edit')}}" href="#"
                                                   data-url="{{ route('policy-for.edit',$policyFor->id) }}"
                                                   data-title="{{__('Edit Policy For')}}"> <i data-feather="edit"></i></a>
                                            @endcan
                                            @if(Gate::check('delete policy for'))
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

