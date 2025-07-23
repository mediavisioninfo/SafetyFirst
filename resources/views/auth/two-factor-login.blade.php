<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Two Factor Challenge</title>
</head>
<body>
    <form method="POST" action="{{ route('two-factor.login.store') }}">
        @csrf
        <div>
            <label>Authentication Code:</label>
            <input type="text" name="code" autofocus required>
            @error('code')
                <span style="color:red;">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit">Verify</button>
    </form>
</body>
</html>
