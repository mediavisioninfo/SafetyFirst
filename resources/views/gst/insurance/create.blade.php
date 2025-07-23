@extends('layouts.app')
@section('page-title')
    {{__('Insurance Create')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{route('dashboard')}}"><h1>{{__('Dashboard')}}</h1></a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{route('insurance.index')}}">{{__('Insurance')}}</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{__('Create')}}</a>
        </li>
    </ul>
@endsection
@push('script-page')

    <script>
        var priceSymbole= "{{getSettingsValByName('CURRENCY_SYMBOL')}}";
        $('#customer').on('change', function () {
            "use strict";
            var customer = $(this).val();
            var url = '{{ route("insurance.user") }}';
            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    user: customer,
                },
                type: 'POST',
                success: function (response) {
                    var customerDetail='<div class="detail-group col-md-6 col-lg-6"><h6>{{__('Email')}}</h6><p class="mb-20">'+response.email+'<p/></div><div class="detail-group col-md-6 col-lg-6"><h6>{{__('Phone Number')}}</h6><p class="mb-20">'+response.phone_number+'<p/></div><div class="detail-group col-md-12 col-lg-12"><h6>{{__('Address')}}</h6><p class="mb-20">'+response.customer.address+','+response.customer.city+','+response.customer.state+','+response.customer.country+','+response.customer.zip_code+'<p/></div>';
                    $('#customerDetail').html(customerDetail)
                },
            });
        });
    </script>
    <script>
        $('#policy').on('change', function () {
            "use strict";
            var policy = $(this).val();
            var url = '{{ route("insurance.policy") }}';
            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    policy: policy,
                },
                type: 'POST',
                success: function (response) {
                    var pricingList=response.pricing;
                    var pricing='';
                    pricingList.forEach(function(item) {
                        pricing += '<div class="form-check custom-chek form-check-inline"><input class="form-check-input" type="radio" value="' + item.duration_terms + '-' +item.duration_month+'-'+item.price + '" id="' + item.duration_terms + '" name="policy_terms"><label class="form-check-label" for="' + item.duration_terms + '">' + item.duration_terms + ' - ' + priceSymbole+item.price + '</label></div>';
                    });
                    var policyDetail='<div class="detail-group col-md-4 col-lg-4"><h6>{{__('Policy Type')}}</h6><p class="mb-20">'+response.policy_type+'<p/></div><div class="detail-group col-md-4 col-lg-4"><h6>{{__('Policy Sub Type')}}</h6><p class="mb-20">'+response.policy_subtype+'<p/></div><div class="detail-group col-md-4 col-lg-4"><h6>{{__('Sum Assured')}}</h6><p class="mb-20">'+priceSymbole+response.sum_assured+'<p/></div><div class="detail-group col-md-4 col-lg-4"><h6>{{__('Liability Risk')}}</h6><p class="mb-20">'+response.liability_risk+'<p/></div><div class="detail-group col-md-4 col-lg-4"><h6>{{__('Coverage Type')}}</h6><p class="mb-20">'+response.coverage_type+'<p/></div><div class="detail-group col-md-4 col-lg-4"><h6>{{__('Total Insured Person')}}</h6><p class="mb-20">'+response.total_insured_person+'<p/></div><div class="detail-group col-md-12 col-lg-12"><h6>{{__('Policy Terms')}}:</h6><p class="mb-20">'+pricing+'</p></div>';

                    $('#policyDetail').html(policyDetail)

                },
            });
        });
    </script>
@endpush
@section('content')
    {{ Form::open(array('url' => 'insurance', 'method' => 'post')) }}
    <div class="row">
        <div class="col-xl-6 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>{{insurancePrefix().$insuranceNumber}}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group">
                            {{ Form::label('customer', __('Customer'), array('class' => 'form-label')) }}
                            {!! Form::select('customer', $customer, null, array('class' => 'form-control  basic-select', 'required' => 'required')) !!}
                        </div>
                    </div>
                    <div class="row" id="customerDetail"> </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>{{__('Policy Detail')}}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group">
                            {{ Form::label('policy', __('Policy'), array('class' => 'form-label')) }}
                            {!! Form::select('policy', $policy, null, array('class' => 'form-control  basic-select', 'required' => 'required')) !!}
                        </div>
                    </div>
                    <div class="row" id="policyDetail"> </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>{{__('Agent Detail')}}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-6 col-lg-6">
                            {{ Form::label('agent', __('Agent'), array('class' => 'form-label')) }}
                            {!! Form::select('agent', $agent, old('policy_type'), array('class' => 'form-control  basic-select')) !!}
                        </div>
                        <div class="form-group col-md-6 col-lg-6">
                            {{ Form::label('agent_commission', __('Agent Commission'), array('class' => 'form-label')) }}
                            {{ Form::number('agent_commission', 0, array('class' => 'form-control','placeholder'=>__('Enter agent commission'))) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-6 col-lg-6">
                            {{ Form::label('start_date', __('Start Date'), array('class' => 'form-label')) }}
                            {{ Form::date('start_date', date('Y-m-d'), array('class' => 'form-control')) }}
                        </div>
                        <div class="form-group col-md-6 col-lg-6">
                            {{ Form::label('status', __('Status'), array('class' => 'form-label')) }}
                            {!! Form::select('status', $status, old('status'), array('class' => 'form-control  basic-select hidesearch')) !!}
                        </div>
                        <div class="form-group  col-md-12">
                            {{Form::label('notes',__('Note'),array('class'=>'form-label'))}}
                            {{Form::textarea('notes',null,array('class'=>'form-control','rows'=>1))}}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row ">
        <div class="form-group text-end">
            {{ Form::submit(__('Create'), array('class' => 'btn btn-primary ml-10')) }}
        </div>
    </div>
    {{ Form::close() }}

@endsection

