@extends('layouts.app')
@section('page-title')
    {{ $claim->claim_id }} {{ __('Detail') }}
@endsection
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}">
                <h1>{{ __('Dashboard') }}</h1>
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('claim.index') }}">{{ __('Claim') }}</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#"> {{ $claim->claim_id }} {{ __('Detail') }}</a>
        </li>
    </ul>
@endsection
@push('script-page')
    <script>
        $(document).on('click', '.print', function() {
            var printContents = document.getElementById('insurance-print').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        });
    </script>
@endpush
@section('card-action-btn')
    <div class="right-breadcrumb">
        <ul>
            <a class="btn btn-warning float-end print me-2" href="javascript:void(0);"> {{ __('Print') }}</a>
            @if ($claim->status === 'approved' || $claim->status === 'rejected')
                <a class="btn btn-success float-end me-2" href="{{ route('claim.report', $claim->id) }}">
                    {{ __('Download Report') }}
                </a>
            @endif
            <a class="btn btn-primary float-end me-2" href="{{ route('claim.excel', $claim->id) }}">
                {{ __('Download Excel') }}
            </a>
            <a class="btn btn-danger float-end me-2" href="{{ route('claim.exceltopdf', $claim->id) }}">
                {{ __('Convert Excel to PDF') }}
            </a>
            <a class="btn btn-info float-end me-2" href="{{ route('claim.recheck', $claim->id) }}">
                {{ __('Recheck') }}
            </a>
        </ul>
    </div>
@endsection
<style>
    /* Base styles for tabs */
    .nav-tabs .nav-link {
        color: #555;
        background-color: #f8f9fa;
        border: 1px solid transparent;
        border-radius: 8px 8px 0 0;
        padding: 10px 20px;
        transition: background-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
    }

    /* Hover effect for inactive tabs */
    .nav-tabs .nav-link:hover {
        background-color: #0056b3;
        /* Slightly darker blue for hover */
        color: #fff;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        /* Subtle shadow on hover */
    }

    /* Active tab styling */
    .nav-tabs .nav-link.active {
        background-color: #007bff;
        /* Bright blue for active tab */
        color: #fff;
        border-color: #007bff #007bff #f8f9fa;
        font-weight: bold;
        box-shadow: 0px 4px 12px rgba(0, 123, 255, 0.5);
        /* Bolder shadow for active tab */
        position: relative;
        top: -1px;
        /* Slight lift effect for active tab */
    }

    /* Rounded effect for smoother look */
    .nav-tabs {
        border-bottom: 2px solid #007bff;
        margin-bottom: 15px;
    }

    /* Tab panel */
    .tab-content {
        padding: 15px;
        border: 1px solid #ddd;
        border-top: none;
        border-radius: 0 8px 8px 8px;
        background-color: #fff;
    }
</style>

@section('content')
    <div id="insurance-print">
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4>#{{ $claim->claim_id }} </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            {{-- <div class="col-sm-4 col-md-3 col-lg-3">
                                <div class="detail-group">
                                    <h6>{{__('Insurance')}}</h6>
                                    <p class="mb-20">{{insurancePrefix().$insurance->insurance_id}}</p>
                                </div>
                            </div> --}}
                            <div class="col-sm-4 col-md-3 col-lg-3">
                                <div class="detail-group">
                                    <h6>{{ __('Claim Date') }}</h6>
                                    <p class="mb-20">{{ dateFormat($claim->date) }}</p>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3 col-lg-3">
                                <div class="detail-group">
                                    <h6>{{ __('Status') }}</h6>
                                    <p class="mb-20">
                                        @switch($claim->status)
                                            @case('claim_intimated')
                                                <span
                                                    class="badge badge-primary">{{ \App\Models\Claim::$status[$claim->status] }}</span>
                                            @break

                                            @case('link_shared')
                                                <span
                                                    class="badge badge-info">{{ \App\Models\Claim::$status[$claim->status] }}</span>
                                            @break

                                            @case('documents_pending')
                                                <span
                                                    class="badge badge-warning">{{ \App\Models\Claim::$status[$claim->status] }}</span>
                                            @break

                                            @case('documents_submitted')
                                                <span
                                                    class="badge badge-info">{{ \App\Models\Claim::$status[$claim->status] }}</span>
                                            @break

                                            @case('documents_mismatched')
                                                <span
                                                    class="badge badge-warning">{{ \App\Models\Claim::$status[$claim->status] }}</span>
                                            @break

                                            @case('under_review')
                                                <span
                                                    class="badge badge-warning">{{ \App\Models\Claim::$status[$claim->status] }}</span>
                                            @break

                                            @case('rejected')
                                                <span
                                                    class="badge badge-danger">{{ \App\Models\Claim::$status[$claim->status] }}</span>
                                            @break

                                            @case('approved')
                                                <span
                                                    class="badge badge-success">{{ \App\Models\Claim::$status[$claim->status] }}</span>
                                            @break

                                            @case('pre_approval_given')
                                                <span
                                                    class="badge badge-info">{{ \App\Models\Claim::$status[$claim->status] }}</span>
                                            @break

                                            @case('final_approval_given')
                                                <span
                                                    class="badge badge-success">{{ \App\Models\Claim::$status[$claim->status] }}</span>
                                            @break

                                            @case('under_repair')
                                                <span
                                                    class="badge badge-warning">{{ \App\Models\Claim::$status[$claim->status] }}</span>
                                            @break

                                            @case('final_bill_submitted')
                                                <span
                                                    class="badge badge-info">{{ \App\Models\Claim::$status[$claim->status] }}</span>
                                            @break

                                            @case('claim_settled')
                                                <span
                                                    class="badge badge-success">{{ \App\Models\Claim::$status[$claim->status] }}</span>
                                            @break

                                            @case('final_report_submitted')
                                                <span
                                                    class="badge badge-info">{{ \App\Models\Claim::$status[$claim->status] }}</span>
                                            @break

                                            @default
                                                <span
                                                    class="badge badge-danger">{{ \App\Models\Claim::$status[$claim->status] }}</span>
                                        @endswitch
                                    </p>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3 col-lg-3">
                                <div class="detail-group">
                                    <h6>{{ __('Created At') }}</h6>
                                    <p class="mb-20">{{ dateFormat($claim->created_at) }}</p>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3 col-lg-3">
                                <div class="detail-group">
                                    <h6>{{ __('Note') }}</h6>
                                    <p class="mb-20">{{ $claim->notes }}</p>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <div class="detail-group">
                                    <h6>{{ __('Reason') }}</h6>
                                    <p class="mb-20">{{ $claim->cause_of_accident }}</p>
                                </div>
                            </div>
                            {{-- Start Tabs --}}
                            <ul class="nav nav-tabs" id="claimTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="professional-fee-details-tab" data-bs-toggle="tab"
                                        href="#professional-fee-details" role="tab" aria-controls="professional-fee-details-tab"
                                        aria-selected="true">{{ __('Fee Bill') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="policy-details-tab" data-bs-toggle="tab"
                                        href="#policy-details" role="tab" aria-controls="policy-details"
                                        aria-selected="true">{{ __('Policy Details') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="vehicle-details-tab" data-bs-toggle="tab"
                                        href="#vehicle-details" role="tab" aria-controls="vehicle-details"
                                        aria-selected="false">{{ __('Vehicle Details') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="documents-tab" data-bs-toggle="tab" href="#documents"
                                        role="tab" aria-controls="documents"
                                        aria-selected="false">{{ __('Document') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="damage-details-tab" data-bs-toggle="tab" href="#damage-details"
                                        role="tab" aria-controls="damage-details"
                                        aria-selected="false">{{ __('Estimate Damage') }}</a>
                                </li>
                                <!-- New Tab for Damage Photos -->
                                <li class="nav-item">
                                    <a class="nav-link" id="damage-photos-tab" data-bs-toggle="tab" href="#damage-photos"
                                        role="tab" aria-controls="damage-photos"
                                        aria-selected="false">{{ __('Video & Photos') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="final-report-tab" data-bs-toggle="tab" href="#final-report"
                                        role="tab" aria-controls="final-report"
                                        aria-selected="false">{{ __('Final Report') }}</a>
                                </li>
                            </ul>

                            <div class="tab-content" id="claimTabContent">
                                {{-- Enter Professional Fee Details --}}
                                <div class="tab-pane fade show active" class="col-md-12 mt-5" id="professional-fee-details" role="tabpanel"
                                    aria-labelledby="professional-fee-details-tab">
                                    <div class="col-md-12 mt-5" id="fee-bill-pdf-content">
                                    <form action="{{ $feesBillData ? route('claim.feeBillData', [$claim->id, $feesBillData->id]) : route('claim.feeBillData', [$claim->id]) }}" method="POST">

                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <!-- Professional Fee on Estimate -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="professional_fee">{{ __('Professional Fee on Estimate (Rs.)') }}</label>
                                                    <input type="text" class="form-control" id="professional_fee" name="professional_fee"
                                                        value="{{ old('professional_fee', trim($feesBillData['professional_fee'] ?? '')) }}" required>
                                                    @error('professional_fee')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Reinspection Charges -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="reinspection_fee">{{ __('Reinspection Charges') }}</label>
                                                    <input type="text" class="form-control" id="reinspection_fee" name="reinspection_fee"
                                                        value="{{ old('reinspection_fee', trim($feesBillData['reinspection_fee'] ?? '')) }}">
                                                    @error('reinspection_fee')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Date of Visits -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="date_of_visits">{{ __('Date of Visits') }}</label>
                                                    <input type="date" class="form-control" id="date_of_visits" name="date_of_visits"
                                                        value="{{ old('date_of_visits', trim($feesBillData['date_of_visits'] ?? '')) }}">
                                                    @error('date_of_visits')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Halting Charges -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="halting_charges">{{ __('Halting Charges') }}</label>
                                                    <input type="text" class="form-control" id="halting_charges" name="halting_charges"
                                                        value="{{ old('halting_charges', trim($feesBillData['halting_charges'] ?? '')) }}">
                                                    @error('halting_charges')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Conveyance Charges - Final -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="conveyance_final">{{ __('Conveyance Charges - Final') }}</label>
                                                    <input type="text" class="form-control" id="conveyance_final" name="conveyance_final"
                                                        value="{{ old('conveyance_final', trim($feesBillData['conveyance_final'] ?? '')) }}">
                                                    @error('conveyance_final')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Total Distance Final -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="distance_final">{{ __('Total Distance (Final) in km') }}</label>
                                                    <input type="number" class="form-control" id="distance_final" name="distance_final"
                                                        value="{{ old('distance_final', trim($feesBillData['distance_final'] ?? '')) }}">
                                                    @error('distance_final')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Rate per km Final -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="rate_per_km_final">{{ __('Rate per km (Final)') }}</label>
                                                    <input type="text" class="form-control" id="rate_per_km_final" name="rate_per_km_final"
                                                        value="{{ old('rate_per_km_final', trim($feesBillData['rate_per_km_final'] ?? '')) }}">
                                                    @error('rate_per_km_final')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Conveyance Charges - Re-inspection -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="conveyance_reinspection">{{ __('Conveyance Charges -  Re-inspection') }}</label>
                                                    <input type="text" class="form-control" id="conveyance_reinspection" name="conveyance_reinspection"
                                                        value="{{ old('conveyance_reinspection', trim($feesBillData['conveyance_reinspection'] ?? '')) }}">
                                                    @error('conveyance_reinspection')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Total Distance Re-inspection -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="distance_reinspection">{{ __('Total Distance (Re-inspection) in km') }}</label>
                                                    <input type="number" class="form-control" id="distance_reinspection" name="distance_reinspection"
                                                        value="{{ old('distance_reinspection', trim($feesBillData['distance_reinspection'] ?? '')) }}">
                                                    @error('distance_reinspection')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Rate per km Re-inspection -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="rate_per_km_reinspection">{{ __('Rate per km (Re-inspection)') }}</label>
                                                    <input type="text" class="form-control" id="rate_per_km_reinspection" name="rate_per_km_reinspection"
                                                        value="{{ old('rate_per_km_reinspection', trim($feesBillData['rate_per_km_reinspection'] ?? '')) }}">
                                                    @error('rate_per_km_reinspection')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Photographs -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="photos_count">{{ __('Number of Photos') }}</label>
                                                    <input type="number" class="form-control" id="photos_count" name="photos_count"
                                                        value="{{ old('photos_count', trim($feesBillData['photos_count'] ?? '')) }}">
                                                    @error('photos_count')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Rate per Photographs -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="photo_rate">{{ __('Rate per Photograph') }}</label>
                                                    <input type="text" class="form-control" id="photo_rate" name="photo_rate"
                                                        value="{{ old('photo_rate', trim($feesBillData['photo_rate'] ?? '')) }}">
                                                    @error('photo_rate')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Toll Tax -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="toll_tax">{{ __('Toll Tax') }}</label>
                                                    <input type="text" class="form-control" id="toll_tax" name="toll_tax"
                                                        value="{{ old('toll_tax', trim($feesBillData['toll_tax'] ?? '')) }}">
                                                    @error('toll_tax')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- TOTAL -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="total_amount">{{ __('Total Amount') }}</label>
                                                    <input type="text" class="form-control" id="total_amount" name="total_amount"
                                                        value="{{ old('total_amount', trim($feesBillData['total_amount'] ?? '')) }}" required>
                                                    @error('total_amount')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            @php
                                                $cgst = $feesBillData['cgst'] ?? 0;  // If cgst is null, set it to 0
                                                $sgst = $feesBillData['sgst'] ?? 0;  // If sgst is null, set it to 0

                                                $gstType = old('gst_type', ($cgst > 0 && $sgst > 0) ? 'cgst_sgst' : 'igst'); 
                                            @endphp

                                            <!-- GST Type Selection -->
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><strong>Select GST Type</strong></label>
                                                    <div>
                                                        <input type="radio" id="gst_cgst_sgst" name="gst_type" value="cgst_sgst" 
                                                            {{ $gstType == 'cgst_sgst' ? 'checked' : '' }}>
                                                        <label for="gst_cgst_sgst">CGST + SGST (9% Each)</label>
                                                    </div>
                                                    <div>
                                                        <input type="radio" id="gst_igst" name="gst_type" value="igst" 
                                                            {{ $gstType == 'igst' ? 'checked' : '' }}>
                                                        <label for="gst_igst">IGST (18%)</label>
                                                    </div>
                                                </div>
                                            </div>


                                            <!-- CGST & SGST Show Only If Not 0 -->
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="cgst">{{ __('CGST @ 9%') }}</label>
                                                    <input type="text" class="form-control" id="cgst" name="cgst" value="{{ $feesBillData['cgst'] ?? 0 }}" readonly>
                                                    @error('cgst')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="sgst">{{ __('SGST @ 9%') }}</label>
                                                    <input type="text" class="form-control" id="sgst" name="sgst" value="{{ $feesBillData['sgst'] ?? 0 }}" readonly>
                                                    @error('sgst')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- IGST Show Only If Not 0 and CGST & SGST are 0 -->
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="igst">{{ __('IGST @ 18%') }}</label>
                                                    <input type="text" class="form-control" id="igst" name="igst" value="{{ $feesBillData['igst'] ?? 0}}" readonly>
                                                    @error('igst')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>


                                            <!-- Net Total -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="net_total"><strong>{{ __('Net Total Amount') }}</strong></label>
                                                    <input type="text" class="form-control" id="net_total" name="net_total" value="{{ $feesBillData['net_total'] ?? 0 }}"  readonly>
                                                    @error('net_total')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>


                                            <!-- BANK DETAILS -->
                                            <div class="col-md-12 mt-4">
                                                <h4 class="text-primary">Bank & Other Details</h4>
                                            </div>

                                            <!-- Bank Name -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="bank_name">{{ __('Bank Name') }}</label>
                                                    <input type="text" class="form-control" id="bank_name" name="bank_name"
                                                        value="{{ old('bank_name', trim($feesBillData['bank_name'] ?? 'Bank of Baroda')) }}" required>
                                                    @error('bank_name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>  
                                            </div>

                                            <!-- Branch Name -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="branch_name">{{ __('Branch Name') }}</label>
                                                    <input type="text" class="form-control" id="branch_name" name="branch_name"
                                                        value="{{ old('branch_name', trim($feesBillData['branch_name'] ?? 'IBB Jaipur Branch')) }}" required>
                                                    @error('branch_name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Branch Address  -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="branch_address">{{ __('Branch Address') }}</label>
                                                    <input type="text" class="form-control" id="branch_address" name="branch_address"
                                                        value="{{ old('branch_address', trim($feesBillData['branch_address'] ?? 'IBB Jaipur Branch, Jaipur - 302001')) }}" required>
                                                    @error('branch_address')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Bank Account Number -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="account_number">{{ __('Bank A/C Number') }}</label>
                                                    <input type="text" class="form-control" id="account_number" name="account_number"
                                                        value="{{ old('account_number', trim($feesBillData['account_number'] ?? '24750200006187')) }}" required>
                                                    @error('account_number')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- IFSC Code -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="ifsc_code">{{ __('IFSC Code') }}</label>
                                                    <input type="text" class="form-control" id="ifsc_code" name="ifsc_code"
                                                        value="{{ old('ifsc_code', trim($feesBillData['ifsc_code'] ?? 'BARB0JAIINT')) }}" required>
                                                    @error('ifsc_code')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- MICR Code -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="micr_code">{{ __('MICR Code') }}</label>
                                                    <input type="text" class="form-control" id="micr_code" name="micr_code"
                                                        value="{{ old('micr_code', trim($feesBillData['micr_code'] ?? '302012013')) }}">
                                                    @error('micr_code')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- ID No. -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="id_no">{{ __('ID No.') }}</label>
                                                    <input type="text" class="form-control" id="id_no" name="id_no"
                                                        value="{{ old('id_no', trim($feesBillData['id_no'] ?? '2300013333')) }}">
                                                    @error('id_no')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- GSTIN (UIIC) -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="gstin">{{ __('GSTIN (UIIC)') }}</label>
                                                    <input type="text" class="form-control" id="gstin" name="gstin"
                                                        value="{{ old('gstin', trim($feesBillData['gstin'] ?? '24AAACU5552C3ZN')) }}">
                                                    @error('gstin')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            
                                        </div>
                                        <div class="form-group mt-4">
                                            <button type="submit" class="btn btn-primary">{{ __('Submit Professional Fee Details') }}</button>
                                        </div> 
                                    </form>
                                    </div>
                                    <!-- Download button: -->
                                    <div class="form-group mt-2 d-flex justify-content-end">
                                        <button
                                            type="button"
                                            class="btn btn-success"
                                            data-target="fee-bill-pdf-content"
                                            data-filename="fee-bill-details.pdf"
                                            onclick="downloadSectionAsPDF(this)">
                                            Download Fee Bill PDF
                                        </button>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="policy-details" role="tabpanel"
                                    aria-labelledby="policy-details-tab">
                                    <div class="col-md-12 mt-5" id="policy-details-pdf-content">
                                        <form action="{{ route('insuranceDetails.update', $claim->id) }}" method="POST">
                                            @csrf
                                            @method('PUT') <!-- To specify we are updating an existing record -->

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="policy_type">{{ __('Policy Type') }}</label>
                                                        <input type="text" class="form-control" id="policy_type"
                                                            name="policy_type"
                                                            value="{{ old('policy_type', trim($insuranceDetail['policy_type'])) }}">
                                                        @error('policy_type')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="policy_number">{{ __('Policy Number') }}</label>
                                                        <input type="text" class="form-control" id="policy_number"
                                                            name="policy_number" placeholder="Enter part name"
                                                            value="{{ old('policy_number', trim($insuranceDetail['policy_number'])) }}">
                                                        @error('policy_number')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="insured_name">{{ __('Name of the Insured') }}</label>
                                                        <input type="text" class="form-control" id="insured_name"
                                                            name="insured_name"
                                                            value="{{ old('insured_name', trim($insuranceDetail['insured_name'])) }}">
                                                        @error('insured_name')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label
                                                            for="insured_declared_value">{{ __('Insured Declared Value') }}</label>
                                                        <input type="text" class="form-control"
                                                            id="insured_declared_value" name="insured_declared_value"
                                                            value="{{ old('insured_declared_value', trim($insuranceDetail['insured_declared_value'])) }}">
                                                        @error('insured_declared_value')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label
                                                            for="issuing_office_address_code">{{ __('Issuing Office Address Code') }}</label>
                                                        <input type="text" class="form-control"
                                                            id="issuing_office_address_code"
                                                            name="issuing_office_address_code"
                                                            value="{{ old('issuing_office_address_code', trim($insuranceDetail['issuing_office_address_code'])) }}">
                                                        @error('issuing_office_address_code')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="occupation">{{ __('Business/Occupation') }}</label>
                                                        <input type="text" class="form-control" id="occupation"
                                                            name="occupation"
                                                            value="{{ old('occupation', trim($insuranceDetail['occupation'])) }}">
                                                        @error('occupation')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="mobile">{{ __('Mobile No.') }}</label>
                                                        <input type="text" class="form-control" id="mobile"
                                                            name="mobile"
                                                            value="{{ old('mobile', trim($insuranceDetail['mobile'])) }}">
                                                        @error('mobile')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="vehicle">{{ __('Vehicle No.') }}</label>
                                                        <input type="text" class="form-control" id="vehicle"
                                                            name="vehicle"
                                                            value="{{ old('vehicle', trim($insuranceDetail['vehicle'])) }}">
                                                        @error('vehicle')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="engine_no">{{ __('Engine No.') }}</label>
                                                        <input type="text" class="form-control" id="engine_no"
                                                            name="engine_no"
                                                            value="{{ old('engine_no', trim($insuranceDetail['engine_no'])) }}">
                                                        @error('engine_no')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="chassis_no">{{ __('Chassis No.') }}</label>
                                                        <input type="text" class="form-control" id="chassis_no"
                                                            name="chassis_no"
                                                            value="{{ old('chassis_no', trim($insuranceDetail['chassis_no'])) }}">
                                                        @error('chassis_no')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="make">{{ __('Make') }}</label>
                                                        <input type="text" class="form-control" id="make"
                                                            name="make"
                                                            value="{{ old('make', trim($insuranceDetail['make'])) }}">
                                                        @error('make')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="model">{{ __('Model') }}</label>
                                                        <input type="text" class="form-control" id="model"
                                                            name="model"
                                                            value="{{ old('model', trim($insuranceDetail['model'])) }}">
                                                        @error('model')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label
                                                            for="year_of_manufacture">{{ __('Year of Manufacture') }}</label>
                                                        <input type="text" class="form-control"
                                                            id="year_of_manufacture" name="year_of_manufacture"
                                                            value="{{ old('year_of_manufacture', trim($insuranceDetail['year_of_manufacture'])) }}">
                                                        @error('year_of_manufacture')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="cubic_capacity">{{ __('Cubic Capacity') }}</label>
                                                        <input type="number" class="form-control" id="cubic_capacity"
                                                            name="cubic_capacity"
                                                            value="{{ old('cubic_capacity', trim($insuranceDetail['cubic_capacity'])) }}">
                                                        @error('cubic_capacity')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="seating_capacity">{{ __('Seating Capacity') }}</label>
                                                        <input type="number" class="form-control" id="seating_capacity"
                                                            name="seating_capacity"
                                                            value="{{ old('seating_capacity', trim($insuranceDetail['seating_capacity'])) }}">
                                                        @error('seating_capacity')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label
                                                            for="insurance_start_date">{{ __('Insurance Start Date') }}</label>
                                                        <input type="date" class="form-control"
                                                            id="insurance_start_date" name="insurance_start_date"
                                                            value="{{ old('insurance_start_date', $insuranceDetail->insurance_start_date ? \Carbon\Carbon::parse($insuranceDetail->insurance_start_date)->toDateString() : '') }}">
                                                        @error('insurance_start_date')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label
                                                            for="insurance_expiry_date">{{ __('Insurance Expiry Date') }}</label>
                                                        <input type="date" class="form-control"
                                                            id="insurance_expiry_date" name="insurance_expiry_date"
                                                            value="{{ old('insurance_expiry_date', $insuranceDetail->insurance_expiry_date ? \Carbon\Carbon::parse($insuranceDetail->insurance_expiry_date)->toDateString() : '') }}">
                                                        @error('insurance_expiry_date')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label
                                                            for="previous_policy_number">{{ __('Previous Policy Number') }}</label>
                                                        <input type="text" class="form-control"
                                                            id="previous_policy_number" name="previous_policy_number"
                                                            value="{{ old('previous_policy_number', trim($insuranceDetail['previous_policy_number'])) }}">
                                                        @error('previous_policy_number')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="zero_dep">{{ __('Zero Dep(Yes/No)') }}</label>
                                                        <input type="text" class="form-control" id="zero_dep"
                                                            name="zero_dep"
                                                            value="{{ old('zero_dep', trim($insuranceDetail['zero_dep'])) }}">
                                                        @error('zero_dep')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label
                                                            for="no_claim_bonus_percentage">{{ __('No Claim Bonus (%)') }}</label>
                                                        <input type="text" class="form-control"
                                                            id="no_claim_bonus_percentage"
                                                            name="no_claim_bonus_percentage"
                                                            value="{{ old('no_claim_bonus_percentage', trim($insuranceDetail['no_claim_bonus_percentage'])) }}">
                                                        @error('no_claim_bonus_percentage')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label
                                                            for="nil_depreciation">{{ __('Nil Depreciation Without Excess(Yes/No)') }}</label>
                                                        <input type="text" class="form-control" id="nil_depreciation"
                                                            name="nil_depreciation"
                                                            value="{{ old('nil_depreciation', trim($insuranceDetail['nil_depreciation'])) }}">
                                                        @error('nil_depreciation')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label
                                                            for="additional_towing_charges">{{ __('Additional Towing Charges') }}</label>
                                                        <input type="number" class="form-control"
                                                            id="additional_towing_charges"
                                                            name="additional_towing_charges"
                                                            value="{{ old('additional_towing_charges', trim($insuranceDetail['additional_towing_charges'])) }}">
                                                        @error('additional_towing_charges')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label
                                                            for="insured_address">{{ __('Address of the Insured') }}</label>
                                                        <textarea class="form-control" id="insured_address" name="insured_address" rows="4">{{ old('insured_address', trim($insuranceDetail['insured_address'])) }}</textarea>
                                                        @error('insured_address')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label
                                                            for="issuing_office_address">{{ __('Issuing Office Address') }}</label>
                                                        <textarea class="form-control" id="issuing_office_address" name="issuing_office_address" rows="4">{{ old('issuing_office_address', trim($insuranceDetail['issuing_office_address'])) }}</textarea>
                                                        @error('issuing_office_address')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="form-group mt-4">
                                                <button type="submit"
                                                    class="btn btn-primary">{{ __('Update Insurance Details') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- Download button: -->
                                    <div class="form-group mt-2 d-flex justify-content-end">
                                        <button
                                            type="button"
                                            class="btn btn-success"
                                            data-target="policy-details-pdf-content"
                                            data-filename="policy-details.pdf"
                                            onclick="downloadSectionAsPDF(this)">
                                            Download Policy Details PDF
                                        </button>
                                    </div>
                                </div>


                                {{-- Vehicle Details Tab --}}
                                <div class="tab-pane fade" id="vehicle-details" role="tabpanel"
                                    aria-labelledby="vehicle-details-tab">
                                    <div class="col-md-12 mt-5" id="vehicle-driving-details-pdf-content">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover">
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 20%;">Aadhaar Name: {{ $claim->aadhaar_name ?? '' }}</td>
                                                        <td style="width: 20%;">Aadhaar Number: {{ $claim->aadhaar_number ?? '' }}</td>
                                                    </tr>

                                                    @if($claim->aadhaar_name && $claim->vehicleRegistrations->isNotEmpty())
                                                        @php
                                                            $ownerName = $claim->vehicleRegistrations[0]->owner_name ?? null;
                                                        @endphp

                                                        @if(strtolower(trim($claim->aadhaar_name)) !== strtolower(trim($ownerName)))
                                                            <tr>
                                                                <td colspan="2" style="color: red; font-weight: bold;">
                                                                    ⚠️ Aadhaar name and Owner name do not match. They must be the same.
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover">
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 20%;">Vehicle Number:
                                                            {{ $vehicleNumber ?? '' }}</td>
                                                        @if($numberPlate)
                                                            <td style="width: 80%;">
                                                                <a href="{{ isset($numberPlate[0]['filename']) ? asset('storage/upload/document/number_plate/' . $numberPlate[0]['filename']) : '#' }}"
                                                                    target="_blank" class="btn btn-sm btn-info me-2">
                                                                    View
                                                                </a>
                                                            </td>
                                                        @endif
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="table-responsive">
                                            @if ($claim->vehicleRegistrations->isNotEmpty())
                                                <table class="table table-striped table-bordered table-hover">
                                                    <tbody>
                                                        @foreach ($claim->vehicleRegistrations as $vehicle)
                                                            <tr>
                                                                <td style="width: 20%;">{{ __('Vehicle Number') }}</td>
                                                                <td style="width: 80%;">{{ $vehicle->rc_number }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('Registration Date') }}</td>
                                                                <td>{{ $vehicle->registration_date->format('Y-m-d') }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('Owner Name') }}</td>
                                                                <td>{{ $vehicle->owner_name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('Owner Number') }}</td>
                                                                <td>{{ $vehicle->owner_number }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('Vehicle Category') }}</td>
                                                                <td>{{ $vehicle->vehicle_category }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('Chassis Number') }}</td>
                                                                <td>{{ $vehicle->vehicle_chasi_number }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('Engine Number') }}</td>
                                                                <td>{{ $vehicle->vehicle_engine_number }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('Maker Model') }}</td>
                                                                <td>{{ $vehicle->maker_model }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('Body Type') }}</td>
                                                                <td>{{ $vehicle->body_type }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('Fuel Type') }}</td>
                                                                <td>{{ $vehicle->fuel_type }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('Color') }}</td>
                                                                <td>{{ $vehicle->color }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('Financed') }}</td>
                                                                <td>{{ $vehicle->financed ? 'Yes' : 'No' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('Fit Up To') }}</td>
                                                                <td>{{ $vehicle->fit_up_to ? $vehicle->fit_up_to->format('Y-m-d') : 'N/A' }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('Insurance Up To') }}</td>
                                                                <td>{{ $vehicle->insurance_upto ? $vehicle->insurance_upto->format('Y-m-d') : 'N/A' }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('RC Status') }}</td>
                                                                <td>{{ $vehicle->rc_status }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('Blacklist Status') }}</td>
                                                                <td>{{ $vehicle->blacklist_status }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @else
                                                <p><strong>No vehicle registrations found for this claim.</strong></p>
                                            @endif
                                        </div>

                                        <form action="{{ route('dlDetails.update', $claim->id) }}" method="POST">
                                            @csrf
                                            @method('PUT') <!-- To specify we are updating an existing record -->
                                            <div class="table-responsive mt-2">
                                                <table class="table table-sm table-striped table-bordered">
                                                    <thead>
                                                        <tr style="padding-top: 10px; padding-bottom: 10px;">
                                                            <th colspan="2" class="text-center"
                                                                style="padding-top: 10px; padding-bottom: 10px;">
                                                                <strong>Driving License Details</strong>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td style="width: 20%;">{{ __('License Number') }}</td>
                                                            <td style="width: 80%;">
                                                                <input type="text" class="form-control"
                                                                    id="license_number" name="license_number"
                                                                    value="{{ old('license_number', trim($dlDetail['license_number'])) }}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{ __('Name of the License Holder') }}</td>
                                                            <td>
                                                                <input type="text" class="form-control" id="name"
                                                                    name="name"
                                                                    value="{{ old('name', trim($dlDetail['name'])) }}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{ __('Date of Birth') }}</td>
                                                            <td>
                                                                <input type="date" class="form-control" id="dob"
                                                                    name="dob"
                                                                    value="{{ old('dob', $dlDetail->dob ? \Carbon\Carbon::parse($dlDetail->dob)->toDateString() : '') }}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{ __('Father/Husband\'s Name') }}</td>
                                                            <td>
                                                                <input type="text" class="form-control"
                                                                    id="father_name" name="father_name"
                                                                    value="{{ old('father_name', trim($dlDetail['father_name'])) }}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{ __('Address of the License Holder') }}</td>
                                                            <td>
                                                                <textarea class="form-control" id="address" name="address" rows="4">{{ old('address', trim($dlDetail['address'])) }}</textarea>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{ __('License Issue Date') }}</td>
                                                            <td>
                                                                <input type="date" class="form-control"
                                                                    id="issue_date" name="issue_date"
                                                                    value="{{ old('issue_date', $dlDetail->issue_date ? \Carbon\Carbon::parse($dlDetail->issue_date)->toDateString() : '') }}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{ __('License Expiry/Validity Date') }}</td>
                                                            <td>
                                                                <input type="date" class="form-control"
                                                                    id="validity_date" name="validity_date"
                                                                    value="{{ old('validity_date', $dlDetail->validity_date ? \Carbon\Carbon::parse($dlDetail->validity_date)->toDateString() : '') }}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{ __('Vehicle Class') }}</td>
                                                            <td>
                                                                <input type="text" class="form-control"
                                                                    id="vehicle_class" name="vehicle_class"
                                                                    value="{{ old('vehicle_class', trim($dlDetail['vehicle_class'])) }}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{ __('State Code') }}</td>
                                                            <td>
                                                                <input type="text" class="form-control"
                                                                    id="state_code" name="state_code"
                                                                    value="{{ old('state_code', trim($dlDetail['state_code'])) }}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{ __('License Type') }}</td>
                                                            <td>
                                                                <input type="text" class="form-control"
                                                                    id="license_type" name="license_type"
                                                                    value="{{ old('license_type', trim($dlDetail['license_type'])) }}">
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="form-group mt-4">
                                                <button type="submit"
                                                    class="btn btn-primary">{{ __('Update Driving License Details') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- Download button: -->
                                    <div class="form-group mt-2 d-flex justify-content-end">
                                        <button
                                            type="button"
                                            class="btn btn-success"
                                            data-target="vehicle-driving-details-pdf-content"
                                            data-filename="vehicle-driving-details.pdf"
                                            onclick="downloadSectionAsPDF(this)">
                                            Download Vehicle & Driving Details PDF
                                        </button>
                                    </div>
                                </div>
                                {{-- Document Tab --}}
                                <div class="tab-pane fade" id="documents" role="tabpanel"
                                    aria-labelledby="documents-tab">
                                    <div class="col-md-12 mt-5">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>{{ __('Document Type') }}</th>
                                                        <th>{{ __('Status') }}</th>
                                                        <th>{{ __('Actions') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $numberPlateFiles = json_decode($claim->number_plate_file, true) ?? [];
                                                        $aadhaarFiles = json_decode($claim->aadhaar_files, true) ?? [];
                                                        $panCardFiles = json_decode($claim->pancard_file, true) ?? [];
                                                        $rcBookFiles = json_decode($claim->rcbook_files, true) ?? [];
                                                        $taxReceiptFiles = json_decode($claim->tax_receipt_file, true) ?? [];
                                                        $salesInvoiceFiles = json_decode($claim->sales_invoice_file, true) ?? [];
                                                        $dlFiles = json_decode($claim->dl_files, true) ?? [];
                                                        $insuranceFile = $claim->insurance_file;
                                                        $claimForm = $claim->claim_form_file;
                                                        $claimIntimation = $claim->claim_intimation_file;
                                                        $consentForm = $claim->consent_form_file;
                                                        $satisfactionVoucher = $claim->satisfaction_voucher_file;
                                                        $firFile = $claim->fir_file;
                                                        $finalBillFiles = $claim->final_bill_files;
                                                        $paymentRecepitFiles = json_decode($claim->payment_receipt_files, true) ?? [];
                                                    @endphp
                                                    {{-- number Plate Files --}}
                                                    <tr>
                                                        <td>Number Plate</td>
                                                        <td>
                                                            @if (!empty($numberPlateFiles))
                                                                <span style="color: green;">&#x2714;</span> Available
                                                                ({{ count($numberPlateFiles) }} file(s))
                                                            @else
                                                                <span style="color: red;">&#x2716;</span> Pending Document
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if (!empty($numberPlateFiles))
                                                                @foreach ($numberPlateFiles as $number_plate)
                                                                    <div class="d-flex align-items-center mb-2">
                                                                        <!-- View Icon -->
                                                                        <a href="{{ asset("storage/upload/document/claim-{$claim->id}/number_plate/" . $number_plate['filename']) }}"
                                                                            target="_blank" class="text-warning"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-original-title="View">
                                                                            <i data-feather="eye"></i>
                                                                        </a>
                                                                        <!-- Update Icon -->
                                                                        <input type="file"
                                                                            class="form-control form-control-sm d-none"
                                                                            id="number_plate-update-{{ $number_plate['filename'] }}"
                                                                            data-document-type="number_plate"
                                                                            data-file-to-update="{{ $number_plate['filename'] }}"
                                                                            accept=".jpg,.jpeg,.png,.pdf"
                                                                            onchange="updateDocument(this)">
                                                                        <a href="javascript:void(0);"
                                                                            class="text-success ms-2"
                                                                            onclick="document.getElementById('number_plate-update-{{ $number_plate['filename'] }}').click()"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-original-title="Edit">
                                                                            <i data-feather="edit"></i>
                                                                        </a>
                                                                        <!-- Delete Icon -->
                                                                        <a href="javascript:void(0);"
                                                                            class="text-danger ms-2"
                                                                            onclick="deleteDocument('number_plate', '{{ $number_plate['filename'] }}')"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-original-title="Delete">
                                                                            <i data-feather="trash-2"></i>
                                                                        </a>
                                                                    </div>
                                                                @endforeach
                                                                <!-- Add More Documents -->
                                                                <a href="javascript:void(0);" class="text-primary mt-2"
                                                                    onclick="addMoreDocuments('number_plate')"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-original-title="Add More">
                                                                    <i class="ti-plus"></i>
                                                                </a>
                                                            @else
                                                                <!-- Upload Icon -->
                                                                <a href="javascript:void(0);" class="text-primary"
                                                                    onclick="addMoreDocuments('number_plate')"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-original-title="Upload">
                                                                    <i data-feather="upload"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    {{-- Aadhaar Files --}}
                                                    <tr>
                                                        <td>Aadhaar</td>
                                                        <td>
                                                            @if (!empty($aadhaarFiles))
                                                                <span style="color: green;">&#x2714;</span> Available
                                                                ({{ count($aadhaarFiles) }} file(s))
                                                            @else
                                                                <span style="color: red;">&#x2716;</span> Pending Document
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if (!empty($aadhaarFiles))
                                                                @foreach ($aadhaarFiles as $aadhaar)
                                                                    <div class="d-flex align-items-center mb-2">
                                                                        <!-- View Icon -->
                                                                        <a href="{{ asset("storage/upload/document/claim-{$claim->id}/aadhaar/" . $aadhaar) }}"
                                                                            target="_blank" class="text-warning"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-original-title="View">
                                                                            <i data-feather="eye"></i>
                                                                        </a>
                                                                        <!-- Update Icon -->
                                                                        <input type="file"
                                                                            class="form-control form-control-sm d-none"
                                                                            id="aadhaar-update-{{ $aadhaar }}"
                                                                            data-document-type="aadhaar"
                                                                            data-file-to-update="{{ $aadhaar }}"
                                                                            accept=".jpg,.jpeg,.png,.pdf"
                                                                            onchange="updateDocument(this)">
                                                                        <a href="javascript:void(0);"
                                                                            class="text-success ms-2"
                                                                            onclick="document.getElementById('aadhaar-update-{{ $aadhaar }}').click()"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-original-title="Edit">
                                                                            <i data-feather="edit"></i>
                                                                        </a>
                                                                        <!-- Delete Icon -->
                                                                        <a href="javascript:void(0);"
                                                                            class="text-danger ms-2"
                                                                            onclick="deleteDocument('aadhaar', '{{ $aadhaar }}')"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-original-title="Delete">
                                                                            <i data-feather="trash-2"></i>
                                                                        </a>
                                                                    </div>
                                                                @endforeach
                                                                <!-- Add More Documents -->
                                                                <a href="javascript:void(0);" class="text-primary mt-2"
                                                                    onclick="addMoreDocuments('aadhaar')"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-original-title="Add More">
                                                                    <i class="ti-plus"></i>
                                                                </a>
                                                            @else
                                                                <!-- Upload Icon -->
                                                                <a href="javascript:void(0);" class="text-primary"
                                                                    onclick="addMoreDocuments('aadhaar')"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-original-title="Upload">
                                                                    <i data-feather="upload"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Pan Card</td>
                                                        <td>
                                                            @if (!empty($panCardFiles))
                                                                <span style="color: green;">&#x2714;</span> Available
                                                                ({{ count($panCardFiles) }} file(s))
                                                            @else
                                                                <span style="color: red;">&#x2716;</span> Pending Document
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if (!empty($panCardFiles))
                                                                @foreach ($panCardFiles as $pan_card)
                                                                    <div class="d-flex align-items-center mb-2">
                                                                        <!-- View Icon -->
                                                                        <a href="{{ asset("storage/upload/document/claim-{$claim->id}/pan_card/" . $pan_card) }}"
                                                                            target="_blank" class="text-warning"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-original-title="View">
                                                                            <i data-feather="eye"></i>
                                                                        </a>
                                                                        <!-- Update Icon -->
                                                                        <input type="file"
                                                                            class="form-control form-control-sm d-none"
                                                                            id="pan_card-update-{{ $pan_card }}"
                                                                            data-document-type="pan_card"
                                                                            data-file-to-update="{{ $pan_card }}"
                                                                            accept=".jpg,.jpeg,.png,.pdf"
                                                                            onchange="updateDocument(this)">
                                                                        <a href="javascript:void(0);"
                                                                            class="text-success ms-2"
                                                                            onclick="document.getElementById('pan_card-update-{{ $pan_card }}').click()"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-original-title="Edit">
                                                                            <i data-feather="edit"></i>
                                                                        </a>
                                                                        <!-- Delete Icon -->
                                                                        <a href="javascript:void(0);"
                                                                            class="text-danger ms-2"
                                                                            onclick="deleteDocument('pan_card', '{{ $pan_card }}')"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-original-title="Delete">
                                                                            <i data-feather="trash-2"></i>
                                                                        </a>
                                                                    </div>
                                                                @endforeach
                                                                <!-- Add More Documents -->
                                                                <a href="javascript:void(0);" class="text-primary mt-2"
                                                                    onclick="addMoreDocuments('pan_card')"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-original-title="Add More">
                                                                    <i class="ti-plus"></i>
                                                                </a>
                                                            @else
                                                                <!-- Upload Icon -->
                                                                <a href="javascript:void(0);" class="text-primary"
                                                                    onclick="addMoreDocuments('pan_card')"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-original-title="Upload">
                                                                    <i data-feather="upload"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @if (!empty($rcBookFiles))
                                                    {{-- RC Book Files --}}
                                                    <tr>
                                                        <td>RC Book</td>
                                                        <td>
                                                            @if (!empty($rcBookFiles))
                                                                <span style="color: green;">&#x2714;</span> Available
                                                                ({{ count($rcBookFiles) }} file(s))
                                                            @else
                                                                <span style="color: red;">&#x2716;</span> Pending Document
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if (!empty($rcBookFiles))
                                                                @foreach ($rcBookFiles as $rcBook)
                                                                    <div class="d-flex align-items-center mb-2">
                                                                        <!-- View Icon -->
                                                                        <a href="{{ asset("storage/upload/document/claim-{$claim->id}/rcbook/" . $rcBook) }}"
                                                                            target="_blank" class="text-warning"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-original-title="View">
                                                                            <i data-feather="eye"></i>
                                                                        </a>
                                                                        <!-- Update Icon -->
                                                                        <input type="file"
                                                                            class="form-control form-control-sm d-none"
                                                                            id="rcbook-update-{{ $rcBook }}"
                                                                            data-document-type="rcbook"
                                                                            data-file-to-update="{{ $rcBook }}"
                                                                            accept=".jpg,.jpeg,.png,.pdf"
                                                                            onchange="updateDocument(this)">
                                                                        <a href="javascript:void(0);"
                                                                            class="text-success ms-2"
                                                                            onclick="document.getElementById('rcbook-update-{{ $rcBook }}').click()"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-original-title="Edit">
                                                                            <i data-feather="edit"></i>
                                                                        </a>
                                                                        <!-- Delete Icon -->
                                                                        <a href="javascript:void(0);"
                                                                            class="text-danger ms-2"
                                                                            onclick="deleteDocument('rcbook', '{{ $rcBook }}')"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-original-title="Delete">
                                                                            <i data-feather="trash-2"></i>
                                                                        </a>
                                                                    </div>
                                                                @endforeach
                                                                <!-- Add More Documents -->
                                                                <a href="javascript:void(0);" class="text-primary mt-2"
                                                                    onclick="addMoreDocuments('rcbook')"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-original-title="Add More">
                                                                    <i class="ti-plus"></i>
                                                                </a>
                                                            @else
                                                                <!-- Upload Icon -->
                                                                <a href="javascript:void(0);" class="text-primary"
                                                                    onclick="addMoreDocuments('rcbook')"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-original-title="Upload">
                                                                    <i data-feather="upload"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @else
                                                    {{-- Show Sales Invoice and Tax Receipt if RC Book is not available --}}
                                                    <tr>
                                                        <td>Tax Receipt</td>
                                                        <td>
                                                            @if (!empty($taxReceiptFiles))
                                                                <span style="color: green;">&#x2714;</span> Available
                                                                ({{ count($taxReceiptFiles) }} file(s))
                                                            @else
                                                                <span style="color: red;">&#x2716;</span> Pending Document
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if (!empty($taxReceiptFiles))
                                                                @foreach ($taxReceiptFiles as $taxReceipt)
                                                                    <div class="d-flex align-items-center mb-2">
                                                                        <!-- View Icon -->
                                                                        <a href="{{ asset("storage/upload/document/claim-{$claim->id}/tax_receipt/" . $taxReceipt) }}"
                                                                            target="_blank" class="text-warning"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-original-title="View">
                                                                            <i data-feather="eye"></i>
                                                                        </a>
                                                                        <!-- Update Icon -->
                                                                        <input type="file"
                                                                            class="form-control form-control-sm d-none"
                                                                            id="tax_receipt-update-{{ $taxReceipt }}"
                                                                            data-document-type="tax_receipt"
                                                                            data-file-to-update="{{ $taxReceipt }}"
                                                                            accept=".jpg,.jpeg,.png,.pdf"
                                                                            onchange="updateDocument(this)">
                                                                        <a href="javascript:void(0);"
                                                                            class="text-success ms-2"
                                                                            onclick="document.getElementById('tax_receipt-update-{{ $taxReceipt }}').click()"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-original-title="Edit">
                                                                            <i data-feather="edit"></i>
                                                                        </a>
                                                                        <!-- Delete Icon -->
                                                                        <a href="javascript:void(0);"
                                                                            class="text-danger ms-2"
                                                                            onclick="deleteDocument('tax_receipt', '{{ $taxReceipt }}')"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-original-title="Delete">
                                                                            <i data-feather="trash-2"></i>
                                                                        </a>
                                                                    </div>
                                                                @endforeach
                                                                <!-- Add More Documents -->
                                                                <a href="javascript:void(0);" class="text-primary mt-2"
                                                                    onclick="addMoreDocuments('tax_receipt')"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-original-title="Add More">
                                                                    <i class="ti-plus"></i>
                                                                </a>
                                                            @else
                                                                <!-- Upload Icon -->
                                                                <a href="javascript:void(0);" class="text-primary"
                                                                    onclick="addMoreDocuments('tax_receipt')"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-original-title="Upload">
                                                                    <i data-feather="upload"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Sales Invoice</td>
                                                        <td>
                                                            @if (!empty($salesInvoiceFiles))
                                                                <span style="color: green;">&#x2714;</span> Available
                                                                ({{ count($salesInvoiceFiles) }} file(s))
                                                            @else
                                                                <span style="color: red;">&#x2716;</span> Pending Document
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if (!empty($salesInvoiceFiles))
                                                                @foreach ($salesInvoiceFiles as $salesInvoice)
                                                                    <div class="d-flex align-items-center mb-2">
                                                                        <!-- View Icon -->
                                                                        <a href="{{ asset("storage/upload/document/claim-{$claim->id}/sales_invoice/" . $salesInvoice) }}"
                                                                            target="_blank" class="text-warning"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-original-title="View">
                                                                            <i data-feather="eye"></i>
                                                                        </a>
                                                                        <!-- Update Icon -->
                                                                        <input type="file"
                                                                            class="form-control form-control-sm d-none"
                                                                            id="sales_invoice-update-{{ $salesInvoice }}"
                                                                            data-document-type="sales_invoice"
                                                                            data-file-to-update="{{ $salesInvoice }}"
                                                                            accept=".jpg,.jpeg,.png,.pdf"
                                                                            onchange="updateDocument(this)">
                                                                        <a href="javascript:void(0);"
                                                                            class="text-success ms-2"
                                                                            onclick="document.getElementById('sales_invoice-update-{{ $salesInvoice }}').click()"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-original-title="Edit">
                                                                            <i data-feather="edit"></i>
                                                                        </a>
                                                                        <!-- Delete Icon -->
                                                                        <a href="javascript:void(0);"
                                                                            class="text-danger ms-2"
                                                                            onclick="deleteDocument('sales_invoice', '{{ $salesInvoice }}')"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-original-title="Delete">
                                                                            <i data-feather="trash-2"></i>
                                                                        </a>
                                                                    </div>
                                                                @endforeach
                                                                <!-- Add More Documents -->
                                                                <a href="javascript:void(0);" class="text-primary mt-2"
                                                                    onclick="addMoreDocuments('sales_invoice')"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-original-title="Add More">
                                                                    <i class="ti-plus"></i>
                                                                </a>
                                                            @else
                                                                <!-- Upload Icon -->
                                                                <a href="javascript:void(0);" class="text-primary"
                                                                    onclick="addMoreDocuments('sales_invoice')"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-original-title="Upload">
                                                                    <i data-feather="upload"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    {{-- DL Files --}}
                                                    <tr>
                                                        <td>DL</td>
                                                        <td>
                                                            @if (!empty($dlFiles))
                                                                <span style="color: green;">&#x2714;</span> Available
                                                                ({{ count($dlFiles) }} file(s))
                                                            @else
                                                                <span style="color: red;">&#x2716;</span> Pending Document
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if (!empty($dlFiles))
                                                                @foreach ($dlFiles as $dl)
                                                                    <div class="d-flex align-items-center mb-2">
                                                                        <!-- View Icon -->
                                                                        <a href="{{ asset("storage/upload/document/claim-{$claim->id}/dl/" . $dl) }}"
                                                                            target="_blank" class="text-warning"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-original-title="View">
                                                                            <i data-feather="eye"></i>
                                                                        </a>
                                                                        <!-- Update Icon -->
                                                                        <input type="file"
                                                                            class="form-control form-control-sm d-none"
                                                                            id="dl-update-{{ $dl }}"
                                                                            data-document-type="dl"
                                                                            data-file-to-update="{{ $dl }}"
                                                                            accept=".jpg,.jpeg,.png,.pdf"
                                                                            onchange="updateDocument(this)">
                                                                        <a href="javascript:void(0);"
                                                                            class="text-success ms-2"
                                                                            onclick="document.getElementById('dl-update-{{ $dl }}').click()"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-original-title="Edit">
                                                                            <i data-feather="edit"></i>
                                                                        </a>
                                                                        <!-- Delete Icon -->
                                                                        <a href="javascript:void(0);"
                                                                            class="text-danger ms-2"
                                                                            onclick="deleteDocument('dl', '{{ $dl }}')"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-original-title="Delete">
                                                                            <i data-feather="trash-2"></i>
                                                                        </a>
                                                                    </div>
                                                                @endforeach
                                                                <!-- Add More Documents -->
                                                                <a href="javascript:void(0);" class="text-primary mt-2"
                                                                    onclick="addMoreDocuments('dl')"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-original-title="Add More">
                                                                    <i class="ti-plus"></i>
                                                                </a>
                                                            @else
                                                                <!-- Upload Icon -->
                                                                <a href="javascript:void(0);" class="text-primary"
                                                                    onclick="addMoreDocuments('dl')"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-original-title="Upload">
                                                                    <i data-feather="upload"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    {{-- Insurance File --}}
                                                    <tr>
                                                        <td>Insurance</td>
                                                        <td>
                                                            @if (!empty($insuranceFile))
                                                                <span style="color: green;">&#x2714;</span> Available
                                                            @else
                                                                <span style="color: red;">&#x2716;</span> Pending Document
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if (!empty($insuranceFile))
                                                                <div class="d-flex align-items-center mb-2">
                                                                    <!-- View Icon -->
                                                                    <a href="{{ asset("storage/upload/document/claim-{$claim->id}/insurance/" . $insuranceFile) }}"
                                                                        target="_blank" class="text-warning"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-original-title="View">
                                                                        <i data-feather="eye"></i>
                                                                    </a>
                                                                    <!-- Update Icon -->
                                                                    <input type="file"
                                                                        class="form-control form-control-sm d-none"
                                                                        id="insurance-update-{{ $insuranceFile }}"
                                                                        data-document-type="insurance"
                                                                        data-file-to-update="{{ $insuranceFile }}"
                                                                        accept=".jpg,.jpeg,.png,.pdf"
                                                                        onchange="updateDocument(this)">
                                                                    <a href="javascript:void(0);"
                                                                        class="text-success ms-2"
                                                                        onclick="document.getElementById('insurance-update-{{ $insuranceFile }}').click()"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-original-title="Edit">
                                                                        <i data-feather="edit"></i>
                                                                    </a>
                                                                    <!-- Delete Icon -->
                                                                    <a href="javascript:void(0);" class="text-danger ms-2"
                                                                        onclick="deleteDocument('insurance', '{{ $insuranceFile }}')"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-original-title="Delete">
                                                                        <i data-feather="trash-2"></i>
                                                                    </a>
                                                                </div>
                                                            @else
                                                                <!-- Upload Icon -->
                                                                <a href="javascript:void(0);" class="text-primary"
                                                                    onclick="addMoreDocuments('insurance')"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-original-title="Upload">
                                                                    <i data-feather="upload"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Claim Form</td>
                                                        <td>
                                                            @if (!empty($claimForm))
                                                                <span style="color: green;">&#x2714;</span> Available
                                                            @else
                                                                <span style="color: red;">&#x2716;</span> Pending Document
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if (!empty($claimForm))
                                                                <div class="d-flex align-items-center mb-2">
                                                                    <!-- View Icon -->
                                                                    <a href="{{ asset("storage/upload/document/claim-{$claim->id}/claimform/" . $claimForm) }}"
                                                                        target="_blank" class="text-warning"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-original-title="View">
                                                                        <i data-feather="eye"></i>
                                                                    </a>
                                                                    <!-- Update Icon -->
                                                                    <input type="file"
                                                                        class="form-control form-control-sm d-none"
                                                                        id="claimform-update-{{ $claimForm }}"
                                                                        data-document-type="claimform"
                                                                        data-file-to-update="{{ $claimForm }}"
                                                                        accept=".jpg,.jpeg,.png,.pdf"
                                                                        onchange="updateDocument(this)">
                                                                    <a href="javascript:void(0);"
                                                                        class="text-success ms-2"
                                                                        onclick="document.getElementById('claimform-update-{{ $claimForm }}').click()"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-original-title="Edit">
                                                                        <i data-feather="edit"></i>
                                                                    </a>
                                                                    <!-- Delete Icon -->
                                                                    <a href="javascript:void(0);" class="text-danger ms-2"
                                                                        onclick="deleteDocument('claimform', '{{ $claimForm }}')"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-original-title="Delete">
                                                                        <i data-feather="trash-2"></i>
                                                                    </a>
                                                                </div>
                                                            @else
                                                                <!-- Upload Icon -->
                                                                <a href="javascript:void(0);" class="text-primary"
                                                                    onclick="addMoreDocuments('claimform')"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-original-title="Upload">
                                                                    <i data-feather="upload"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Claim Intimation</td>
                                                        <td>
                                                            @if (!empty($claimIntimation))
                                                                <span style="color: green;">&#x2714;</span> Available
                                                            @else
                                                                <span style="color: red;">&#x2716;</span> Pending Document
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if (!empty($claimIntimation))
                                                                <div class="d-flex align-items-center mb-2">
                                                                    <!-- View Icon -->
                                                                    <a href="{{ asset("storage/upload/document/claim-{$claim->id}/claimintimation/" . $claimIntimation) }}"
                                                                        target="_blank" class="text-warning"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-original-title="View">
                                                                        <i data-feather="eye"></i>
                                                                    </a>
                                                                    <!-- Update Icon -->
                                                                    <input type="file"
                                                                        class="form-control form-control-sm d-none"
                                                                        id="claimintimation-update-{{ $claimIntimation }}"
                                                                        data-document-type="claimintimation"
                                                                        data-file-to-update="{{ $claimIntimation }}"
                                                                        accept=".jpg,.jpeg,.png,.pdf"
                                                                        onchange="updateDocument(this)">
                                                                    <a href="javascript:void(0);"
                                                                        class="text-success ms-2"
                                                                        onclick="document.getElementById('claimintimation-update-{{ $claimIntimation }}').click()"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-original-title="Edit">
                                                                        <i data-feather="edit"></i>
                                                                    </a>
                                                                    <!-- Delete Icon -->
                                                                    <a href="javascript:void(0);" class="text-danger ms-2"
                                                                        onclick="deleteDocument('claimintimation', '{{ $claimIntimation }}')"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-original-title="Delete">
                                                                        <i data-feather="trash-2"></i>
                                                                    </a>
                                                                </div>
                                                            @else
                                                                <!-- Upload Icon -->
                                                                <a href="javascript:void(0);" class="text-primary"
                                                                    onclick="addMoreDocuments('claimintimation')"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-original-title="Upload">
                                                                    <i data-feather="upload"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Satisfaction Voucher</td>
                                                        <td>
                                                            @if (!empty($satisfactionVoucher))
                                                                <span style="color: green;">&#x2714;</span> Available
                                                            @else
                                                                <span style="color: red;">&#x2716;</span> Pending Document
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if (!empty($satisfactionVoucher))
                                                                <div class="d-flex align-items-center mb-2">
                                                                    <!-- View Icon -->
                                                                    <a href="{{ asset("storage/upload/document/claim-{$claim->id}/satisfactionvoucher/" . $satisfactionVoucher) }}"
                                                                        target="_blank" class="text-warning"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-original-title="View">
                                                                        <i data-feather="eye"></i>
                                                                    </a>

                                                                    <!-- Update Icon -->
                                                                    <input type="file"
                                                                        class="form-control form-control-sm d-none"
                                                                        id="satisfactionvoucher-update-{{ $satisfactionVoucher }}"
                                                                        data-document-type="satisfactionvoucher"
                                                                        data-file-to-update="{{ $satisfactionVoucher }}"
                                                                        accept=".jpg,.jpeg,.png,.pdf"
                                                                        onchange="updateDocument(this)">
                                                                    <a href="javascript:void(0);"
                                                                        class="text-success ms-2"
                                                                        onclick="document.getElementById('satisfactionvoucher-update-{{ $satisfactionVoucher }}').click()"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-original-title="Edit">
                                                                        <i data-feather="edit"></i>
                                                                    </a>

                                                                    <!-- Delete Icon -->
                                                                    <a href="javascript:void(0);" class="text-danger ms-2"
                                                                        onclick="deleteDocument('satisfactionvoucher', '{{ $satisfactionVoucher }}')"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-original-title="Delete">
                                                                        <i data-feather="trash-2"></i>
                                                                    </a>
                                                                </div>
                                                            @else
                                                                <!-- Upload Icon -->
                                                                <a href="javascript:void(0);" class="text-primary"
                                                                    onclick="addMoreDocuments('satisfactionvoucher')"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-original-title="Upload">
                                                                    <i data-feather="upload"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>FIR Copy</td>
                                                        <td>
                                                            @if (!empty($firFile))
                                                                <span style="color: green;">&#x2714;</span> Available
                                                            @else
                                                                <span style="color: red;">&#x2716;</span> No FIR Copy
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if (!empty($firFile))
                                                                <div class="d-flex align-items-center mb-2">
                                                                    <!-- View Icon -->
                                                                    <a href="{{ asset("storage/upload/document/claim-{$claim->id}/fir/" . $firFile) }}"
                                                                        target="_blank" class="text-warning"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-original-title="View">
                                                                        <i data-feather="eye"></i>
                                                                    </a>
                                                                    <!-- Update Icon -->
                                                                    <input type="file"
                                                                        class="form-control form-control-sm d-none"
                                                                        id="fir-update-{{ $firFile }}"
                                                                        data-document-type="fir"
                                                                        data-file-to-update="{{ $firFile }}"
                                                                        accept=".jpg,.jpeg,.png,.pdf"
                                                                        onchange="updateDocument(this)">
                                                                    <a href="javascript:void(0);"
                                                                        class="text-success ms-2"
                                                                        onclick="document.getElementById('fir-update-{{ $firFile }}').click()"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-original-title="Edit">
                                                                        <i data-feather="edit"></i>
                                                                    </a>
                                                                    <!-- Delete Icon -->
                                                                    <a href="javascript:void(0);" class="text-danger ms-2"
                                                                        onclick="deleteDocument('fir', '{{ $firFile }}')"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-original-title="Delete">
                                                                        <i data-feather="trash-2"></i>
                                                                    </a>
                                                                </div>
                                                            @else
                                                                <!-- Upload Icon -->
                                                                <a href="javascript:void(0);" class="text-primary"
                                                                    onclick="addMoreDocuments('fir')"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-original-title="Upload">
                                                                    <i data-feather="upload"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    {{-- Payment Receipt Files --}}
                                                    <tr>
                                                        <td>Payment Receipt</td>
                                                        <td>
                                                            @if (!empty($paymentRecepitFiles))
                                                                <span style="color: green;">&#x2714;</span> Available
                                                                ({{ count($paymentRecepitFiles) }} file(s))
                                                            @else
                                                                <span style="color: red;">&#x2716;</span> Pending Document
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if (!empty($paymentRecepitFiles))
                                                                @foreach ($paymentRecepitFiles as $paymentreceipt)
                                                                    <div class="d-flex align-items-center mb-2">
                                                                        <!-- View Icon -->
                                                                        <a href="{{ asset("storage/upload/document/claim-{$claim->id}/paymentreceipt/" . $paymentreceipt) }}"
                                                                            target="_blank" class="text-warning"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-original-title="View">
                                                                            <i data-feather="eye"></i>
                                                                        </a>
                                                                        <!-- Update Icon -->
                                                                        <input type="file"
                                                                            class="form-control form-control-sm d-none"
                                                                            id="paymentreceipt-update-{{ $paymentreceipt }}"
                                                                            data-document-type="paymentreceipt"
                                                                            data-file-to-update="{{ $paymentreceipt }}"
                                                                            accept=".jpg,.jpeg,.png,.pdf"
                                                                            onchange="updateDocument(this)">
                                                                        <a href="javascript:void(0);"
                                                                            class="text-success ms-2"
                                                                            onclick="document.getElementById('paymentreceipt-update-{{ $paymentreceipt }}').click()"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-original-title="Edit">
                                                                            <i data-feather="edit"></i>
                                                                        </a>
                                                                        <!-- Delete Icon -->
                                                                        <a href="javascript:void(0);"
                                                                            class="text-danger ms-2"
                                                                            onclick="deleteDocument('paymentreceipt', '{{ $paymentreceipt }}')"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-original-title="Delete">
                                                                            <i data-feather="trash-2"></i>
                                                                        </a>
                                                                    </div>
                                                                @endforeach
                                                                <!-- Add More Documents -->
                                                                <a href="javascript:void(0);" class="text-primary mt-2"
                                                                    onclick="addMoreDocuments('paymentreceipt')"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-original-title="Add More">
                                                                    <i class="ti-plus"></i>
                                                                </a>
                                                            @else
                                                                <!-- Upload Icon -->
                                                                <a href="javascript:void(0);" class="text-primary"
                                                                    onclick="addMoreDocuments('paymentreceipt')"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-original-title="Upload">
                                                                    <i data-feather="upload"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Final Bill</td>
                                                        <td>
                                                            @if (!empty($finalBillFiles))
                                                                <span style="color: green;">&#x2714;</span> Available
                                                            @else
                                                                <span style="color: red;">&#x2716;</span> Pending Document
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if (!empty($finalBillFiles))
                                                                <div class="d-flex align-items-center mb-2">
                                                                    <!-- View Icon -->
                                                                    <a href="{{ asset("storage/upload/document/claim-{$claim->id}/finalbill/" . $finalBillFiles) }}"
                                                                        target="_blank" class="text-warning"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-original-title="View">
                                                                        <i data-feather="eye"></i>
                                                                    </a>
                                                                    <!-- Update Icon -->
                                                                    <input type="file"
                                                                        class="form-control form-control-sm d-none"
                                                                        id="finalbill-update-{{ $finalBillFiles }}"
                                                                        data-document-type="finalbill"
                                                                        data-file-to-update="{{ $finalBillFiles }}"
                                                                        accept=".jpg,.jpeg,.png,.pdf"
                                                                        onchange="updateDocument(this)">
                                                                    <a href="javascript:void(0);"
                                                                        class="text-success ms-2"
                                                                        onclick="document.getElementById('finalbill-update-{{ $finalBillFiles }}').click()"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-original-title="Edit">
                                                                        <i data-feather="edit"></i>
                                                                    </a>
                                                                    <!-- Delete Icon -->
                                                                    <a href="javascript:void(0);" class="text-danger ms-2"
                                                                        onclick="deleteDocument('finalbill', '{{ $finalBillFiles }}')"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-original-title="Delete">
                                                                        <i data-feather="trash-2"></i>
                                                                    </a>
                                                                </div>
                                                            @else
                                                                <!-- Upload Icon -->
                                                                <a href="javascript:void(0);" class="text-primary"
                                                                    onclick="addMoreDocuments('finalbill')"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-original-title="Upload">
                                                                    <i data-feather="upload"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- New Tab Content for Damage Photos -->
                                <div class="tab-pane fade" id="damage-details" role="tabpanel"
                                    aria-labelledby="damage-details-tab">
                                    @if ($claim->photo_files)
                                        @php
                                            $totalPrice = 0;
                                            $totalLabour = 0;
                                            $totalPaint = 0;
                                            $totalPaintSum = 0;
                                            $totalTaxAmount = 0;

                                            // Ensure damageResults is not empty
                                            if (!empty($damageResults)) {
                                                foreach ($damageResults as $result) {
                                                    $price = isset($result['price']) && is_numeric($result['price']) ? (float)$result['price'] : 0;
                                                    $labour = isset($result['labour']) && is_numeric($result['labour']) ? (float)$result['labour'] : 0;
                                                    $paint = isset($result['paint']) && is_numeric($result['paint']) ? (float)$result['paint'] : 0;
                                                    $tax = isset($result['tax']) && is_numeric($result['tax']) ? (float)$result['tax'] : 0;

                                                    $totalPrice += $price;
                                                    $totalLabour += $labour;
                                                    $totalPaintSum += $paint;
                                                    $totalTaxAmount += ($price * $tax) / 100;
                                                }

                                                $paintDepreciation = isset($claim->paint_depreciation) && is_numeric($claim->paint_depreciation) ? (float)$claim->paint_depreciation : 0;
                                                $totalPaint = $totalPaintSum - (($totalPaintSum * $paintDepreciation) / 100);
                                            }
                                        @endphp

                                        <div class="col-12 mt-20" id="estimate-damage-pdf-content">
                                            @if ($claim->processed_image_files)
                                                <div class="col-12 mt-20 mb-20">
                                                    <div class="detail-group">
                                                        <h6>{{ __('Analysis  Image') }}</h6>
                                                        <div class="row mt-3">
                                                            <div class="col-12">
                                                                <div class="row">
                                                                    @php
                                                                        $processFiles = json_decode(
                                                                            $claim->processed_image_files,
                                                                            true,
                                                                        );
                                                                    @endphp
                                                                    @foreach ($processFiles as $file)
                                                                        <div class="col-6 col-md-4 col-lg-3 mb-3">
                                                                            <a href="{{ asset('storage/upload/processed_image/' . $file) }}"
                                                                                target="_blank"
                                                                                class="text-decoration-none">
                                                                                <img src="{{ asset('storage/upload/processed_image/' . $file) }}"
                                                                                    class="img-fluid img-thumbnail"
                                                                                    alt="{{ $file }}"
                                                                                    style="max-width: 150px;">
                                                                            </a>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            <h5 class="mb-4">Details Of Damage</h5>
                                            <form id="addPartForm" class="mb-4">
                                                @csrf
                                                <div class="form-group d-flex align-items-center">
                                                    <label for="part" class="mr-2">Select Part:</label>
                                                    <select name="part" id="part"
                                                        class="form-control form-control-sm mr-2" style="width: 400px;">
                                                        @foreach ($parts as $part)
                                                            <option value="{{ $part->id }}"
                                                                data-price="{{ $part->rate }}"
                                                                data-material="{{ $part->material }}">
                                                                {{ $part->part }}
                                                            </option>
                                                        @endforeach
                                                        <option value="other" data-price="0" data-material="">Other
                                                        </option>
                                                    </select>

                                                    <input type="text" id="otherPartInput"
                                                        class="form-control form-control-sm mr-2 d-none"
                                                        placeholder="Enter part name" style="width: 400px;">

                                                    <input type="text" id="otherMaterialInput"
                                                        class="form-control form-control-sm mr-2 d-none"
                                                        placeholder="Enter material" style="width: 180px;">

                                                    <button type="submit" class="btn btn-primary">Add Part</button>
                                                </div>
                                            </form>
                                            <form id="addLabourPart" class="mb-4">
                                                @csrf
                                                <div class="form-group d-flex align-items-center">
                                                    <label for="labourPart" class="mr-2">Select Labour Part:</label>
                                                    <select name="labourpart" id="labourpart"
                                                        class="form-control form-control-sm mr-2" style="width: 400px;">
                                                        <option value="opening&fitting" data-price="0" data-material="">Opening & fitting</option>
                                                        <option value="dentingcharge" data-price="0" data-material="">Denting Charges</option>
                                                        <option value="paintingcharge" data-price="0" data-material="">Painting Charges</option>
                                                        <option value="glassfittingcharge" data-price="0" data-material="">Glass Fitting Charges</option>
                                                        <option value="other" data-price="0" data-material="">other</option>
                                                    </select>

                                                    <input type="text" id="otherLabourPartInput"
                                                        class="form-control form-control-sm mr-2 d-none"
                                                        placeholder="Enter labour part name" style="width: 400px;">
                                                    
                                                    <button type="submit" class="btn btn-primary">Add Labour Part</button>
                                                </div>
                                            </form>

                                            <div class="form-group d-flex align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <label for="vehicleDepreciation" class="mr-2">Select Vehicle Age:</label>
                                                    <select name="vehicleDepreciation" id="vehicleDepreciation"
                                                        class="form-control form-control-sm mr-2" style="width: 260px;">
                                                        @foreach ($vehicleDepreciation as $index => $depreciation)
                                                            <!-- Set the first option as selected by default -->
                                                            <option value="{{ $depreciation->id }}"
                                                                data-age="{{ $depreciation->vehicle_age }}"
                                                                data-depreciation="{{ $depreciation->depreciation_percentage }}"
                                                                @if ($depreciation->id == $vehicleDepreciationData) selected @endif>
                                                                {{ $depreciation->dep_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <label for="depreciationType" class="mr-2">Select Depreciation Type:</label>
                                                    <select name="depreciationType" id="depreciationType" class="form-control form-control-sm depreciationType" style="width: 150px;">
                                                        <option value="normal" {{ $depreciationTypeData == 'normal' ? 'selected' : '' }}>Normal Depreciation</option>
                                                        <option value="nil" {{ $depreciationTypeData == 'nil' ? 'selected' : '' }}>Nil Depreciation</option>
                                                    </select>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <label for="paintDep" class="mr-2">Select Paint Dep:</label>
                                                    <select name="paintDep" id="paintDep" class="form-control form-control-sm paintDep" style="width: 150px;">
                                                        <option value="0" {{ $paintDepreciationData == 0 ? 'selected' : '' }}>Zero Dep - 0%</option>
                                                        <option value="12.5" {{ $paintDepreciationData == 12.5 ? 'selected' : '' }}>Normal - 12.5%</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <strong>Assessment</strong>
                                                <table id="damageTable"
                                                    class="table table-striped">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th>Part</th>
                                                            <th>Material</th>
                                                            <th>Est Cost</th>
                                                            <th>Assess Cost</th>
                                                            <th>Tax</th>
                                                            <th>Tax Amt</th>
                                                            <th>Total Amt</th>
                                                            <th>Rate of Dep.</th>
                                                            <th>Dep.Amt</th>
                                                            <th>Final Amt</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach ($damageTableResult as $index => $result)
                                                        <tr data-index="{{ $index }}">
                                                            <td>{{ $result['partName'] }}</td>
                                                            <td>{{ $result['material'] ?? '' }}</td>
                                                            <td>
                                                                <input type="number" class="form-control form-control-sm estimate-price-input" 
                                                                    value="{{ $result['estimateCost'] ?? 0 }}" />
                                                            </td>
                                                            <td>
                                                                <input type="number" class="form-control form-control-sm price-input"
                                                                    value="{{ $result['assessedCost'] ?? 0 }}" data-index="{{ $index }}">
                                                            </td>
                                                            <td>
                                                                <select class="form-select form-select-sm tax-input w-auto" data-index="{{ $index }}">
                                                                    <option value="0" {{ $result['taxPercentage'] == 0 ? 'selected' : '' }}>0%</option>
                                                                    <option value="5" {{ $result['taxPercentage'] == 5 ? 'selected' : '' }}>5%</option>
                                                                    <option value="18" {{ $result['taxPercentage'] == 18 ? 'selected' : '' }}>18%</option>
                                                                    <option value="28" {{ $result['taxPercentage'] == 28 ? 'selected' : '' }}>28%</option>
                                                                </select>
                                                            </td>
                                                            <td id="taxAssess">₹{{ $result['taxAmount'] ?? 0  }}</td>
                                                            <td id="assessAmount">₹{{ $result['totalAmount'] ?? 0 }}</td>
                                                            <td id="deprateper">{{ $result['depreciationRate'] }}%</td>
                                                            <td id="DepAmount">₹{{ $result['depreciationAmount'] ?? 0 }}</td>
                                                            <td id="finalAssessAmount">₹{{ $result['finalAmount'] ?? 0 }}</td>
                                                            <td>
                                                                <button class="btn btn-danger btn-sm remove-part" data-index="{{ $index }}">
                                                                    <i data-feather="trash-2"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr class="font-weight-bold">
                                                        <td></td>
                                                        <td><strong>Total</strong></td>
                                                        <td><strong id="totalEstimateCost">₹{{ number_format(collect($damageTableResult)
                                                            ->map(function ($item) {
                                                                return (float) $item['estimateCost']; // Convert to float
                                                            })
                                                            ->sum(), 2) }}</strong></td>
                                                        <td><strong id="totalAssessedCost">₹{{ number_format(collect($damageTableResult)->map(function ($item) {
                                                                return (float) $item['assessedCost']; // Convert to float
                                                            })->sum(),2) }}</strong></td>
                                                        <td></td>
                                                        <td><strong id="totalAssesTaxAmount">₹{{ number_format(collect($damageTableResult)
                                                            ->map(function ($item) {
                                                                return (float) $item['taxAmount']; // Convert to float
                                                            })
                                                            ->sum(),2) }}</strong></td>
                                                        <td><strong id="totalAmount">₹{{ number_format(collect($damageTableResult)
                                                            ->map(function ($item) {
                                                                return (float) $item['totalAmount']; // Convert to float
                                                            })
                                                            ->sum(),2) }}</strong></td>
                                                        <td></td>
                                                        <td><strong id="totalDepAmount">₹{{ number_format(collect($damageTableResult)->map(function ($item) {
                                                                return (float) $item['depreciationAmount']; // Convert to float
                                                            })->sum(),2) }}</strong></td>
                                                        <td><strong id="finalAssessTotalAmount">₹{{ number_format(collect($damageTableResult)
                                                            ->map(function ($item) {
                                                                return (float) $item['finalAmount']; // Convert to float
                                                            })
                                                            ->sum(),2) }}</strong></td>
                                                    </tr>
                                                </tfoot>
                                                </table>  
                                                    <strong>Labour Charges</strong>
                                                <table id="labourChargesTable" class="table table-striped ">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th>Desc</th>
                                                            <th>Estimate Cost</th>
                                                            <th>Assessed Cost</th>
                                                            <th>Tax</th>
                                                            <th>Tax Amount</th>
                                                            <th>Total Amount</th>
                                                            <th>Final Amount</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach ($labourTableResult as $index => $result)
                                                        <tr>
                                                            <td><input type="text" class="form-control form-control-sm description-labour-input" value="{{ $result['descriptionLabour'] ?? '' }}"></td>
                                                            <td><input type="number" class="form-control form-control-sm estimate-labour-price-input" value="{{ $result['estimateLabourCost'] ?? 0 }}"></td>
                                                            <td><input type="number" class="form-control form-control-sm assessed-labour-cost-input" value="{{ $result['assessedLabourCost'] ?? 0 }}"></td>
                                                            <td>
                                                                <select class="form-select form-select-sm tax-labour-input w-auto">
                                                                    <option value="0" {{ $result['taxLabourPercentage'] == 0 ? 'selected' : '' }}>0%</option>
                                                                    <option value="18" {{ $result['taxLabourPercentage'] == 18 ? 'selected' : '' }}>18%</option>
                                                                </select>
                                                            </td>
                                                            <td class="taxLabour">₹{{ $result['taxLabourAmount'] ?? 0 }}</td>
                                                            <td class="taxLabourAmount">₹{{ $result['totalLabourAmount'] ?? 0 }}</td>
                                                            <td class="finalLabourAmount">₹{{ $result['finalLabourAmount'] ?? 0 }}</td>
                                                            <td><button class="btn btn-danger btn-sm remove-labour-part" data-index="{{ $index }}"><i data-feather="trash-2"></i></button></td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr class="font-weight-bold">
                                                            <td><strong>Total</strong></td>
                                                            <td><strong id="totalEstimateLabourCost">₹{{ number_format(collect($labourTableResult)
                                                            ->map(function ($item) {
                                                                return (float) $item['estimateLabourCost']; // Convert to float
                                                            })
                                                            ->sum(),2) }}</strong></td>
                                                            <td><strong id="totalAssessLabourCost">₹{{ number_format(collect($labourTableResult)
                                                            ->map(function ($item) {
                                                                return (float) $item['assessedLabourCost']; // Convert to float
                                                            })
                                                            ->sum(),2) }}</strong></td>
                                                            <td><strong></strong></td>
                                                            <td><strong id="totalLabourTaxAmount">₹{{ number_format(collect($labourTableResult)
                                                            ->map(function ($item) {
                                                                return (float) $item['taxLabourAmount']; // Convert to float
                                                            })
                                                            ->sum(),2) }}</strong></td>
                                                            <td><strong id="totalLabourAmount">₹{{ number_format(collect($labourTableResult)
                                                            ->map(function ($item) {
                                                                return (float) $item['totalLabourAmount']; // Convert to float
                                                            })
                                                            ->sum(),2) }}</strong></td>
                                                            <td><strong id="totalFinalLabourAmount">₹{{ number_format(collect($labourTableResult)
                                                            ->map(function ($item) {
                                                                return (float) $item['finalLabourAmount']; // Convert to float
                                                            })
                                                            ->sum(),2) }}</strong></td>
                                                        </tr>
                                                    </tfoot>

                                                </table>  

                                                <strong>Summary of Assessment</strong>
                                                    <table class="table table-striped ">
                                                        <thead>
                                                            <tr>
                                                                <th><strong>Estimate :-</strong></th>
                                                                <th><strong>Assessed For :-</strong></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>Total Labour Charges: <strong id="totalEstimateLabour1">₹{{ $summaryTableResult['totalEstimateLabour'] ?? 0}}</strong></td>
                                                                <td>Total Labour Tax Charges: <strong id="totalAssessedLabourTax">₹{{ $summaryTableResult['totalFinalLabourAmount'] ?? 0 }}</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Total Cost of Parts: <strong id="totalEstimateParts">₹{{ $summaryTableResult['totalEstimateParts'] ?? 0}}</strong></td>
                                                                <td>Total Spare Parts: <strong id="totalAssessedParts">₹{{ $summaryTableResult['totalAssessedParts'] ?? 0}}</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                <td>Less Excess (-): <input type="number" id="lessExcess" placeholder="Enter amount" value="{{ $summaryTableResult['lessExcess']  ?? 0}}" /></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Total:</strong> <strong id="totalEstimate">₹{{ $summaryTableResult['totalEstimate'] ?? 0, 2 }}</strong></td>
                                                                <td><strong>Total:</strong> <strong id="totalAssessed">₹{{ $summaryTableResult['totalAssessed'] ?? 0, 2 }}</strong></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                        </div>
                                    @endif
                                    <br>
                                    <br>
                                    <br>
                                    <!-- Download button: -->
                                    <div class="form-group mt-2 d-flex justify-content-end">
                                        <button
                                            type="button"
                                            class="btn btn-success"
                                            data-target="estimate-damage-pdf-content"
                                            data-filename="estimate-damage-details.pdf"
                                            onclick="downloadSectionAsPDF(this)">
                                            Download Estimate Damage Details PDF
                                        </button>
                                    </div>
                                </div>
                                

                                {{-- Final Report Tab --}}
                                <div class="tab-pane fade" id="final-report" role="tabpanel"
                                    aria-labelledby="final-report-tab">
                                    <h5>{{ __('Final Report') }}</h5>
                                    <iframe src="{{ route('claim.viewReport', ['id' => $claim->id]) }}" width="100%"
                                        height="600px"></iframe>
                                </div>
                                <!-- New Tab Content for Damage Photos -->
                                <div class="tab-pane fade" id="damage-photos" role="tabpanel"
                                    aria-labelledby="damage-photos-tab">
                                    <div class="mb-3">
                                        <a href="{{ route('claims.photos.pdf', $claim->id) }}" class="btn btn-primary" target="_blank"> Download Photos as PDF</a>
                                    </div>
                                    @if ($claim->video_file)
                                        <div class="col-12 mt-20">
                                            <div class="detail-group">
                                                <h6>{{ __('Uploaded Video') }}</h6>
                                                <video width="320" height="240" controls>
                                                    <source
                                                        src="{{ asset("storage/upload/document/claim-{$claim->id}/video/" . $claim->video_file) }}"
                                                        type="video/mp4">
                                                </video>
                                            </div>
                                    @endif
                                    @if ($claim->processed_image_files)
                                        <div class="col-12 mt-20">
                                            <div class="detail-group">
                                                <h6>{{ __('Analysis  Image') }}</h6>
                                                <div class="row mt-3">
                                                    <div class="col-12">
                                                        <div class="row">
                                                            @php
                                                                $processFiles = json_decode(
                                                                    $claim->processed_image_files,
                                                                    true,
                                                                );
                                                            @endphp
                                                            @foreach ($processFiles as $file)
                                                                <div class="col-6 col-md-4 col-lg-3 mb-3">
                                                                    <a href="{{ asset('storage/upload/processed_image/' . $file) }}"
                                                                        target="_blank" class="text-decoration-none">
                                                                        <img src="{{ asset('storage/upload/processed_image/' . $file) }}"
                                                                            class="img-fluid" alt="{{ $file }}">
                                                                    </a>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($claim->photo_files)
                                        <div class="col-12 mt-20">
                                            <div class="detail-group">
                                                <h5>{{ __('Vechicle Damage Images with Geotag and Capture Time') }}</h5>
                                                <div class="row mt-3">
                                                    <div class="col-12">
                                                        <div class="row">
                                                            @php
                                                                $photoFiles = json_decode($claim->photo_files, true);
                                                                $claimHash = md5($claim->id);
                                                                $folderCode = getFolderCode('vehicle');
                                                            @endphp

                                                            @foreach ($photoFiles as $photo)
                                                                @php
                                                                    $filename = $photo['filename'];
                                                                    $imageUrl = route('secure.image', [$claimHash, 'PHX', $folderCode , $filename]);
                                                                @endphp

                                                                <div class="col-12 col-md-6 col-lg-4 mb-4">
                                                                    <div class="card">
                                                                        <a href="{{ $imageUrl }}" target="_blank" class="text-decoration-none">
                                                                            <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $filename }}">
                                                                        </a>
                                                                        <div class="card-body">
                                                                            <p class="card-text">
                                                                                <strong>Capture Time:</strong><br>
                                                                                {{ \Carbon\Carbon::parse($photo['captureTime'])->format('Y-m-d H:i:s') }}
                                                                            </p>

                                                                            @if (isset($photo['geotag']) && $photo['geotag'] !== null)
                                                                                <p class="card-text">
                                                                                    <strong>Location:</strong><br>
                                                                                    Lat: {{ $photo['geotag']['latitude'] }}<br>
                                                                                    Long: {{ $photo['geotag']['longitude'] }}
                                                                                </p>
                                                                                <a href="https://www.google.com/maps/search/?api=1&query={{ $photo['geotag']['latitude'] }},{{ $photo['geotag']['longitude'] }}"
                                                                                target="_blank" class="btn btn-sm btn-primary">
                                                                                    View on Map
                                                                                </a>
                                                                            @else
                                                                                <p class="card-text text-muted">No location data available</p>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="detail-group">
                                                <h5>{{ __('Under Repair Images with Geotag and Capture Time') }}</h5>
                                                <div class="row mt-3">
                                                    <div class="col-12">
                                                        <div class="row">
                                                            @php
                                                                $underPhotoFiles = json_decode($claim->under_repair_photo_files, true);
                                                                $claimHash = md5($claim->id);
                                                                $folderCode = getFolderCode('under_repair');
                                                            @endphp

                                                            @foreach ($underPhotoFiles as $photo)
                                                                @php
                                                                    $filename = $photo['filename'];
                                                                    $imageUrl = route('secure.image', [$claimHash, 'PHX', $folderCode , $filename]);
                                                                @endphp

                                                                <div class="col-12 col-md-6 col-lg-4 mb-4">
                                                                    <div class="card">
                                                                        <a href="{{ $imageUrl }}" target="_blank" class="text-decoration-none">
                                                                            <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $filename }}">
                                                                        </a>
                                                                        <div class="card-body">
                                                                            <p class="card-text">
                                                                                <strong>Capture Time:</strong><br>
                                                                                {{ \Carbon\Carbon::parse($photo['captureTime'])->format('Y-m-d H:i:s') }}
                                                                            </p>

                                                                            @if (isset($photo['geotag']) && $photo['geotag'] !== null)
                                                                                <p class="card-text">
                                                                                    <strong>Location:</strong><br>
                                                                                    Lat: {{ $photo['geotag']['latitude'] }}<br>
                                                                                    Long: {{ $photo['geotag']['longitude'] }}
                                                                                </p>
                                                                                <a href="https://www.google.com/maps/search/?api=1&query={{ $photo['geotag']['latitude'] }},{{ $photo['geotag']['longitude'] }}"
                                                                                target="_blank" class="btn btn-sm btn-primary">
                                                                                    View on Map
                                                                                </a>
                                                                            @else
                                                                                <p class="card-text text-muted">No location data available</p>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="detail-group">
                                                <h5>{{ __('Final Images with Geotag and Capture Time') }}</h5>
                                                <div class="row mt-3">
                                                    <div class="col-12">
                                                        <div class="row">
                                                            @php
                                                                $finalPhotoFiles = json_decode($claim->final_photo_files, true);
                                                                $claimHash = md5($claim->id);
                                                                $folderCode = getFolderCode('final');
                                                            @endphp

                                                            @foreach ($finalPhotoFiles as $photo)
                                                                @php
                                                                    $filename = $photo['filename'];
                                                                    $imageUrl = route('secure.image', [$claimHash, 'PHX', $folderCode , $filename]);
                                                                @endphp

                                                                <div class="col-12 col-md-6 col-lg-4 mb-4">
                                                                    <div class="card">
                                                                        <a href="{{ $imageUrl }}" target="_blank" class="text-decoration-none">
                                                                            <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $filename }}">
                                                                        </a>
                                                                        <div class="card-body">
                                                                            <p class="card-text">
                                                                                <strong>Capture Time:</strong><br>
                                                                                {{ \Carbon\Carbon::parse($photo['captureTime'])->format('Y-m-d H:i:s') }}
                                                                            </p>

                                                                            @if (isset($photo['geotag']) && $photo['geotag'] !== null)
                                                                                <p class="card-text">
                                                                                    <strong>Location:</strong><br>
                                                                                    Lat: {{ $photo['geotag']['latitude'] }}<br>
                                                                                    Long: {{ $photo['geotag']['longitude'] }}
                                                                                </p>
                                                                                <a href="https://www.google.com/maps/search/?api=1&query={{ $photo['geotag']['latitude'] }},{{ $photo['geotag']['longitude'] }}"
                                                                                target="_blank" class="btn btn-sm btn-primary">
                                                                                    View on Map
                                                                                </a>
                                                                            @else
                                                                                <p class="card-text text-muted">No location data available</p>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            {{-- End Tabs --}}
                        </div>
                    </div>
                </div>
            </div>
            @if (count($claim->documents) > 0)
                <div class="col-md-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4> {{ __('Document Detail') }} </h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Type') }}</th>
                                            <th>{{ __('Document') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            @if (Gate::check('delete document'))
                                                <th class="action">{{ __('Action') }}</th>
                                            @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($claim->documents as $document)
                                        <tr>
                                            <td>{{ !empty($document->types) ? $document->types->title : '-' }}</td>
                                            <td><a href="{{ asset('/storage/upload/document/' . $document->document) }}"
                                                    target="_blank">{{ !empty($document->types) ? $document->types->title : '-' }}</a>
                                            </td>
                                            <td>
                                                {{ \App\Models\Insurance::$docStatus[$document->status] }}
                                            </td>
                                            @if (Gate::check('delete document'))
                                                <td class="action">
                                                    <div class="cart-action">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['claim.document.destroy', [$claim->id, $document->id]]]) !!}
                                                        <a class=" text-danger confirm_dialog"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-original-title="{{ __('Detete') }}"
                                                            href="#">
                                                            <i data-feather="trash-2"></i></a>

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
</div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
{{-- PDF Library and Script --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
function downloadSectionAsPDF(button) {
  const targetId = button.getAttribute('data-target');
  let filename = button.getAttribute('data-filename') || 'document.pdf';

  // Build "Fee Bill Details" from filename:
  const title = filename
    .replace(/\.pdf$/i, '')
    .split(/[-_]/)
    .map(w => w[0].toUpperCase() + w.slice(1))
    .join(' ');

  const orig = document.getElementById(targetId);
  if (!orig) return;

  // Clone to leave the live DOM alone
  const clone = orig.cloneNode(true);

  // 1) Create and style heading  
  const heading = document.createElement('h2');
  heading.innerText = title;
  heading.classList.add('no-break');
  heading.style.cssText = `
    text-align: center;
    font-family: Arial, sans-serif;
    font-size: 20px;
    margin: 0 0 10px 0;           /* no top-margin, small bottom margin */
    page-break-after: avoid;       /* <=== prevent break right after */
  `;
  clone.insertBefore(heading, clone.firstChild);

  // 2) Hide the submit button in the clone
  const submitWrapper = clone.querySelector('.form-group.mt-4');
  if (submitWrapper) submitWrapper.style.display = 'none';

  // 3) Inject PDF-specific CSS including our no-break utility
  const style = document.createElement('style');
  style.textContent = `
    @page {
      size: A4;
      margin: 10mm;
    }
    /* Utility to forbid breaks inside */
    .no-break { page-break-inside: avoid; }
    body, #${targetId} {
      font-family: Arial, sans-serif;
      font-size: 12px;
      color: #333;
      width: 100%;
      box-sizing: border-box;
      margin: 0; padding: 0;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 8px;
    }
    th, td {
      border: 1px solid #ccc;
      padding: 6px;
    }
    .form-control {
      width: 100%;
      padding: 5px;
      box-sizing: border-box;
      border: 1px solid #ccc;
    }
    .row {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      margin-bottom: 8px;
    }
    .col-md-6 { flex: 1 1 48%; }
    .col-md-4 { flex: 1 1 30%; }
    .col-md-12 { flex: 1 1 100%; }
  `;
  clone.insertBefore(style, clone.firstChild);

  // 4) Generate the PDF
  html2pdf().set({
    margin:       10,
    filename:     filename,
    image:        { type: 'jpeg', quality: 1 },
    html2canvas:  { scale: 2, scrollY: 0 },
    jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' },
    pagebreak:    { mode: ['avoid-all','css'] },
  }).from(clone).save()
    .catch(err => console.error(err));
}
</script>
<script>

    document.addEventListener("DOMContentLoaded", function () {
        let professionalFeeInput = document.getElementById("professional_fee");
        let conveyanceFinalInput = document.getElementById("conveyance_final");
        let totalAmountInput = document.getElementById("total_amount");

        function updateTotal() {
            let professionalFee = parseFloat(professionalFeeInput.value) || 0;
            let conveyanceFinal = parseFloat(conveyanceFinalInput.value) || 0;
            totalAmountInput.value = (professionalFee + conveyanceFinal).toFixed(2); // Keep two decimal places
        }

        // Add event listeners to both inputs
        professionalFeeInput.addEventListener("input", updateTotal);
        conveyanceFinalInput.addEventListener("input", updateTotal);
    });


    document.addEventListener("DOMContentLoaded", function () {
        // Attach event listeners
        document.getElementById('total_amount').addEventListener('input', calculateGST);
        document.querySelectorAll('input[name="gst_type"]').forEach((radio) => {
            radio.addEventListener('change', calculateGST);
        });

        function calculateGST() {
            let totalAmount = parseFloat(document.getElementById('total_amount')?.value) || 0;
            let gstType = document.querySelector('input[name="gst_type"]:checked')?.value;

            let cgst = 0, sgst = 0, igst = 0, netTotal = totalAmount;
            let igstContainer = document.getElementById('igst_container');

            if (gstType === 'cgst_sgst') {
                cgst = parseFloat((totalAmount * 0.09).toFixed(2));
                sgst = parseFloat((totalAmount * 0.09).toFixed(2));
                netTotal += cgst + sgst;

                if (igstContainer) {
                    igstContainer.style.display = "none";  // Hide IGST field
                }
            } else {
                igst = parseFloat((totalAmount * 0.18).toFixed(2));
                netTotal += igst;

                if (igstContainer) {
                    igstContainer.style.display = "block"; // Show IGST field
                }
            }

            document.getElementById('cgst').value = cgst;
            document.getElementById('sgst').value = sgst;
            document.getElementById('igst').value = igst;
            document.getElementById('net_total').value = netTotal.toFixed(2);
        }


        // Initialize values on page load
        calculateGST();
    });




</script>

<script>
   
    $(document).ready(function() {
        $('#part').on('change', function() {
            var selectedOption = $(this).val();
            if (selectedOption === 'other') {
                $('#otherPartInput').removeClass('d-none'); // Show the part name input
                $('#otherMaterialInput').removeClass('d-none'); // Show the material input
            } else {
                $('#otherPartInput').addClass('d-none'); // Hide the part name input
                $('#otherMaterialInput').addClass('d-none'); // Hide the material input
            }
        });

        $('#labourpart').on('change', function() {
            var selectedOption = $(this).val();
            if (selectedOption === 'other') {
                $('#otherLabourPartInput').removeClass('d-none'); // Show the part name input
            } else {
                $('#otherLabourPartInput').addClass('d-none'); // Hide the part name input
            }
        });

        // Event listener for removing a part from the table
        $(document).on('click', '.remove-part', function(e) {
            e.preventDefault();
            var rowIndex = $(this).data('index');

            // AJAX call to remove the part from the backend
            $.ajax({
                url: '{{ route('claim.removePart', $claim->id) }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    index: rowIndex
                },
                success: function(response) {
                    if (response.success) {
                        removeRowFromTable(rowIndex); // Remove row from the table
                        updateTotals(response.totalPrice, response.totalLabour, response
                            .totalPaint, response.totalTax, response.estimatePrice);
                        if ($('#damageTable tbody tr').length === 0) {
                            $('#damageTable').addClass('d-none'); // Hide table if no rows left
                        } // Show the table
                        // location.reload();
                    } else {
                        alert('Error removing part');
                    }
                },
                error: function() {
                    alert('Error removing part');
                }
            });
        });

        // Function to remove the row from the table
        function removeRowFromTable(index) {
            $('#damageTable tbody tr[data-index="' + index + '"]').remove();
        }

        function removeRowFromLabourTable(index) {
            $('#labourChargesTable tbody tr[data-index="' + index + '"]').remove();
        }



        // Event listener for removing a part from the damage table
        $(document).on('click', '.remove-labour-part', function(e) {
            e.preventDefault();
            var rowIndex = $(this).data('index'); // Get the index of the row to remove

            // AJAX call to remove the part from the backend
            $.ajax({
                url: '{{ route('claim.removeLabourPart', $claim->id) }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    index: rowIndex
                },
                success: function(response) {
                    if (response.success) {
                        removeRowFromLabourTable(rowIndex); // Remove row from the table
                        updateTotals(response.totalPrice, response.totalLabour, response.totalPaint, response.totalTax, response.estimatePrice);

                        // Check if there are no rows left, and hide the table if empty
                        if ($('#labourChargesTable tbody tr').length === 0) {
                            $('#labourChargesTable').addClass('d-none');
                        }
                    } else {
                        alert('Error removing part');
                    }
                },
                error: function() {
                    alert('Error removing part');
                }
            });
        });

        
    });

    function updatingDataInDatabase(){
         // Collect data from all tables
        var allData = collectAllTableData(); // This is the function you would use to gather data from all three tables.
        console.log(allData);

        var totalEstimateLabourCost  = $('#totalEstimateLabourCost').text();
        $('#totalEstimateLabour1').text(totalEstimateLabourCost);

        var totalEstimateCost  = $('#totalEstimateCost').text();
        $('#totalEstimateParts').text(totalEstimateCost);

        var totalFinalLabourAmount  = $('#totalFinalLabourAmount').text();
        $('#totalAssessedLabourTax').text(totalFinalLabourAmount);

        var finalAssessTotalAmount  = $('#finalAssessTotalAmount').text();
        $('#totalAssessedParts').text(finalAssessTotalAmount);

        var totalEstimate  = $('#totalEstimate').text();
        $('#totalEstimate').text(totalEstimate);

        // var finalLabourAmount1  = document.querySelector('.finalLabourAmount').textContent.trim();
        // console.log(finalLabourAmount1);
        // $('#finalLabourAmount').text(finalLabourAmount1);

        // AJAX request to send the data
        $.ajax({
            url: '{{ route('claim.updateAllData', $claim->id) }}', // Replace with your backend route
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                all_data: allData // Send all the table data as a JSON string
            },
            success: function(response) {
                if (response.success) {
                    // location.reload(); // Or any other action you want
                } else {
                    alert('Error updating the data');
                }
            },
            error: function() {
                alert('Error updating the data');
            }
        });
    }

    $('#damageTable, #labourChargesTable, #summaryTable, #lessExcess, #vehicleDepreciation').on('change', function() {
        updatingDataInDatabase();       
    });

    // Function to collect data from Damage, Labour, and Summary tables
    function collectAllTableData() {
        var damageData = collectDamageTableData();
        var labourData = collectLabourTableData();
        var summaryData = collectSummaryTableData();

        var vehicleDepreciation =  document.getElementById("vehicleDepreciation").value;
        var paintDepreciation =  document.getElementById("paintDep").value;
        var depreciationType =  document.getElementById("depreciationType").value;
        // console.log(vehicleDepreciation);
        // Combine all data into one object
        var allData = {
            damageTableData: damageData,
            labourTableData: labourData,
            summaryTableData: summaryData,
            vehicleDepreciation: vehicleDepreciation,
            paintDepreciation: paintDepreciation,
            depreciationType: depreciationType,
        };

        // Return the JSON string for easy storage
        return JSON.stringify(allData);
    }

    // Collect data from Damage Table
    function collectDamageTableData() {
        var damageData = [];
        $('#damageTable tbody tr').each(function() {
            var partName = $(this).find('td').eq(0).text().trim();
            var material = $(this).find('td').eq(1).text().trim();
            var estimateCost = $(this).find('.estimate-price-input').val() || 0;
            var assessedCost = $(this).find('.price-input').val() || 0;
            var taxPercentage = $(this).find('.tax-input').val() || 0;
            var taxAmount = $(this).find('#taxAssess').text().replace("₹", "").trim() || 0;
            var totalAmount = $(this).find('#assessAmount').text().replace("₹", "").trim() || 0;
            var depreciationRate = $(this).find('td').eq(7).text().replace('%', '').trim() || 0;
            var depreciationAmount = $(this).find('#DepAmount').text().replace("₹", "").trim() || 0;
            var finalAmount = $(this).find('#finalAssessAmount').text().replace("₹", "").trim() || 0;

            damageData.push({
                partName: partName,
                material: material,
                estimateCost: estimateCost,
                assessedCost: assessedCost,
                taxPercentage: taxPercentage,
                taxAmount: taxAmount,
                totalAmount: totalAmount,
                depreciationRate: depreciationRate,
                depreciationAmount: depreciationAmount,
                finalAmount: finalAmount
            });
        });
        return damageData;
    }

    // Collect data from Labour Table
    function collectLabourTableData() {
        var labourData = [];
        
        $('#labourChargesTable tbody tr').each(function() {
            var descriptionLabour = $(this).find('td').eq(0).find('input').val().trim();
            var estimateLabourCost = $(this).find('.estimate-labour-price-input').val() || 0;
            var assessedLabourCost = $(this).find('.assessed-labour-cost-input').val() || 0;
            var taxLabourPercentage = $(this).find('.tax-labour-input').val() || 0;
            var taxLabourAmount = $(this).find('.taxLabour').text().replace("₹", "").trim() || 0;
            var totalLabourAmount = $(this).find('.taxLabourAmount').text().replace("₹", "").trim() || 0;
            var finalLabourAmount = $(this).find('.finalLabourAmount').text().replace("₹", "").trim() || 0;
            // Store each row's data in an object
            labourData.push({
                descriptionLabour: descriptionLabour,
                estimateLabourCost: estimateLabourCost,
                assessedLabourCost: assessedLabourCost,
                taxLabourPercentage: taxLabourPercentage,
                taxLabourAmount: taxLabourAmount,
                totalLabourAmount: totalLabourAmount,
                finalLabourAmount: finalLabourAmount
            });
        });

        return labourData;
    }

    // Collect data from Summary Table
    function collectSummaryTableData() {
        var summaryData = {
            totalEstimateLabour: $('#totalEstimateLabourCost').text().replace("₹", ""),
            totalFinalLabourAmount: $('#totalFinalLabourAmount').text().replace("₹", ""),
            totalEstimateParts: $('#totalEstimateCost').text().replace("₹", ""),
            totalFinalPartsAmount: $('#finalAssesAmount').text().replace("₹", ""),
            totalAssessedParts: $('#finalAssessTotalAmount').text().replace("₹", ""),
            lessExcess: $('#lessExcess').val(),
            totalEstimate: (parseFloat($('#totalEstimate').text().replace("₹", "")).toFixed(2)),
            totalAssessed: (parseFloat($('#totalAssessed').text().replace("₹", "")).toFixed(2))
        };
        
        return summaryData;
    }


    // Function to update total prices in the footer
    function updateTotals(totalPrice, totalLabour, totalPaint, totalTax, estimatePrice) {
        $('#totalPrice').text('₹' + totalPrice);
        $('#totalLabour').text('₹' + totalLabour);
        $('#totalPaint').text('₹' + totalPaint);
        $('#totalTax').text('₹' + totalTax);
        $('#estimatePrice').text('₹' + estimatePrice);
    }

    $('#addPartForm').on('submit', function(e) {
            e.preventDefault();

            var selectedPart = $('#part option:selected');
            var partName = selectedPart.text().replace(/\s+/g, ' ').trim(); // Default part name
            var material = selectedPart.data('material'); // Default material

            // If the "Other" option is selected, get the custom part name and material from the input fields
            if (selectedPart.val() === 'other') {
            }

            // Create partData to send with the AJAX request
            var partData = {
                class: partName,
                price: parseFloat(selectedPart.data('price')),
                score: 0.98,
                material: material,
                tax: 0,
                labour: 0,
                paint: 0,
                severity: 'manually'
            };

            // AJAX call to add part
            $.ajax({
                url: '{{ route('claim.addPart', $claim->id) }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    part: partData
                },
                success: function(response) {
                    if (response.success) {
                        addRowToTable(); // Add new row to table
                        updateTotals(response.totalPrice, response.totalLabour, response
                            .totalPaint, response.totalTax);
                        $('#damageTable').removeClass('d-none'); // Show the table
                        // location.reload();
                    } else {
                        alert('Error adding part');
                    }
                },
                error: function() {
                    alert('Error adding part');
                }
            });
        });

        function addRowToTable() {
            var rowIndex = $('#damageTable tbody tr').length; // Get current number of rows
            var selectedPart = $('#part option:selected');

            var selectedPart = $('#part option:selected');
            var partName = selectedPart.text().replace(/\s+/g, ' ').trim(); // Default part name
            var material = selectedPart.data('material'); // Default material

            if (selectedPart.val() === 'other') {
                partName = $('#otherPartInput').val().trim(); // Get the custom part name
                material = $('#otherMaterialInput').val().trim(); // Get the custom material
            }
            var newRow = `
                <tr data-index="${rowIndex}">
                    <td>${partName}</td>
                    <td>${material}</td> 
                    <td>
                        <input type="number" class="form-control form-control-sm estimate-price-input" 
                            value="" placeholder="Enter amount" data-index="">
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm price-input" />
                    </td>
                    <td>
                        <select class="form-select form-select-sm tax-input w-auto" style="min-width: 70px;">
                            <option value="0">0%</option>
                            <option value="5">5%</option>
                                <option value="18">18%</option>
                                <option value="28">28%</option>
                        </select>
                    </td>
                    <td id="taxAssess"></td>
                    <td id="assessAmount"></td>
                    <td id="deprateper"></td>
                    <td id="DepAmount"></td>
                    <td id="finalAssessAmount"></td>
                    <td><button class="btn btn-danger btn-sm remove-part" data-index="${rowIndex}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                    </button></td>
                </tr>`;

            $('#damageTable tbody').append(newRow);
            
        }




    $(document).ready(function() {
        // Handle form submission
        $('#addLabourPart').on('submit', function(event) {
            event.preventDefault(); // Prevent the form from submitting
            // Get the selected labour part
            var selectedLabourPart = $('#labourpart option:selected').text();

            // Create a new row
            var newRow = `
                <tr>
                    <td><input type="text" class="form-control form-control-sm description-labour-input" placeholder="Description" value="${selectedLabourPart}"></td>
                    <td><input type="number" class="form-control form-control-sm estimate-labour-price-input" placeholder="0" /></td>
                    <td><input type="number" class="form-control form-control-sm assessed-labour-cost-input" placeholder="0"></td>
                    <td>
                        <select class="form-select form-select-sm tax-labour-input w-auto" style="min-width: 70px;">
                            <option value="0">0%</option>
                            <option value="18">18%</option>
                        </select>
                    </td>
                    <td class="taxLabour">₹0.00</td>
                    <td class="taxLabourAmount">₹0.00</td>
                    <td class="finalLabourAmount">₹0.00</td>
                    <td><button class="btn btn-danger btn-sm remove-labour-part">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                    </button></td>
                </tr>
            `;

            // Append the new row to the table
            $('#labourChargesTable tbody').append(newRow);

            // Show the table if it was hidden
            $('#labourChargesTable').removeClass('d-none');
        });

        // Handle row removal
        $('#labourChargesTable').on('click', '.remove-labour-part', function() {
            $(this).closest('tr').remove();

            // Hide the table if there are no rows left
            if ($('#labourChargesTable tbody tr').length === 0) {
                $('#labourChargesTable').addClass('d-none');
            }
        });
    });

    // Function to update the total depreciation amount in the footer
    function updateTotalDepAmount() {
        let totalDepAmount = 0;

        // Loop through each row and sum up the depreciation amounts
        const tableRows = document.querySelectorAll('#damageTable tbody tr');
        tableRows.forEach(function(row) {
            const depAmountCell = row.cells[8]; // Depreciation Amount is in 9th column (index 8)
            const depAmount = parseFloat(depAmountCell.textContent.replace('₹', '').replace(',', '').trim());


            const price = row.cells[6];  // Get TOTAL aasess amount cell
            const ttlamount = price.textContent.replace('₹', '').replace(',', '').trim()
            const updatedAmnt = ttlamount - depAmount;
            const finalAmountCell = row.cells[9];                                                              
            finalAmountCell.textContent = `₹${updatedAmnt.toFixed(2)}`;
            if (!isNaN(depAmount)) {
                totalDepAmount += depAmount;
            }
        });
        
        // Update the totalDepAmount in the footer
        document.getElementById('totalDepAmount').textContent = '₹' + totalDepAmount.toFixed(2);
    }

    
    // Initialize the default depreciation percentage from the selected option
    const defaultSelectedOption = document.getElementById('vehicleDepreciation').options[document.getElementById('vehicleDepreciation').selectedIndex];
    const defaultDepreciationPercentage = parseFloat(defaultSelectedOption.getAttribute('data-depreciation'));

    // Function to apply depreciation percentage to rows
    /*function applyDepreciationToRows(depreciationPercentage) {
        const tableRows = document.querySelectorAll('#damageTable tbody tr');

        tableRows.forEach(function(row) {
            // Get the material (2nd column) in each row (index 1)
            const materialCell = row.cells[1];
            const material = materialCell.textContent.trim().toLowerCase();

            const assessAmountCell = row.querySelector("#assessAmount");  // Get depreciation amount cell
            // Initialize the depreciation percentage variable
            var depPercentage = depreciationPercentage;

            // Apply fixed depreciation percentages based on material
            if (material === 'glass') {
                depPercentage = 0; // Glass has 0% depreciation
            } else if (material === 'plastic') {
                depPercentage = 50; // Plastic has 50% depreciation
            } else if (material === 'fibre') {
                depPercentage = 50; // Fibre has 50% depreciation
            }else if (material === 'rubber') {
                depPercentage = 50; // Rubber has 50% depreciation
            }

            // Apply depreciation calculation
            if (material === 'metal' || material === 'plastic' || material === 'fibre' || material === 'glass' || material === 'rubber') {
                const assessAmount = assessAmountCell.textContent.replace("₹", "").replace(",", "").trim() || 0;
                
                if (!isNaN(assessAmount) && !isNaN(depPercentage) && depPercentage!=0) {
                    // console.log(depPercentage,price);
                    // Calculate depreciation amount: Price * Depreciation Percentage / 100
                    const depreciationAmount = assessAmount - ((assessAmount * depPercentage) / 100);
                    // Update the depreciation amount cell (index 8)
                    const depAmountCell = row.cells[8];
                    
                    depAmountCell.textContent = '₹' + depreciationAmount.toFixed(2);

                    // Update the Depreciation % column (index 7)
                    const depPercentageCell = row.cells[7];
                    depPercentageCell.textContent = depPercentage.toFixed(2) + '%';
                }else if(depPercentage==0){
                    const depPercentageCell = row.cells[7];
                    depPercentageCell.textContent = '0.00%';

                    const depAmountCell = row.cells[8];
                    depAmountCell.textContent = '₹0.00';
                }
            }
        });
    }*/

    function applyDepreciationToRows(depreciationPercentage) {
        const tableRows = document.querySelectorAll('#damageTable tbody tr');

        tableRows.forEach(function(row) {
            const materialCell = row.cells[1];
            const material = materialCell.textContent.trim().toLowerCase();
            const assessAmountCell = row.querySelector("#assessAmount");

            let depPercentage = depreciationPercentage;

            if (material === 'glass') {
                depPercentage = 0;
            } else if (material === 'plastic' || material === 'fibre' || material === 'rubber') {
                depPercentage = 50;
            }

            if (material === 'metal' || material === 'plastic' || material === 'fibre' || material === 'glass' || material === 'rubber') {
                const assessAmount = parseFloat(assessAmountCell.textContent.replace("₹", "").replace(",", "").trim()) || 0;

                const depAmount = (assessAmount * depPercentage) / 100;
                const finalAmount = assessAmount - depAmount;

                // Update the depreciation percentage cell (index 7)
                row.cells[7].textContent = depPercentage.toFixed(2) + '%';

                // Update the depreciation amount (index 8)
                row.cells[8].textContent = '₹' + depAmount.toFixed(2);

                // Update the final amount (index 9)
                row.cells[9].textContent = '₹' + finalAmount.toFixed(2);
            }
        });
    }
    
    // Apply default depreciation when the page loads
    applyDepreciationToRows(defaultDepreciationPercentage);

    // When the dropdown selection changes, apply the new depreciation percentage
    document.getElementById('vehicleDepreciation').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const depreciationPercentage = parseFloat(selectedOption.getAttribute('data-depreciation'));

        // Apply depreciation to all rows
        applyDepreciationToRows(depreciationPercentage);

        // Update the total depreciation amount after the changes
        updateTotalDepAmount();
        // updatingDataInDatabase();


        let table = document.getElementById("damageTable"); // Get the table
            if (!table) return;

            let rows = table.querySelectorAll("tbody tr"); // Get all rows

            rows.forEach((row) => {
                calculateRow(row);
            });
            
            // After processing all rows, update the footer and summary table
            calculateFooter("damageTable", {
                estimateCost: "#totalEstimateCost",
                assessedCost: "#totalAssessedCost",
                taxAmount: "#totalAssesTaxAmount",
                finalAmount: "#finalAssessTotalAmount",
                totalAmount: "#totalAmount",
                totalDepAmount: "#totalDepAmount",
            });
            updateSummaryTable();
            updatingDataInDatabase();
    });


    //customized by tanuja 28th january 2025
    // document.addEventListener("DOMContentLoaded", function () {
        // Function to calculate a row's values
        function calculateRow(row) {
            const assessedCostInput = row.querySelector(".price-input");
            const taxSelect = row.querySelector(".tax-input");
            const depreciationCell = row.querySelector("#DepAmount");  // Get depreciation amount cell
            const taxAmountCell = row.querySelector("#taxAssess");
            const totalAmountCell = row.querySelector("#assessAmount");
            const finalAmountCell = row.querySelector("#finalAssessAmount");
            const depreRatePercentage = row.querySelector("#deprateper");

            var depPercentage = parseFloat(depreRatePercentage.textContent.replace("%", "").trim());

            // Get input values
            const assessedCost = parseFloat(assessedCostInput.value) || 0;
            const taxRate = parseFloat(taxSelect?.value) || 0;

            // Calculate tax amount and total amount (assessed cost + tax amount)
            const taxAmount = (assessedCost * taxRate) / 100;
            const totalAmount = assessedCost + taxAmount;

            const materialCell = row.cells[1];
            const material = materialCell.textContent.trim().toLowerCase();

            const depreciationType = document.getElementById("depreciationType").value;
            // Apply fixed depreciation percentages based on material
            if (material === 'glass') {
                depPercentage = 0; // Glass has 0% depreciation
            } else if (material === 'plastic') {
                depPercentage = 50; // Plastic has 50% depreciation
            } else if (material === 'fibre') {
                depPercentage = 50; // Fibre has 50% depreciation
            }else if (material === 'rubber') {
                depPercentage = 50; // Rubber has 50% depreciation
            }
            
            // Get Depreciation Amount from the table cell 
            var depreciationAmount = parseFloat(depreciationCell.textContent.replace("₹", "").trim()) || 0;

            const depreciationAmountPrice = parseFloat(row.querySelector("#assessAmount")?.textContent.replace("₹", "").trim() || 0);
            // console.log("DepPrice",depreciationAmountPrice);

            if (!isNaN(depreciationAmountPrice) && !isNaN(depPercentage) && depPercentage!=0) {
                // Calculate depreciation amount: Price * Depreciation Percentage / 100
                const depreciationAmount1 = depreciationAmountPrice - ((depreciationAmountPrice * depPercentage) / 100);

                // Update the depreciation amount cell (index 8)
                const depAmountCell = row.cells[8];

                depAmountCell.textContent = '₹' + depreciationAmount1.toFixed(2);

                // Update the Depreciation % column (index 7)
                const depPercentageCell = row.cells[7];
                depPercentageCell.textContent = depPercentage.toFixed(2) + '%';

            }else if(depPercentage==0){
                const depPercentageCell = row.cells[7];
                depPercentageCell.textContent = '0.00%';

                const depAmountCell = row.cells[8];
                depAmountCell.textContent = '₹0.00';
            }

            // Calculate final amount after subtracting depreciation amount
            var finalAmount = totalAmount - depreciationAmount;

            if(depreciationType=='nil'){
                const depPercentageNil = row.cells[7];
                depPercentageNil.textContent = '0.00%';

                const depAmountCell = row.cells[8];  
                depAmountCell.textContent = '₹0.00';

                finalAmount = totalAmount;
            }else if(depreciationType=='normal'){
                const selectedOption = document.getElementById("vehicleDepreciation");
                let selectedOption1 = selectedOption.options[selectedOption.selectedIndex];
                const depreciationPercentage = parseFloat(selectedOption1.getAttribute('data-depreciation'));

                // console.log(depreciationPercentage);
                // Apply depreciation to all rows
                applyDepreciationToRows(depreciationPercentage);

                // Update the total depreciation amount after the changes
                updateTotalDepAmount();
                updatingDataInDatabase();
                
            }

            // Update DOM with calculated values
            taxAmountCell.textContent = `₹${taxAmount.toFixed(2)}`;
            totalAmountCell.textContent = `₹${totalAmount.toFixed(2)}`;
            finalAmountCell.textContent = `₹${finalAmount.toFixed(2)}`;
        }

        // Function to calculate total footer values
        function calculateFooter(tableId, totalFields) {
            const rows = document.querySelectorAll(`#${tableId} tbody tr`);
            let totalEstimateCost = 0;
            let totalAssessedCost = 0;
            let totalTaxAmount = 0;
            let totalFinalAmount = 0;
            let totalAmountVal = 0;
            let totalDepAmountVal = 0; // Variable to store total depreciation amount

            rows.forEach((row) => {
                const estimateCost = parseFloat(row.querySelector(".estimate-price-input")?.value || 0);
                const assessedCost = parseFloat(row.querySelector(".price-input")?.value || 0);
                const taxAmount = parseFloat(row.querySelector("#taxAssess")?.textContent.replace("₹", "").trim() || 0);
                const finalAmount = parseFloat(row.querySelector("#finalAssessAmount")?.textContent.replace("₹", "").trim() || 0);
                const depreciationAmount = parseFloat(row.querySelector("#DepAmount")?.textContent.replace("₹", "").trim() || 0);

                const rowTotalAmount = assessedCost + taxAmount;

                totalEstimateCost += estimateCost;
                totalAssessedCost += assessedCost;
                totalTaxAmount += taxAmount;
                totalFinalAmount += finalAmount; 
                totalAmountVal += rowTotalAmount;
                totalDepAmountVal += depreciationAmount; // Sum all depreciation amounts
            });

            // Update footer totals
            document.querySelector(totalFields.estimateCost).textContent = `₹${totalEstimateCost.toFixed(2)}`;
            document.querySelector(totalFields.assessedCost).textContent = `₹${totalAssessedCost.toFixed(2)}`;
            document.querySelector(totalFields.taxAmount).textContent = `₹${totalTaxAmount.toFixed(2)}`;
            document.querySelector(totalFields.finalAmount).textContent = `₹${totalFinalAmount.toFixed(2)}`; // Fix for finalAssessTotalAmount
            document.querySelector(totalFields.totalAmount).textContent = `₹${totalAmountVal.toFixed(2)}`;
            document.querySelector(totalFields.totalDepAmount).textContent = `₹${totalDepAmountVal.toFixed(2)}`; // Update total depreciation

        }


        // Add event listeners for real-time updates
        document.querySelectorAll(".table").forEach((table) => {
            document.addEventListener("input", (e) => {
                if (e.target.closest("#damageTable")) { // Ensure the event is inside the table
                    const row = e.target.closest("tr");
                    calculateRow(row);
                    calculateFooter("damageTable", {
                        estimateCost: "#totalEstimateCost",
                        assessedCost: "#totalAssessedCost",
                        taxAmount: "#totalAssesTaxAmount",
                        finalAmount: "#finalAssessTotalAmount",
                        totalAmount: "#totalAmount",
                        totalDepAmount: "#totalDepAmount", // Add this for total depreciation
                    });
                    updateSummaryTable();
                }
            });

            // Add event listener for removing rows
            table.addEventListener("click", (e) => {
                if (e.target.classList.contains("remove-part")) {
                    e.target.closest("tr").remove();
                    calculateFooter("damageTable", {
                        estimateCost: "#totalEstimateCost",
                        assessedCost: "#totalAssessedCost",
                        taxAmount: "#totalAssesTaxAmount",
                        finalAmount: "#finalAssessTotalAmount",
                        totalAmount: "#totalAmount",
                        totalDepAmount: "#totalDepAmount",
                    });
                    updateSummaryTable();
                }
            });
        });

        function DepreciationTypeFun(){
            let table = document.getElementById("damageTable"); // Get the table
            if (!table) return;

            let rows = table.querySelectorAll("tbody tr"); // Get all rows

            rows.forEach((row) => {
                calculateRow(row);
            });
            
            // After processing all rows, update the footer and summary table
            calculateFooter("damageTable", {
                estimateCost: "#totalEstimateCost",
                assessedCost: "#totalAssessedCost",
                taxAmount: "#totalAssesTaxAmount",
                finalAmount: "#finalAssessTotalAmount",
                totalAmount: "#totalAmount",
                totalDepAmount: "#totalDepAmount",
            });
            updateSummaryTable();
            updatingDataInDatabase();
        }

        document.getElementById("depreciationType").addEventListener("change", function () {
            // alert();
            DepreciationTypeFun();
        });


    // });

    function updateLabourRowValues(row) {
        // console.log(row);
        const assessedLabourCost = parseFloat(row.querySelector(".assessed-labour-cost-input")?.value || 0);
        const taxPercentage = parseFloat(row.querySelector(".tax-labour-input")?.value || 0);
        let taxCell = row.querySelector(".taxLabour"); // Find the cell with the class 'taxLabour'

        const taxAmount = (assessedLabourCost.toFixed(2) * taxPercentage.toFixed(2)) / 100;

        // console.log(taxAmount);
        if (taxCell) {
            taxCell.textContent = `₹${taxAmount.toFixed(2)}`;
        }

        const taxLabourAmountCell = row.querySelector(".taxLabourAmount");
        const finalLabourTaxAmount = (assessedLabourCost + taxAmount).toFixed(2);
        
        if (taxLabourAmountCell) {
            taxLabourAmountCell.textContent = `₹${finalLabourTaxAmount}`;
        }

        // Ensure `.description-labour-input` exists before accessing `.value`
        let labourDescriptionElement = row.querySelector('.description-labour-input');

        let labourDescription = labourDescriptionElement ? labourDescriptionElement.value.trim() : '';

        // console.log(labourDescription);
        var finalAmount = finalLabourTaxAmount; // Default final amount
        
        // Get the selected paint depreciation value
        const paintDepreciation = parseFloat(document.getElementById("paintDep").value);
        const depreciationType = document.getElementById("depreciationType").value;

        // If it's Painting Charges, apply 12.5% discount
       // Apply depreciation only if the description is 'painting charge'
       if (labourDescription == 'Painting Charges') {
            let taxLabourAmount = parseFloat(row.querySelector('.taxLabourAmount')?.textContent.replace(/[^0-9.]/g, '')) || 0;

            // Apply 12.5% depreciation if selected, else 0% (no depreciation)
            if (paintDepreciation === 12.5) {
                let discountedAmount = taxLabourAmount - (taxLabourAmount * 12.5 / 100);
                finalAmount = discountedAmount.toFixed(2);
            } else if (paintDepreciation === 0) {
                finalAmount = taxLabourAmount.toFixed(2); // No change in amount, no depreciation
            }
        }

       if (depreciationType) {
            let taxLabourAmount = parseFloat(row.querySelector('.taxLabourAmount')?.textContent.replace(/[^0-9.]/g, '')) || 0;

            if (depreciationType === 'nil') {
                let discountedAmount = taxLabourAmount - (taxLabourAmount * 0 / 100);
                finalAmount = discountedAmount.toFixed(2);
            }
        }

        // ✅ Update Final Amount
        let finalLabourAmountCell = row.querySelector(".finalLabourAmount");
        
        if (finalLabourAmountCell) {
            finalLabourAmountCell.textContent = `₹${finalAmount}`;
        }
        // console.log(parseFloat(finalAmount));
        return {
            assessedLabourCost,
            taxAmount,
            finalLabourAmount: parseFloat(finalAmount),
        };
    }



    function calculateLabourFooter(tableId, totalFields) {
        const rows = document.querySelectorAll(`#${tableId} tbody tr`);
        let totalEstimateLabourCost = 0;
        let totalAssessLabourCost = 0;
        let totalLabourTaxAmount = 0;
        let totalFinalLabourAmount = 0;
        let totalTaxLabour = 0;

        rows.forEach((row) => {
            const estimateLabourCost = parseFloat(row.querySelector(".estimate-labour-price-input")?.value || 0);
            const assessedLabourCost = parseFloat(row.querySelector(".assessed-labour-cost-input")?.value || 0);
            const taxLabour = parseFloat(row.querySelector(".taxLabour")?.textContent.replace("₹", "") || 0);
            const taxLabourAmount = parseFloat(row.querySelector(".taxLabourAmount")?.textContent.replace("₹", "") || 0);

            const totalFinalLabourAmountVal = parseFloat(row.querySelector(".finalLabourAmount").textContent.replace("₹", "") || 0);
            //const finalLabourAmount = assessedLabourCost + taxLabour;
            
            totalEstimateLabourCost += estimateLabourCost;
            totalAssessLabourCost += assessedLabourCost;
            totalTaxLabour += taxLabour;
            totalLabourTaxAmount += taxLabourAmount;
            totalFinalLabourAmount += totalFinalLabourAmountVal;
        });
        
        // Update footer totals for Labour Charges
        if (document.querySelector(totalFields.estimateLabourCost)) {
            document.querySelector(totalFields.estimateLabourCost).textContent = `₹${totalEstimateLabourCost.toFixed(2)}`;
        }
        if (document.querySelector(totalFields.assessLabourCost)) {
            document.querySelector(totalFields.assessLabourCost).textContent = `₹${totalAssessLabourCost.toFixed(2)}`;
        }
        if (document.querySelector(totalFields.taxLabour)) {
            document.querySelector(totalFields.taxLabour).textContent = `₹${totalTaxLabour.toFixed(2)}`;
        }
        if (document.querySelector(totalFields.taxLabourAmount)) {
            document.querySelector(totalFields.taxLabourAmount).textContent = `₹${totalLabourTaxAmount.toFixed(2)}`;
        }
        if (document.querySelector(totalFields.finalLabourAmount)) {
            document.querySelector(totalFields.finalLabourAmount).textContent = `₹${totalFinalLabourAmount.toFixed(2)}`;
        }

        // return totalFinalLabourAmount; // Return final labour amount total
    }

    // Function to update the Summary Table
    function updateSummaryTable() {
        // Get Labour Totals
        const totalEstimateLabour = parseFloat(document.querySelector("#totalEstimateLabourCost")?.textContent.replace("₹", "") || 0);

        const totalFinalLabourAmount = parseFloat(document.querySelector("#totalFinalLabourAmount")?.textContent.replace("₹", "").replace(/,/g, "") || "0");
        // const totalFinalLabourAmount = parseFloat(totalFinalLabourAmount1);
        // alert(totalFinalLabourAmount);

        const finalAssessTotalAmount = parseFloat(document.querySelector("#finalAssessTotalAmount")?.textContent.replace("₹", "").replace(/,/g, "") || "0");
        // const finalAssessTotalAmount = parseFloat(finalAssessTotalAmount1);

        // Get Parts Totals
        const totalEstimateParts = document.querySelector("#totalEstimateCost")?.textContent.replace("₹", "").replace(/,/g, "") || "0";
        const totalEstimateNumber = parseFloat(totalEstimateParts);

        // Get User Input Values (Supplementary & Deductions)
        const lessExcess = parseFloat(document.querySelector("#lessExcess")?.value || 0);

        // **Calculate Totals Correctly**
        const totalEstimate = totalEstimateLabour + totalEstimateNumber; 
        const totalAssessed = (totalFinalLabourAmount + finalAssessTotalAmount) - lessExcess;
        // **Update Summary Table**
        document.querySelector("#totalEstimateLabour1").textContent = `₹${totalEstimateLabour}`;
        document.querySelector("#totalAssessedLabourTax").textContent = `₹${totalFinalLabourAmount}`; // FIXED HERE ✅
       
        // alert(finalAssessTotalAmount1);
        document.querySelector("#totalEstimateParts").textContent = `₹${totalEstimateParts}`;
        document.querySelector("#totalAssessedParts").textContent = `₹${finalAssessTotalAmount}`; 

        document.querySelector("#lessExcess").textContent = `₹${lessExcess}`;

        document.querySelector("#totalEstimate").textContent = `₹${totalEstimate.toFixed(2)}`;
        document.querySelector("#totalAssessed").textContent = `₹${totalAssessed.toFixed(2)}`;
    }

    // Update tax amount dynamically when the tax select input changes
    document.addEventListener("change", (e) => {
        if (e.target.classList.contains("tax-labour-input")) {
            const row = e.target.closest("tr");
            updateLabourRowValues(row);
            calculateLabourFooter("labourChargesTable", {
                estimateLabourCost: "#totalEstimateLabourCost",
                assessLabourCost: "#totalAssessLabourCost",
                taxLabour: "#totalLabourTaxAmount",
                taxLabourAmount: "#totalLabourAmount",
                finalLabourAmount: "#totalFinalLabourAmount",
            });
            updateSummaryTable();
        }
    });

    document.getElementById("paintDep").addEventListener("change", function () {
        let table = document.getElementById("labourChargesTable"); // Get the table
        if (!table) return;

        let rows = table.querySelectorAll("tr"); // Get all rows

        rows.forEach((row) => {
            updateLabourRowValues(row);
        });
        
        // After processing all rows, update the footer and summary table
        calculateLabourFooter("labourChargesTable", {
            estimateLabourCost: "#totalEstimateLabourCost",
            assessLabourCost: "#totalAssessLabourCost",
            taxLabour: "#totalLabourTaxAmount",
            taxLabourAmount: "#totalLabourAmount",
            finalLabourAmount: "#totalFinalLabourAmount",
        });
        updateSummaryTable();
        updatingDataInDatabase();
    });

    document.getElementById("depreciationType").addEventListener("change", function () {
        let table = document.getElementById("labourChargesTable"); // Get the table
        if (!table) return;

        let rows = table.querySelectorAll("tr"); // Get all rows

        rows.forEach((row) => {
            updateLabourRowValues(row);
        });
        
        // After processing all rows, update the footer and summary table
        calculateLabourFooter("labourChargesTable", {
            estimateLabourCost: "#totalEstimateLabourCost",
            assessLabourCost: "#totalAssessLabourCost",
            taxLabour: "#totalLabourTaxAmount",
            taxLabourAmount: "#totalLabourAmount",
            finalLabourAmount: "#totalFinalLabourAmount",
        });
        updateSummaryTable();
        updatingDataInDatabase();
    });

    // Add event listeners for real-time updates
    document.querySelectorAll(".table").forEach((table) => {
        document.addEventListener("input", (e) => {
            if (e.target.closest("#labourChargesTable")) { // Ensure the event is inside the table
                const row = e.target.closest("tr");
                updateLabourRowValues(row);
                calculateLabourFooter("labourChargesTable", {
                    estimateLabourCost: "#totalEstimateLabourCost",
                    assessLabourCost: "#totalAssessLabourCost",
                    taxLabour: "#totalLabourTaxAmount",
                    taxLabourAmount: "#totalLabourAmount",
                    finalLabourAmount: "#totalFinalLabourAmount",
                });
                updateSummaryTable();
            }
        });

    });

    document.addEventListener("DOMContentLoaded", () => {

        let depreciationType = document.getElementById("depreciationType");
        if (depreciationType) {
            depreciationType.dispatchEvent(new Event("change"));
            DepreciationTypeFun();
        }

        // Recalculate the footer totals after initializing the rows
        calculateLabourFooter("labourChargesTable", {
            estimateLabourCost: "#totalEstimateLabourCost",
            assessLabourCost: "#totalAssessLabourCost",
            taxLabour: "#totalLabourTaxAmount",
            taxLabourAmount: "#totalLabourAmount",
            finalLabourAmount: "#totalFinalLabourAmount",
        });

        updateSummaryTable();
    });

    // **Trigger Calculation on Input Changes**
    document.querySelectorAll(".table, .summary-input").forEach((element) => {
        element.addEventListener("input", updateSummaryTable);
    });

    // ** Run Calculation on Page Load**
    // document.addEventListener("DOMContentLoaded", function() {
    //     setTimeout(function(){
    //         updateSummaryTable();
    //     },1000);
        
    // });

</script>
<script>
    // Function to get the CSRF token from the meta tag
    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    }

    function updateDocument(fileInput) {
        const documentType = fileInput.dataset.documentType;
        const fileToUpdate = fileInput.dataset.fileToUpdate;
        const file = fileInput.files[0];

        if (!file) return;

        const formData = new FormData();
        formData.append('claim_id', '{{ $claim->id }}');
        formData.append('document_type', documentType);
        formData.append('file_to_update', fileToUpdate);
        formData.append('new_file', file);

        $.ajax({
            url: '{{ route('claims.update-document') }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': getCsrfToken() // Add CSRF token to the request headers
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Document Updated',
                    text: response.message
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Update Failed',
                    text: xhr.responseJSON.error || 'An error occurred'
                });
            }
        });
    }

    function deleteDocument(documentType, fileToDelete) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to delete this document?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('claim_id', '{{ $claim->id }}');
                formData.append('document_type', documentType);
                formData.append('file_to_delete', fileToDelete);

                $.ajax({
                    url: '{{ route('claims.delete-document') }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': getCsrfToken() // Add CSRF token to the request headers
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Document Deleted',
                            text: response.message
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Delete Failed',
                            text: xhr.responseJSON.error || 'An error occurred'
                        });
                    }
                });
            }
        });
    }

    function addMoreDocuments(documentType) {
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = '.jpg,.jpeg,.png';
        input.onchange = function() {
            const file = this.files[0];

            if (!file) return;

            const formData = new FormData();
            formData.append('claim_id', '{{ $claim->id }}');
            formData.append('document_type', documentType);
            formData.append('new_file', file);

            $.ajax({
                url: '{{ route('claims.add-document') }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken() // Add CSRF token to the request headers
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Document Added',
                        text: response.message
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Upload Failed',
                        text: xhr.responseJSON.error || 'An error occurred'
                    });
                }
            });
        };

        input.click();
    }
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const claimTabs = document.getElementById("claimTabs");
        const tabs = claimTabs.querySelectorAll("a[data-bs-toggle='tab']");

        // Function to set active tab based on the ID from localStorage
        const setActiveTab = (tabId) => {
            tabs.forEach((tab) => {
                const contentId = tab.getAttribute("href").substring(
                    1); // Get the ID of the content
                const tabContent = document.getElementById(contentId);

                if (tab.id === tabId) {
                    tab.classList.add("active");
                    tabContent.classList.add("active", "show");
                } else {
                    tab.classList.remove("active");
                    tabContent.classList.remove("active", "show");
                }
            });
        };

        // Retrieve the last active tab from localStorage
        const lastActiveTab = localStorage.getItem("lastActiveTab");
        if (lastActiveTab) {
            setActiveTab(lastActiveTab);
        }

        // Add event listeners to store the clicked tab in localStorage
        tabs.forEach((tab) => {
            tab.addEventListener("click", function() {
                localStorage.setItem("lastActiveTab", tab.id);
            });
        });
    });
</script>
@endsection
