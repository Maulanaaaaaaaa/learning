<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Dosen</title>
    <link rel="stylesheet" href="css/kazuma.css">
</head>

<body>
    <div class="register-container">
        <div class="register-box">
            <h1>Registrasi Dosen</h1>
            <form action="/registerdosen" method="POST">
                @csrf
                <input type="text" name="nidn" value="{{ old('nidn') }}" placeholder="Masukkan nidn" required>
                @error('nidn')
                    <div class="error">{{ $message }}</div>
                @enderror         

                <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Masukkan Nama Lengkap" required>
                @error('nama')
                    <div class="error">{{ $message }}</div>
                @enderror    

                <input type="email" name="email" value="{{ old('email') }}" placeholder="Masukkan Email" required>
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror

                <!-- Nomor Telepon Input -->
                <input type="text" name="no_telp" value="{{ old('no_telp') }}" placeholder="Masukkan Nomor Telepon">
                @error('no_telp')
                    <div class="error">{{ $message }}</div>
                @enderror

                <!-- Password Input -->
                <input type="password" name="password" placeholder="Masukkan Password" required>
                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror

                <!-- Confirm Password Input -->
                <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required>
                
                <button type="submit" class="register-button">Daftar</button>
            </form>
        </div>
    </div>
</body>

</html>
