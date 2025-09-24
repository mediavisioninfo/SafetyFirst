<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two-Factor Authentication</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">Two-Factor Authentication</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">
                        Please select a method to receive your One-Time Password (OTP) for verification.
                    </p>

                    {{-- Success Message --}}
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Error Messages --}}
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ url('/2fa-send') }}">
                        @csrf

                        <!-- Mobile Option -->
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="method" id="methodMobile" value="mobile" {{ old('method', 'mobile') == 'mobile' ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="methodMobile">
                                Verify via Mobile
                            </label>
                            <input 
                                type="text" 
                                name="value" 
                                placeholder="Enter Mobile Number" 
                                class="form-control mt-2" 
                                value="{{ old('method') == 'mobile' ? old('value') : '' }}"
                            >
                        </div>

                        <!-- Email Option -->
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="method" id="methodEmail" value="email" {{ old('method') == 'email' ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="methodEmail">
                                Verify via Email
                            </label>
                            <input 
                                type="email" 
                                name="value" 
                                placeholder="Enter Email Address" 
                                class="form-control mt-2"
                                value="{{ old('method') == 'email' ? old('value') : '' }}"
                            >
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Send OTP
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
