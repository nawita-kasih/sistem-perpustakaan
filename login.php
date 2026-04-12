<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-Perpus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            /* Background gradasi agar terlihat modern */
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
        }

        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }

        .card-header {
            background-color: white;
            border-bottom: none;
            padding-top: 25px;
        }

        .btn-primary {
            background: linear-gradient(to right, #667eea, #764ba2);
            border: none;
            padding: 10px;
            border-radius: 8px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-primary:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        .form-control {
            border-radius: 8px;
            padding: 12px;
            background-color: #f8f9fa;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #764ba2;
        }

        .login-title {
            color: #4a4a4a;
            font-weight: 600;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-lg">
                    <div class="card-header text-center">
                        <img src="https://cdn-icons-png.flaticon.com/512/2232/2232688.png" alt="Logo" width="60" class="mb-3">
                        <h4 class="login-title">E-Perpus Login</h4>
                        <p class="text-muted small">Silakan masuk ke akun Anda</p>
                    </div>
                    <div class="card-body p-4">
                        <form action="proses_login.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 shadow-sm">Masuk Sekarang</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

</body>

</html>