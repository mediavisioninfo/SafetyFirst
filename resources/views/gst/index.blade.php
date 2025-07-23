@extends('layouts.app')

@section('page-title')
    {{__('GST Management')}}
@endsection

@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}"><h1>{{__('Dashboard')}}</h1></a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{__('GST Management')}}</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12 cdx-xxl-100 cdx-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="info-group">
                        {{ Form::model($gst, ['route' => ['gst.update'], 'method' => 'put', 'enctype' => 'multipart/form-data']) }}
                        <div class="row">
                            <!-- CGST Field -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('cgst', __('CGST'), ['class' => 'form-label']) }}
                                    {{ Form::number('cgst', null, ['class' => 'form-control', 'placeholder' => __('Enter CGST value'), 'step' => '0.01']) }}
                                </div>
                            </div>

                            <!-- SGST Field -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('sgst', __('SGST'), ['class' => 'form-label']) }}
                                    {{ Form::number('sgst', null, ['class' => 'form-control', 'placeholder' => __('Enter SGST value'), 'step' => '0.01']) }}
                                </div>
                            </div>

                            <!-- IGST Field -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('igst', __('IGST'), ['class' => 'form-label']) }}
                                    {{ Form::number('igst', null, ['class' => 'form-control', 'placeholder' => __('Enter IGST value'), 'step' => '0.01']) }}
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            {{ Form::submit(__('Save'), ['class' => 'btn btn-primary btn-rounded']) }}
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
