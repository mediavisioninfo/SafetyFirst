@extends('layouts.app')
@section('page-title')
    {{__('Insurance')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{route('dashboard')}}"><h1>{{__('Dashboard')}}</h1></a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{__('Insurance')}}</a>
        </li>
    </ul>
@endsection
@section('card-action-btn')
    @if(Gate::check('create insurance'))
        <a class="btn btn-primary btn-sm ml-20" href="{{ route('insurance.create') }}" > <i class="ti-plus mr-5"></i>{{__('Create Insurance')}}</a>
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
                            <th>{{__('ID')}}</th>
                            <th>{{__('Policy')}}</th>
                            <th>{{__('Policy Type')}}</th>
                            <th>{{__('Policy Sub Type')}}</th>
                            <th>{{__('Policy Holder')}}</th>
                            <th>{{__('Start Date')}}</th>
                            <th>{{__('Expiry Date')}}</th>
                            <th>{{__('Status')}}</th>
                            @if(Gate::check('edit insurance') || Gate::check('delete insurance') || Gate::check('show insurance'))
                                <th>{{__('Action')}}</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($insurances as $insurance)
                            <tr>
                                <td>{{insurancePrefix().$insurance->insurance_id }} </td>
                                <td>{{!empty($insurance->policies)?$insurance->policies->title:'-'}}</td>
                                <td>{{!empty($insurance->policies)?$insurance->policies->types->title:'-'}}</td>
                                <td>{{!empty($insurance->policies)?$insurance->policies->subtypes->title:'-'}}</td>
                                <td>{{!empty($insurance->customers)?$insurance->customers->name:'-'}}</td>
                                <td>{{dateFormat($insurance->start_date)}}</td>
                                <td>{{dateFormat($insurance->due_date)}}</td>
                                <td>
                                    @if($insurance->status=='new')
                                        <span class="badge badge-info"> {{\App\Models\Insurance::$status[$insurance->status]}}</span>
                                    @elseif($insurance->status=='to_review')
                                        <span class="badge badge-warning"> {{\App\Models\Insurance::$status[$insurance->status]}}</span>
                                    @elseif($insurance->status=='confirm')
                                        <span class="badge badge-primary"> {{\App\Models\Insurance::$status[$insurance->status]}}</span>
                                    @elseif($insurance->status=='running')
                                        <span class="badge badge-success"> {{\App\Models\Insurance::$status[$insurance->status]}}</span>
                                    @else
                                        <span class="badge badge-danger"> {{\App\Models\Insurance::$status[$insurance->status]}}</span>
                                    @endif
                                </td>

                                @if(Gate::check('edit insurance') || Gate::check('delete insurance'))
                                    <td>
                                        <div class="cart-action">
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['insurance.destroy', $insurance->id]]) !!}
                                            @if(Gate::check('show insurance'))
                                                <a class="text-warning" data-bs-toggle="tooltip"
                                                   data-bs-original-title="{{__('Details')}}" href="{{ route('insurance.show',\Illuminate\Support\Facades\Crypt::encrypt($insurance->id)) }}"> <i data-feather="eye"></i></a>
                                            @endcan
                                            @if(Gate::check('edit insurance'))
                                                <a class="text-success" data-bs-toggle="tooltip"
                                                   data-bs-original-title="{{__('Edit')}}" href="{{ route('insurance.edit',\Illuminate\Support\Facades\Crypt::encrypt($insurance->id)) }}"> <i data-feather="edit"></i></a>
                                            @endcan
                                            @if(Gate::check('delete insurance'))
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

