<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | Learningml</title>
    <!-- Import CSS -->
    <link rel="stylesheet" href="css/font.css">
</head>

<body>
    <header>
        <div class="logo">
            {{-- <img src="{{ asset('images/poltek.png') }}" alt="Logo"> --}}
            <span>Learningml</span>
        </div>
        <nav>
            <a href="/login" class="btn">Login</a>
            <a href="/register" class="btn">Register</a>
            <a href="/registerdosen" class="btn">Registerdsn</a>
        </nav>
    </header>

    <div class="main-content">
        <h2 class="header">Welcome to Learningml</h2>
        <p class="description">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Deleniti asperiores quas quo fuga perferendis. Ea, voluptates officiis dignissimos velit sapiente incidunt fuga totam, adipisci id, eligendi cum alias odit nulla.
        </p>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Building Name</th>
                    <th>Location</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Poltek Main Building</td>
                    <td>Jakarta</td>
                    <td>Central administrative building</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Library</td>
                    <td>Surabaya</td>
                    <td>Public library with extensive collections</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Engineering Lab</td>
                    <td>Bandung</td>
                    <td>Lab for technical research</td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Student Dormitory</td>
                    <td>Yogyakarta</td>
                    <td>Accommodation for students</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- <a href="#" class="accessibility">Accessibility</a> --}}
</body>

</html>
