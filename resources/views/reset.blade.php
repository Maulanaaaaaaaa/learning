<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="css/frieren.css">
</head>
<body>
    <div class="container">
        <h1>Reset Password</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Soluta perspiciatis officia, asperiores minima magni sunt nobis libero eos, deserunt repellendus aspernatur repellat ea? Minus, possimus. Molestias fugiat ullam aliquam maiores.</p>

        <div class="section">
            <h2>Search by Username</h2>
            <form id="usernameForm">
                <input type="text" name="username" placeholder="Username" required>
                <button type="submit">Search</button>
            </form>
        </div>

        <hr>

        <div class="section">
            <h2>Search by Email Address</h2>
            <form id="emailForm">
                <input type="email" name="email" placeholder="Email Address" required>
                <button type="submit">Search</button>
            </form>
        </div>
    </div>

    

    <script>
        document.getElementById('usernameForm').addEventListener('submit', function(event) {
            event.preventDefault();
            alert('Searching by Username...');
        });

        document.getElementById('emailForm').addEventListener('submit', function(event) {
            event.preventDefault();
            alert('Searching by Email Address...');
        });
    </script>
</body>
</html>
