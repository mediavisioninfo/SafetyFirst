@extends('layouts.app')
@section('page-title')
    {{__('Agent Edit')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{route('dashboard')}}"><h1>{{__('Dashboard')}}</h1></a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{route('agent.index')}}">{{__('Agent')}}</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{__('Edit')}}</a>
        </li>
    </ul>
@endsection
@section('content')
    {{Form::model($agent, array('route' => array('agent.update', $agent->id), 'method' => 'PUT')) }}
    <div class="row">
        <div class="col-xl-6 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>{{agentPrefix().$agent->agent->agent_id}}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-6 col-lg-6">
                            {{Form::label('name',__('Name'),array('class'=>'form-label')) }}
                            {{Form::text('name', null,array('class'=>'form-control','placeholder'=>__('Enter Name'),'required'=>'required'))}}
                        </div>
                        <div class="form-group col-md-6 col-lg-6">
                            {{Form::label('email',__('Email'),array('class'=>'form-label'))}}
                            {{Form::text('email', null,array('class'=>'form-control','placeholder'=>__('Enter email'),'required'=>'required'))}}
                        </div>
                        <div class="form-group col-md-6 col-lg-6">
                            {{Form::label('phone_number',__('Phone Number'),array('class'=>'form-label')) }}
                            {{Form::text('phone_number', null,array('class'=>'form-control','placeholder'=>__('Enter phone number'),'required'=>'required'))}}
                        </div>
                        <div class="form-group col-md-6 col-lg-6">
                            {{Form::label('city',__('City'),array('class'=>'form-label')) }}
                            {{Form::text('city', $agent->agent->city,array('class'=>'form-control','placeholder'=>__('Enter city'),'required'=>'required'))}}
                        </div>
                        <div class="form-group col-md-6 col-lg-6">
                            {{Form::label('state',__('State'),array('class'=>'form-label')) }}
                            {{Form::text('state', $agent->agent->state,array('class'=>'form-control','placeholder'=>__('Enter state'),'required'=>'required'))}}
                        </div>
                        <div class="form-group col-md-6 col-lg-6">
                            {{Form::label('country',__('Country'),array('class'=>'form-label')) }}
                            {{Form::text('country', $agent->agent->country,array('class'=>'form-control','placeholder'=>__('Enter country'),'required'=>'required'))}}
                        </div>
                        <div class="form-group col-md-6 col-lg-6">
                            {{Form::label('zip_code',__('Zip Code'),array('class'=>'form-label')) }}
                            {{Form::text('zip_code', $agent->agent->zip_code,array('class'=>'form-control','placeholder'=>__('Enter zip code'),'required'=>'required'))}}
                        </div>
                        <div class="form-group col-md-6 col-lg-6">
                            {{Form::label('address',__('Address'),array('class'=>'form-label')) }}
                            {{Form::textarea('address', $agent->agent->address,array('class'=>'form-control','placeholder'=>__('Enter address'),'rows'=>2,'required'=>'required'))}}
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>{{__('Additional Detail')}}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-6 col-lg-6">
                            {{Form::label('company',__('Company'),array('class'=>'form-label')) }}
                            {{Form::text('company',$agent->agent->company,array('class'=>'form-control','placeholder'=>__('Enter company')))}}
                        </div>
                        <div class="form-group col-md-6 col-lg-6">
                            {{Form::label('tax_number',__('Tax number'),array('class'=>'form-label')) }}
                            {{Form::text('tax_number',$agent->agent->tax_number,array('class'=>'form-control','placeholder'=>__('Enter tax/vat number')))}}
                        </div>

                        <div class="form-group col-md-12 col-lg-12">
                            {{Form::label('notes',__('Note'),array('class'=>'form-label')) }}
                            {{Form::textarea('notes', $agent->agent->notes,array('class'=>'form-control','placeholder'=>__('Enter notes'),'rows'=>2))}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row ">
        <div class="form-group text-end">
            {{ Form::submit(__('Update'), array('class' => 'btn btn-primary ml-10')) }}
        </div>
    </div>
    {{ Form::close() }}

@endsection

