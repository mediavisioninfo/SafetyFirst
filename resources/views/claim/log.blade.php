<div class="modal-body">
    <div class="row">
      @if($logs->count() > 0)
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
                                                <a class="btn btn-primary btn-sm ml-20 customModal" href="#"
                                                    data-url="{{ route('claim.changes', $log->id) }}" data-size="lg"
                                                    data-title="{{ __('View Changes') }}">
                                                    View
                                                </a>
                                            @endif
                                        </td>
                                        <td>{{ $log->ip_address }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
      @else
      <p>No log Found!</p>
      @endif
    </div>
</div>
