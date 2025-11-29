<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Learningml</title>
    <link rel="stylesheet" href="css/elaina.css">
    <link rel="stylesheet" href="css/frieren.css">
    <link rel="stylesheet" href="css/wahyu.css">
</head>

<body>
    <div class="login-container">
        <div class="login-box">
            <img src="images/1.jpg" class="logo" alt="LearningML Logo">

            @if(session('error'))
                <div class="error-message">
                    {{ session('error') }}
                </div>
            @endif

            <form action="/login" method="POST">
                @csrf
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" class="login-button">Log in</button>
                <a href="/forgot" class="forgot-password">Lost password?</a>
            </form>
        </div>
    </div>
</body>

</html>
