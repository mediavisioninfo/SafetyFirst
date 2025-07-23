@extends('layouts.app')
@section('page-title')
    {{$policy->title}} {{__('Detail')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{route('dashboard')}}"><h1>{{__('Dashboard')}}</h1></a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{route('policy.index')}}">{{__('Policy')}}</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{$policy->title}}</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4> {{$policy->title}}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-lg-4">
                            <div class="detail-group">
                                <h6>{{__('Policy Type')}}</h6>
                                <p class="mb-20">{{ !empty($policy->types)?$policy->types->title:'-' }} </p>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div class="detail-group">
                                <h6>{{__('Policy Sub Type')}}</h6>
                                <p class="mb-20">{{ !empty($policy->subtypes)?$policy->subtypes->title:'-' }} </p>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div class="detail-group">
                                <h6>{{__('Policy For')}}</h6>
                                <p class="mb-20">{{ !empty($policy->policyFor)?$policy->policyFor->buying_for:'-' }} </p>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div class="detail-group">
                                <h6>{{__('Coverage Type')}}</h6>
                                <p class="mb-20">{{ \App\Models\Policy::$coverageType[$policy->coverage_type] }} </p>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div class="detail-group">
                                <h6>{{__('Total Insured Person')}}</h6>
                                <p class="mb-20">
                                    <span> {{$policy->total_insured_person}}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div class="detail-group">
                                <h6>{{__('Liability Risk')}}</h6>
                                <p class="mb-20">
                                    <span> {{ \App\Models\Policy::$liabilityRisk[$policy->liability_risk] }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div class="detail-group">
                                <h6>{{__('Sum Assured')}}</h6>
                                <p class="mb-20">
                                    <span> {{ priceFormat($policy->sum_assured) }} </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div class="detail-group">
                                <h6>{{__('Policy Required Document')}}</h6>
                                <p class="mb-20">
                                    @foreach($policy->documentTypes($policy->policy_required_document) as $doc)
                                        <span> {{$doc->title}}</span> <br>
                                    @endforeach
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div class="detail-group">
                                <h6>{{__('Claim Required Document')}}</h6>
                                <p class="mb-20">
                                    @foreach($policy->documentTypes($policy->claim_required_document) as $doc)
                                        <span> {{$doc->title}} </span> <br>
                                    @endforeach
                                </p>
                            </div>
                        </div>
                        @if(!empty($policy->tax))
                            <div class="col-md-4 col-lg-4">
                                <div class="detail-group">
                                    <h6>{{__('Tax Detail')}}</h6>
                                    <p class="mb-20">
                                        @foreach(getTax($policy->tax) as $tax)
                                            <span> {{$tax->tax}} ({{$tax->rate}}%)</span> <br>
                                        @endforeach
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4> {{__('Policy Pricing')}}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xxl-12 cdx-xxl-100 ">
                            <div class="table-responsive">
                                <table class="display dataTable cell-border ">
                                    <thead>
                                    <tr>
                                        <th>{{__('Terms Duration')}}</th>
                                        <th>{{__('Price')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($policy->pricing as $duration)
                                        <tr>
                                            <td>{{$duration->duration_terms}}</td>
                                            <td>{{priceFormat($duration->price)}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4> {{__('Policy Description')}}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xxl-12 cdx-xxl-100 mt-10">
                            {!! $policy->description !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4> {{__('Policy Terms & Condition')}}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xxl-12 cdx-xxl-100 mt-10">
                            {!! $policy->terms_conditions !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

