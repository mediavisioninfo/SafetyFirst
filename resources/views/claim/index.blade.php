@extends('layouts.app')
@section('page-title')
{{ __('Claim') }}
@endsection
@section('breadcrumb')
<ul class="breadcrumb mb-0">
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">
            <h1>{{ __('Dashboard') }}</h1>
        </a>
    </li>
    <li class="breadcrumb-item active">
        <a href="#">{{ __('Claim') }}</a>
    </li>
</ul>
@endsection
@php
use App\Models\User;
$users = User::where('type', 'Operator')
             ->orWhere('type', 'workshop')
             ->orderBy('name')
             ->get();
@endphp
@section('card-action-btn')
@if (Gate::check('create claim'))
<!-- Dropdown to select user and button to assign claim -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="d-flex gap-3">
        <form method="GET" action="{{ route('claim.index') }}" id="vehicleFilterForm">
            <select name="vehicle_type" onchange="document.getElementById('vehicleFilterForm').submit()"
                class="form-select">
                <option value="" disable>-- Select Vehicle Type --</option>
                <option value="">Show All Vehicle</option>
                <option value="2" {{ request('vehicle_type') == '2' ? 'selected' : '' }}>2-Wheeler</option>
                <option value="3" {{ request('vehicle_type') == '3' ? 'selected' : '' }}>3-Wheeler</option>
                <option value="4" {{ request('vehicle_type') == '4' ? 'selected' : '' }}>4-Wheeler</option>
                <option value="more" {{ request('vehicle_type') == 'more' ? 'selected' : '' }}>More than 4-Wheeler
                </option>
            </select>
        </form>

        @if(auth()->user()->type === 'manager' || auth()->user()->type === 'super admin')
        <form id="assignForm" method="POST" action="{{ route('claim.assign') }}">
            @csrf
            <div class="input-group">
                <select name="user_id" id="user_id" class="form-select">
                    <option value="">{{ __('Select User to Assign') }}</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-sm btn-primary ms-2" onclick="assignClaims()">
                    <i data-feather="user" style="width: 16px; height: 16px;"></i>
                    {{ __('Assign Claim') }}
                </button>
            </div>
        </form>
        @endif
    </div>

    <a class="btn btn-primary btn-sm ml-20 customModal" href="#" data-size="lg" data-url="{{ route('claim.create') }}"
        data-title="{{ __('Create Claim') }}">
        <i class="ti-plus mr-5"></i>{{ __('Create Claim') }}
    </a>
</div>
@endif
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('claim.index') }}" class="d-flex mb-6">
                    <select name="date_filter" class="form-select me-2">
                        <option value="">{{ __('Select Date Filter') }}</option>
                        <option value="today">{{ __('Today') }}</option>
                        <option value="this_week">{{ __('This Week') }}</option>
                        <option value="this_month">{{ __('This Month') }}</option>
                        <option value="custom">{{ __('Custom Range') }}</option>
                    </select>

                    <!-- Custom date range inputs (shown only if 'custom' is selected) -->
                    <input type="date" name="start_date" class="form-control me-2" placeholder="{{ __('Start Date') }}">
                    <input type="date" name="end_date" class="form-control me-2" placeholder="{{ __('End Date') }}">

                    <button type="submit" class="btn btn-primary">{{ __('Filter') }}</button>
                </form>
                <table class="display dataTable cell-border datatbl-advance">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAllClaims" onclick="toggleAllClaims()"></th>
                            <th>{{ __('Claim No') }}</th>
                            <th>{{ __('Insured Name & Vehicle Number') }}</th>
                            <th>{{ __('Claim Date') }}</th>
                            <th>{{ __('Mobile No') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Assigned To') }}</th>
                            <th>{{ __('Total Amount') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($claims as $claim)
                        <tr>
                            <td><input type="checkbox" name="claim_ids[]" value="{{ $claim->id }}"
                                    class="claim-checkbox"></td>
                            <td>{{ $claim->claim_id }} </td>
                            <td>
                                {{ !empty($insuranceDetail[$claim->id]) ? $insuranceDetail[$claim->id]->insured_name . ' (' . $insuranceDetail[$claim->id]->vehicle . ')' : '-' }}
                            </td>
                            <td>{{ dateFormat($claim->date) }} </td>
                            <td>{{ !empty($claim->mobile) ? $claim->mobile : '-' }} </td>
                            {{-- <td>
                                    <a href="{{route('insurance.show',\Illuminate\Support\Facades\Crypt::encrypt($claim->insurances->id))}}">{{ !empty($claim->insurances)?insurancePrefix().$claim->insurances->insurance_id:'-' }}
                            </a>
                            </td> --}}
                            <td>
                                @switch($claim->status)
                                @case('claim_intimated')
                                <span
                                    class="badge badge-primary">{{ \App\Models\Claim::$status[$claim->status] }}</span>
                                @break

                                @case('link_shared')
                                <span class="badge badge-info">{{ \App\Models\Claim::$status[$claim->status] }}</span>
                                @break

                                @case('documents_pending')
                                <span
                                    class="badge badge-warning">{{ \App\Models\Claim::$status[$claim->status] }}</span>
                                @break

                                @case('documents_submitted')
                                <span class="badge badge-info">{{ \App\Models\Claim::$status[$claim->status] }}</span>
                                @break

                                @case('approved')
                                <span
                                    class="badge badge-success">{{ \App\Models\Claim::$status[$claim->status] }}</span>
                                @break

                                @case('under_review')
                                <span
                                    class="badge badge-warning">{{ \App\Models\Claim::$status[$claim->status] }}</span>
                                @break

                                @case('rejected')
                                <span class="badge badge-danger">{{ \App\Models\Claim::$status[$claim->status] }}</span>
                                @break

                                @default
                                <span class="badge badge-info">{{ \App\Models\Claim::$status[$claim->status] }}</span>
                                @endswitch
                            </td>
                            <td>
                                <span class="badge {{ $claim->user ? 'badge-success' : 'badge-secondary' }}">
                                    {{ $claim->user ? $claim->user->name : 'Unassigned' }}
                                </span>
                            </td>
                            <td>{{ !empty($feesBillData[$claim->id]['total_amount']) ? $feesBillData[$claim->id]['total_amount'] : '0.00' }}
                            </td>
                            <td>
                                <div class="cart-action">
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['claim.destroy', $claim->id]]) !!}
                                    @if (Gate::check('show claim'))
                                    <a class="text-warning" data-bs-toggle="tooltip"
                                        data-bs-original-title="{{ __('Details') }}"
                                        href="{{ route('claim.show', \Illuminate\Support\Facades\Crypt::encrypt($claim->id)) }}">
                                        <i data-feather="eye"></i></a>
                                    @endcan
                                    @if (Gate::check('edit claim'))
                                    <a class="text-success customModal" data-bs-toggle="tooltip" data-size="lg"
                                        data-bs-original-title="{{ __('Edit') }}" href="#"
                                        data-url="{{ route('claim.edit', $claim->id) }}"
                                        data-title="{{ __('Edit Claim') }}"> <i data-feather="edit"></i></a>
                                    @endcan
                                    @if (Gate::check('delete claim'))
                                    <a class=" text-danger confirm_dialog" data-bs-toggle="tooltip"
                                        data-bs-original-title="{{ __('Detete') }}" href="#"> <i
                                            data-feather="trash-2"></i></a>
                                    @endcan
                                    <!-- Copy Upload Link Icon -->
                                    <a class="text-primary" data-bs-toggle="tooltip"
                                        data-bs-original-title="{{ __('Copy Upload Link') }}" href="javascript:void(0);"
                                        onclick="copyLink('{{ route('claim.upload', ['id' => Crypt::encrypt($claim->id)]) }}')">
                                        <i data-feather="link"></i>
                                    </a>
                                    @if (Gate::check('manage claim'))
                                    <a class="text-info customModal" data-bs-toggle="tooltip" data-size="lg"
                                        data-bs-original-title="{{ __('View Logs') }}" href="#"
                                        data-url="{{ route('claim.log', $claim->id) }}"
                                        data-title="{{ __('View Logs') }}">
                                        <i data-feather="clock"></i></a>
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Your function stays the same
        window.assignClaims = function() {
            let selectedUserId = document.getElementById('user_id').value;
            if (!selectedUserId) {
                alert('{{ __("Please select a user to assign.") }}');
                return;
            }

            let selectedClaims = [];
            document.querySelectorAll('.claim-checkbox:checked').forEach(checkbox => {
                selectedClaims.push(checkbox.value);
            });

            if (selectedClaims.length === 0) {
                alert('{{ __("Please select at least one claim to assign.") }}');
                return;
            }

            let form = document.getElementById('assignForm');
            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'claim_ids';
            input.value = JSON.stringify(selectedClaims);
            form.appendChild(input);
            form.submit();
        };
    });

    function toggleAllClaims() {
        let selectAll = document.getElementById('selectAllClaims').checked;
        document.querySelectorAll('.claim-checkbox').forEach(checkbox => {
            checkbox.checked = selectAll;
        });
    }
    function copyLink(link) {
        navigator.clipboard.writeText(link).then(function() {
            alert('Link copied to clipboard!');
        }, function(err) {
            console.error('Could not copy link: ', err);
        });
    }
</script>

@endsection