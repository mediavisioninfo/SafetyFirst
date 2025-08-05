<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-store" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <title>Verify OTP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS (Optional, if needed for styling) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 60px;
        }
        .otp-container {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="otp-container">
        <h2 class="mb-4 text-center">Verify OTP</h2>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('claim.verifyOtp') }}">
            @csrf
            <input type="hidden" name="claim_id" value="{{ $claimId }}">
            <div class="mb-3">
                <label for="otp" class="form-label">Enter the OTP sent to your mobile</label>
                <input type="text" name="otp" id="otp" class="form-control" maxlength="6" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Verify OTP</button>
        </form>
    </div>

    <!-- Optional Bootstrap JS for form styling -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- ✅ Force expire OTP session on page unload/back -->
    <!-- <script>
        // If user presses back button and page is cached, reload the page
        window.addEventListener("pageshow", function (event) {
            if (event.persisted || performance.getEntriesByType("navigation")[0].type === "back_forward") {
                window.location.reload();
            }
        });

        function expireOtpSession() {
            navigator.sendBeacon("{{ route('claim.otp.expire', ['claim_id' => $claimId]) }}");
        }

        // Trigger on tab close, refresh, or navigation away
        window.addEventListener('beforeunload', expireOtpSession);
        // Trigger on back button
        window.addEventListener('popstate', expireOtpSession);
    </script> -->
</body>

</html>
