<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-success text-white text-center">
                    <h4 class="mb-0">OTP Verification</h4>
                </div>
                <div class="card-body">

                    <!-- Friendly info message -->
                    <div class="alert alert-info text-center">
                        We’ve sent a 6-digit OTP to your registered <strong>email address</strong> and <strong>mobile number</strong>.  
                        Please enter it below to verify your identity. This code will expire in 10 minutes.
                    </div>

                    <!-- Error message -->
                    @if($errors->any())
                        <div class="alert alert-danger text-center">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="/2fa-verify">
                        @csrf
                        <div class="mb-3">
                            <input 
                                type="text" 
                                name="code" 
                                placeholder="Enter 6-digit code" 
                                class="form-control text-center fs-5 fw-bold" 
                                maxlength="6" 
                                required
                            >
                        </div>

                        <button type="submit" class="btn btn-success w-100 fw-bold">
                            Verify
                        </button>
                    </form>

                    <!-- Resend option -->
                    <div class="text-center mt-3">
                        Didn’t receive the code? 
                        <a href="/2fa-resend" class="text-success fw-bold">Resend OTP</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
