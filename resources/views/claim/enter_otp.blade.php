@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Verify OTP</h2>
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('claim.verifyOtp') }}">
        @csrf
        <input type="hidden" name="claim_id" value="{{ $claimId }}">
        <div class="form-group">
            <label>Enter OTP sent to your mobile</label>
            <input type="text" name="otp" class="form-control" maxlength="6" required>
        </div>
        <button type="submit" class="btn btn-primary">Verify OTP</button>
    </form>
</div>
@endsection
