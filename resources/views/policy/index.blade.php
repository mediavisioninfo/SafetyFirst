@extends('layouts.app')
@section('page-title')
    {{__('Policy')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{route('dashboard')}}"><h1>{{__('Dashboard')}}</h1></a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{__('Policy')}}</a>
        </li>
    </ul>
@endsection
@section('card-action-btn')
    @if(Gate::check('create policy'))
        <a class="btn btn-primary btn-sm ml-20" href="{{ route('policy.create') }}" > <i class="ti-plus mr-5"></i>{{__('Create Policy')}}</a>
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
                            <th>{{__('Policy Type')}}</th>
                            <th>{{__('Policy Sub Type')}}</th>
                            <th>{{__('Coverage Type')}}</th>
                            <th>{{__('Liability Risk')}}</th>
                            <th>{{__('Policy For')}}</th>
                            <th>{{__('Sum Assured')}}</th>
                            @if(Gate::check('edit policy') || Gate::check('delete policy') || Gate::check('show policy'))
                                <th>{{__('Action')}}</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($policies as $policy)

                            <tr>
                                <td>{{ $policy->title }} </td>
                                <td>{{ !empty($policy->types)?$policy->types->title:'-' }} </td>
                                <td>{{ !empty($policy->subtypes)?$policy->subtypes->title:'-' }} </td>
                                <td>{{ \App\Models\Policy::$coverageType[$policy->coverage_type] }} </td>
                                <td>{{ \App\Models\Policy::$liabilityRisk[$policy->liability_risk] }} </td>
                                <td>{{ !empty($policy->policyFor)?$policy->policyFor->buying_for:'-' }} </td>
                                <td>{{ priceFormat($policy->sum_assured) }} </td>
                                @if(Gate::check('edit policy') || Gate::check('delete policy') || Gate::check('show policy'))
                                    <td>
                                        <div class="cart-action">
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['policy.destroy', $policy->id]]) !!}
                                            @if(Gate::check('show policy'))
                                                <a class="text-warning" data-bs-toggle="tooltip"
                                                   data-bs-original-title="{{__('Details')}}" href="{{ route('policy.show',\Illuminate\Support\Facades\Crypt::encrypt($policy->id)) }}"> <i data-feather="eye"></i></a>
                                            @endcan
                                            @if(Gate::check('edit policy'))
                                                <a class="text-success" data-bs-toggle="tooltip"
                                                   data-bs-original-title="{{__('Edit')}}" href="{{ route('policy.edit',\Illuminate\Support\Facades\Crypt::encrypt($policy->id)) }}"> <i data-feather="edit"></i></a>
                                            @endcan
                                            @if(Gate::check('delete policy'))
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

