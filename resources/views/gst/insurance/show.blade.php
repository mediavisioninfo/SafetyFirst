@extends('layouts.app')
@section('page-title')
    {{insurancePrefix().$insurance->insurance_id}} {{__('Detail')}}
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
            <a href="#">  {{insurancePrefix().$insurance->insurance_id}} {{__('Detail')}}</a>
        </li>
    </ul>
@endsection
@push('script-page')
    <script>
        $(document).on('click', '.print', function () {
            $('.action').addClass('d-none');
            var printContents = document.getElementById('insurance-print').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            $('.action').removeClass('d-none');
        });
    </script>
@endpush
@section('card-action-btn')

@endsection
@section('content')
    <div class="row mb-10">
        <div class="right-breadcrumb">
            <ul>
                <a class="btn btn-warning float-end print" href="javascript:void(0);"> {{__('Print')}}</a>
                @can('create payment')
                    <a class="btn btn-success float-end me-2 customModal" href="#" data-size="md    "
                       data-url="{{ route('insurance.payment.create',$insurance->id) }}"
                       data-title="{{__('Add Payment')}}"> {{__('Add Payment')}}</a>
                @endcan
                @can('create document')
                    <a class="btn btn-danger float-end me-2 customModal" href="#" data-size="md"
                       data-url="{{ route('insurance.document.create',$insurance->id) }}"
                       data-title="{{__('Add Document')}}"> {{__('Add Document')}}</a>
                @endcan
                @can('create insured detail')
                    <a class="btn btn-info float-end me-2 customModal" href="#" data-size="lg"
                       data-url="{{ route('insurance.nominee.create',$insurance->id) }}"
                       data-title="{{__('Add Nominee')}}"> {{__('Add Nominee')}}</a>
                @endcan
                @can('create insured detail')
                    <a class="btn btn-primary float-end me-2 customModal" href="#" data-size="lg"
                       data-url="{{ route('insurance.insured.create',$insurance->id) }}"
                       data-title="{{__('Add Insured')}}"> {{__('Add Insured')}}</a>
                @endcan
            </ul>
        </div>
    </div>
    <div id="insurance-print">
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="card">

                    <div class="card-header">
                        <h4> {{insurancePrefix().$insurance->insurance_id }} </h4>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4 col-md-3 col-lg-3">
                                <div class="detail-group">
                                    <h6>{{__('Customer ID')}}</h6>
                                    <p class="mb-20">{{!empty($insurance->customers)?customerPrefix().$insurance->customers->customer->customer_id:'-'}}</p>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3 col-lg-3">
                                <div class="detail-group">
                                    <h6>{{__('Name')}}</h6>
                                    <p class="mb-20">{{!empty($insurance->customers)?$insurance->customers->name:'-'}}</p>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3 col-lg-3">
                                <div class="detail-group">
                                    <h6>{{__('Email')}}</h6>
                                    <p class="mb-20">{{!empty($insurance->customers)?$insurance->customers->email:'-'}}</p>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3 col-lg-3">
                                <div class="detail-group">
                                    <h6>{{__('Phone Number')}}</h6>
                                    <p class="mb-20">{{!empty($insurance->customers)?$insurance->customers->phone_number:'-'}}</p>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3 col-lg-3">
                                <div class="detail-group">
                                    <h6>{{__('Company')}}</h6>
                                    <p class="mb-20">{{!empty($insurance->customers)?$insurance->customers->customer->company:'-'}}</p>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3 col-lg-3">
                                <div class="detail-group">
                                    <h6>{{__('Date of Birth')}}</h6>
                                    <p class="mb-20">{{!empty($insurance->customers)?$insurance->customers->customer->dob:'-'}}</p>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3 col-lg-3">
                                <div class="detail-group">
                                    <h6>{{__('Age')}}</h6>
                                    <p class="mb-20">{{!empty($insurance->customers)?$insurance->customers->customer->age:'-'}}</p>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3 col-lg-3">
                                <div class="detail-group">
                                    <h6>{{__('Gender')}}</h6>
                                    <p class="mb-20">{{!empty($insurance->customers)?$insurance->customers->customer->gender:'-'}}</p>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3 col-lg-3">
                                <div class="detail-group">
                                    <h6>{{__('Marital Status')}}</h6>
                                    <p class="mb-20">{{!empty($insurance->customers)?$insurance->customers->customer->marital_status:'-'}}</p>
                                </div>
                            </div>

                            <div class="col-sm-4 col-md-3 col-lg-3">
                                <div class="detail-group">
                                    <h6>{{__('Blood Group')}}</h6>
                                    <p class="mb-20">{{!empty($insurance->customers)?$insurance->customers->customer->blood_group:'-'}}</p>
                                </div>
                            </div>

                            <div class="col-sm-4 col-md-3 col-lg-3">
                                <div class="detail-group">
                                    <h6>{{__('Height')}}</h6>
                                    <p class="mb-20">{{!empty($insurance->customers)?$insurance->customers->customer->height:'-'}}</p>
                                </div>
                            </div>

                            <div class="col-sm-4 col-md-3 col-lg-3">
                                <div class="detail-group">
                                    <h6>{{__('Weight')}}</h6>
                                    <p class="mb-20">{{!empty($insurance->customers)?$insurance->customers->customer->weight:'-'}}</p>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3 col-lg-3">
                                <div class="detail-group">
                                    <h6>{{__('Tax Number')}}</h6>
                                    <p class="mb-20">{{!empty($insurance->customers)?$insurance->customers->customer->tax_number:'-'}}</p>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3 col-lg-3">
                                <div class="detail-group">
                                    <h6>{{__('Address')}}</h6>
                                    <p class="mb-20">
                                        {{!empty($insurance->customers)?$insurance->customers->customer->address:'-'}}
                                        <br>
                                        {{!empty($insurance->customers)?$insurance->customers->customer->city:'-'}}
                                        {{!empty($insurance->customers)?$insurance->customers->customer->zip_code:'-'}}
                                        <br>
                                        {{!empty($insurance->customers)?$insurance->customers->customer->state:'-'}}
                                        {{!empty($insurance->customers)?$insurance->customers->customer->country:'-'}}
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4> {{__('Policy Detail') }} </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4 col-md-3 col-lg-3">
                                <div class="detail-group">
                                    <h6>{{__('Policy')}}</h6>
                                    <p class="mb-20">{{!empty($insurance->policies)?$insurance->policies->title:'-'}} </p>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3 col-lg-3">
                                <div class="detail-group">
                                    <h6>{{__('Policy Type')}}</h6>
                                    <p class="mb-20">{{!empty($insurance->policies)?$insurance->policies->types->title:'-'}} </p>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3 col-lg-3">
                                <div class="detail-group">
                                    <h6>{{__('Policy Sub Type')}}</h6>
                                    <p class="mb-20">{{!empty($insurance->policies)?$insurance->policies->subtypes->title:'-'}}</p>
                                </div>
                            </div>

                            <div class="col-sm-4 col-md-3 col-lg-3">
                                <div class="detail-group">
                                    <h6>{{__('Policy For')}}</h6>
                                    <p class="mb-20">{{ !empty($insurance->policies)?$insurance->policies->policyFor->buying_for:'-' }} </p>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3 col-lg-3">
                                <div class="detail-group">
                                    <h6>{{__('Coverage Type')}}</h6>
                                    <p class="mb-20">{{!empty($insurance->policies)? \App\Models\Policy::$coverageType[$insurance->policies->coverage_type] :'-'}}  </p>
                                </div>
                            </div>


                            <div class="col-sm-4 col-md-3 col-lg-3">
                                <div class="detail-group">
                                    <h6>{{__('Liability Risk')}}</h6>
                                    <p class="mb-20">
                                        <span>{{!empty($insurance->policies)? \App\Models\Policy::$liabilityRisk[$insurance->policies->liability_risk] :'-'}}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3 col-lg-3">
                                <div class="detail-group">
                                    <h6>{{__('Sum Assured')}}</h6>
                                    <p class="mb-20">
                                        <span>  {{!empty($insurance->policies)? priceFormat($insurance->policies->sum_assured) :'-'}} </span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3 col-lg-3">
                                <div class="detail-group">
                                    <h6>{{__('Start Date')}}</h6>
                                    <p class="mb-20">
                                        <span> {{dateFormat($insurance->start_date)}} </span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3 col-lg-3">
                                <div class="detail-group">
                                    <h6>{{__('Expiry Date')}}</h6>
                                    <p class="mb-20">
                                        <span> {{dateFormat($insurance->due_date)}} </span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3 col-lg-3">
                                <div class="detail-group">
                                    <h6>{{__('Policy Term')}}</h6>
                                    <p class="mb-20">
                                        <span> {{$insurance->policy_term}} {{__('Months')}}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3 col-lg-3">
                                <div class="detail-group">
                                    <h6>{{__('Premium')}}</h6>
                                    <p class="mb-20">
                                        <span> {{priceFormat($insurance->premium)}} </span>
                                    </p>
                                </div>
                            </div>

                            @if(!empty($insurance->policies->tax))
                                <div class="col-sm-4 col-md-3 col-lg-3">
                                    <div class="detail-group">
                                        <h6>{{__('Tax Detail')}}</h6>
                                        <p class="mb-20">
                                            @foreach(getTax($insurance->policies->tax) as $tax)
                                                <span> {{$tax->tax}} ({{$tax->rate}}%) ({{priceFormat(taxRate($tax->rate,$insurance->premium))}})</span> <br>
                                            @endforeach
                                        </p>
                                    </div>
                                </div>
                            @endif
                            @if(!empty($insurance->notes))
                                <div class="col-sm-4 col-md-3 col-lg-3">
                                    <div class="detail-group">
                                        <h6>{{__('Notes')}}</h6>
                                        <p class="mb-20">
                                            <span> {{!empty($insurance->notes)?$insurance->notes:'-'}} </span>
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @if(count($insurance->insureds)>0)
                <div class="col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4> {{__('Insured Detail') }} </h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>{{__('Name')}}</th>
                                        <th>{{__('DOB')}}</th>
                                        <th>{{__('Age')}}</th>
                                        <th>{{__('Gender')}}</th>
                                        <th>{{__('Blood Group')}}</th>
                                        <th>{{__('Height')}}</th>
                                        <th>{{__('Weight')}}</th>
                                        <th>{{__('Relation')}}</th>
                                        @if(Gate::check('delete insured detail'))
                                            <th class="action">{{__('Action')}}</th>
                                        @endcan
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($insurance->insureds as $insureds)

                                        <tr>
                                            <td>{{$insureds->name}}</td>
                                            <td>{{dateFormat($insureds->dob)}}</td>
                                            <td>{{$insureds->age}}</td>
                                            <td>{{$insureds->gender}}</td>
                                            <td>{{$insureds->blood_group}}</td>
                                            <td>{{$insureds->height}}</td>
                                            <td>{{$insureds->weight}}</td>
                                            <td>{{$insureds->relation}}</td>
                                            @if(Gate::check('delete insured detail'))
                                                <td class="action">
                                                    <div class="cart-action">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['insurance.insured.destroy', [$insurance->id,$insureds->id]]]) !!}
                                                        <a class=" text-danger confirm_dialog" data-bs-toggle="tooltip"
                                                           data-bs-original-title="{{__('Detete')}}" href="#"> <i
                                                                data-feather="trash-2"></i></a>

                                                        {!! Form::close() !!}
                                                    </div>
                                                </td>
                                            @endcan
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if(count($insurance->nominees)>0)
                <div class="col-md-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4> {{__('Nominee Detail') }} </h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>{{__('Name')}}</th>
                                        <th>{{__('DOB')}}</th>
                                        <th>{{__('Relation')}}</th>
                                        <th class="action">{{__('Percentage')}}</th>
                                        @if(Gate::check('delete nominee'))
                                            <th>{{__('Action')}}</th>
                                        @endcan
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($insurance->nominees as $nominees)

                                        <tr>
                                            <td>{{$nominees->name}}</td>
                                            <td>{{dateFormat($nominees->dob)}}</td>
                                            <td>{{$nominees->relation}}</td>
                                            <td>{{$nominees->percentage}}%</td>
                                            @if(Gate::check('delete nominee'))
                                                <td class="action">
                                                    <div class="cart-action">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['insurance.nominee.destroy', [$insurance->id,$nominees->id]]]) !!}
                                                        <a class=" text-danger confirm_dialog" data-bs-toggle="tooltip"
                                                           data-bs-original-title="{{__('Detete')}}" href="#"> <i
                                                                data-feather="trash-2"></i></a>

                                                        {!! Form::close() !!}
                                                    </div>
                                                </td>
                                            @endcan
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if(count($insurance->documents)>0)
                <div class="col-md-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4> {{__('Document Detail') }} </h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>{{__('Type')}}</th>
                                        <th>{{__('Document')}}</th>
                                        <th>{{__('Status')}}</th>
                                        @if(Gate::check('delete document'))
                                            <th class="action">{{__('Action')}}</th>
                                        @endcan
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($insurance->documents as $document)
                                        <tr>
                                            <td>{{!empty($document->types)?$document->types->title:'-'}}</td>
                                            <td><a href="{{asset('/storage/upload/document/'.$document->document)}}"
                                                   target="_blank">{{!empty($document->types)?$document->types->title:'-'}}</a>
                                            </td>
                                            <td>
                                                {{\App\Models\Insurance::$docStatus[$document->status]}}
                                            </td>
                                            @if(Gate::check('delete document'))
                                                <td class="action">
                                                    <div class="cart-action">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['insurance.document.destroy', [$insurance->id,$document->id]]]) !!}
                                                        <a class=" text-danger confirm_dialog" data-bs-toggle="tooltip"
                                                           data-bs-original-title="{{__('Detete')}}" href="#"> <i
                                                                data-feather="trash-2"></i></a>

                                                        {!! Form::close() !!}
                                                    </div>
                                                </td>
                                            @endcan
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if(!empty($insurance->agents)>0)
                <div class="col-md-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4> {{__('Agent Detail') }} </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-4 col-md-4 col-lg-4">
                                    <div class="detail-group">
                                        <h6>{{__('Agent ID')}}</h6>
                                        <p class="mb-20">{{!empty($insurance->agents)?agentPrefix().$insurance->agents->agent->agent_id:'-'}}</p>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4 col-lg-4">
                                    <div class="detail-group">
                                        <h6>{{__('Name')}}</h6>
                                        <p class="mb-20">{{!empty($insurance->agents)?$insurance->agents->name:'-'}}</p>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4 col-lg-4">
                                    <div class="detail-group">
                                        <h6>{{__('Email')}}</h6>
                                        <p class="mb-20">{{!empty($insurance->agents)?$insurance->agents->email:'-'}}</p>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4 col-lg-4">
                                    <div class="detail-group">
                                        <h6>{{__('Phone Number')}}</h6>
                                        <p class="mb-20">{{!empty($insurance->agents)?$insurance->agents->phone_number:'-'}}</p>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4 col-lg-4">
                                    <div class="detail-group">
                                        <h6>{{__('Company')}}</h6>
                                        <p class="mb-20">{{!empty($insurance->agents)?$insurance->agents->agent->company:'-'}}</p>
                                    </div>
                                </div>

                                <div class="col-sm-4 col-md-4 col-lg-4">
                                    <div class="detail-group">
                                        <h6>{{__('Address')}}</h6>
                                        <p class="mb-20">
                                            {{!empty($insurance->agents)?$insurance->agents->agent->address:'-'}}
                                            <br>
                                            {{!empty($insurance->agents)?$insurance->agents->agent->city:'-'}}
                                            {{!empty($insurance->agents)?$insurance->agents->agent->zip_code:'-'}}
                                            <br>
                                            {{!empty($insurance->agents)?$insurance->agents->agent->state:'-'}}
                                            {{!empty($insurance->agents)?$insurance->agents->agent->country:'-'}}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if(count($insurance->payments)>0)
                <div class="col-md-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4> {{__('Payment Detail') }} </h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>{{__('Payment Date')}}</th>
                                        <th>{{__('Premium')}}</th>
                                        @if(!empty($insurance->policies->tax))
                                            <th>{{__('Tax')}}</th>
                                        @endif
                                        <th>{{__('Total')}}</th>
                                        @if(Gate::check('delete payment'))
                                            <th class="action">{{__('Action')}}</th>
                                        @endcan
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($insurance->payments as $payment)
                                        <tr>
                                            <td>{{dateFormat($payment->payment_date)}}</td>
                                            <td>
                                                {{priceFormat($insurance->premium)}}
                                            </td>
                                            @if(!empty($insurance->policies->tax))
                                                <td>
                                                    @foreach(getTax($insurance->policies->tax) as $tax)
                                                        <span> {{$tax->tax}} ({{$tax->rate}}%) ({{priceFormat(taxRate($tax->rate,$insurance->premium))}})</span>
                                                    @endforeach
                                                </td>
                                            @endif
                                            <td>{{priceFormat($payment->amount)}} </td>
                                            @if(Gate::check('delete payment'))
                                                <td class="action">
                                                    <div class="cart-action">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['insurance.payment.destroy', [$insurance->id,$payment->id]]]) !!}
                                                        <a class=" text-danger confirm_dialog" data-bs-toggle="tooltip"
                                                           data-bs-original-title="{{__('Detete')}}" href="#"> <i
                                                                data-feather="trash-2"></i></a>

                                                        {!! Form::close() !!}
                                                    </div>
                                                </td>
                                            @endcan
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
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
                                {!! $insurance->policies->description !!}
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
                                {!! $insurance->policies->terms_conditions !!}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

