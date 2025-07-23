@extends('layouts.app')
@section('page-title')
    {{__('Tax')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{route('dashboard')}}"><h1>{{__('Dashboard')}}</h1></a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">
                {{__('Tax')}}
            </a>
        </li>
    </ul>
@endsection
@section('card-action-btn')
    @if(Gate::check('manage tax'))
        <a class="btn btn-primary btn-sm ml-20 customModal" href="#" data-size="md"
           data-url="{{ route('tax.create') }}"
           data-title="{{__('Create Tax')}}"> <i
                class="ti-plus mr-5"></i>
            {{__('Create Tax')}}
        </a>
    @endif
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="display dataTable cell-border datatbl-advance productlist-tbl">
                        <thead>
                        <tr>
                            <th>{{__('Tax')}}</th>
                            <th>{{__('Rate')}}</th>
                            <th>{{__('Action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($taxs as $tax)
                            <tr>
                                <td>{{ $tax->tax }} </td>
                                <td>{{ $tax->rate }} %</td>
                                <td>
                                    <div class="cart-action">
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['tax.destroy', $tax->id]]) !!}
                                        @can('edit tax')
                                            <a class="text-success customModal" data-bs-toggle="tooltip" data-size="md"
                                               data-bs-original-title="{{__('Edit')}}" href="#"
                                               data-url="{{ route('tax.edit',$tax->id) }}"
                                               data-title="{{__('Edit Tax')}}"> <i data-feather="edit"></i></a>
                                        @endcan
                                        @can('delete tax')
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
