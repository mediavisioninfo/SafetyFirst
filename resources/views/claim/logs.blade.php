<!-- resources/views/claim/logs.blade.php -->
@extends('layouts.app')
@section('page-title')
    {{ __('Claim Logs') }}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}">
                <h1>{{ __('Dashboard') }}</h1>
            </a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{ __('Claim Logs') }}</a>
        </li>
    </ul>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display dataTable cell-border datatbl-advance">
                            <thead>
                                <tr>
                                    <th>{{ __('Date & Time') }}</th>
                                    <th>{{ __('Claim ID') }}</th>
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('Action') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    <th>{{ __('Changes') }}</th>
                                    <th>{{ __('IP Address') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($logs as $log)
                                    <tr>
                                        <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td>
                                            <a href="{{ route('claim.show', \Crypt::encrypt($log->claim_id)) }}">
                                                {{ $log->claim->claim_id }}
                                            </a>
                                        </td>
                                        <td>{{ $log->user ? $log->user->name : 'System' }}</td>
                                        <td>{{ $log->action }}</td>
                                        <td>{{ $log->description }}</td>
                                        <td>
                                            @if ($log->old_values || $log->new_values)
                                                <a class="btn btn-primary btn-sm ml-20 customModal" href="#" data-url="{{ route('claim.changes', $log->id) }}" data-size="lg"
                                                  data-title="{{ __('View Changes') }}">
                                                  <i data-feather="eye"></i>
                                               </a>
                                            @endif
                                        </td>
                                        <td>{{ $log->ip_address }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
