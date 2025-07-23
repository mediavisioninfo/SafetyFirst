@extends('layouts.app')
@section('page-title')
    {{__('Policy Duration')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{route('dashboard')}}"><h1>{{__('Dashboard')}}</h1></a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{__('Policy Duration')}}</a>
        </li>
    </ul>
@endsection
@section('card-action-btn')
    @if(Gate::check('create policy duration'))
        <a class="btn btn-primary btn-sm ml-20 customModal" href="#" data-size="md"
           data-url="{{ route('policy-duration.create') }}"
           data-title="{{__('Create Policy Duration')}}"> <i class="ti-plus mr-5"></i>{{__('Create Policy Duration')}}</a>
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
                            <th>{{__('Duration Terms')}}</th>
                            <th>{{__('Duration')}}</th>
                            @if(Gate::check('edit policy duration') || Gate::check('delete policy duration'))
                                <th>{{__('Action')}}</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($policyDurations as $policyDuration)
                            <tr>
                                <td>{{ $policyDuration->duration_terms }} </td>
                                <td>{{ $policyDuration->duration_month }} {{__('Months')}}</td>
                                @if(Gate::check('edit policy duration') || Gate::check('delete policy duration'))
                                    <td>
                                        <div class="cart-action">
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['policy-duration.destroy', $policyDuration->id]]) !!}
                                            @if(Gate::check('edit policy duration'))
                                                <a class="text-success customModal" data-bs-toggle="tooltip"
                                                   data-bs-original-title="{{__('Edit')}}" href="#"
                                                   data-url="{{ route('policy-duration.edit',$policyDuration->id) }}"
                                                   data-title="{{__('Edit Policy Duration')}}"> <i data-feather="edit"></i></a>
                                            @endcan
                                            @if(Gate::check('delete policy duration'))
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

