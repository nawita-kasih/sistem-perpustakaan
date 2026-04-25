<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login - E-Perpus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #743454 0%, #1e0e60 100%);
            height: 100vh;
            display: flex;
            align-items: center;
        }

        .card {
            border-radius: 20px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .btn-login {
            background-color: #e9b321;
            color: #1e0e60;
            border: none;
            font-weight: 600;
            padding: 12px;
            transition: 0.3s;
        }

        .btn-login:hover {
            background-color: #b1a1e5;
            color: #1e0e60;
            transform: translateY(-3px);
        }

        .form-control {
            background-color: #f0edf8;
            border: 1px solid #b1a1e5;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card p-4 text-center">
                    <img src="https://cdn-icons-png.flaticon.com/512/2232/2232688.png" width="70" class="mx-auto mb-3">
                    <h4 class="fw-bold" style="color: #1e0e60;">E-Perpus Login</h4>
                    <form action="proses_login.php" method="POST" class="text-start mt-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold small">USERNAME</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold small">PASSWORD</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-login w-100 rounded-pill shadow">MASUK SEKARANG</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>