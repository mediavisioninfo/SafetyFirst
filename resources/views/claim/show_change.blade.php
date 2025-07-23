<div class="modal-body">
  <div class="row">
    @if ($log->old_values)
                                                                    <h6>{{ __('Old Values:') }}</h6>
                                                                    <pre>{{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}</pre>
                                                                @endif
                                                                @if ($log->new_values)
                                                                    <h6>{{ __('New Values:') }}</h6>
                                                                    <pre>{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}</pre>
                                                                @endif
  </div>
</div>