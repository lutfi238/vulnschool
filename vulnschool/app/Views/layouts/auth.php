<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'VulnSchool' ?> — Sistem Akademik Polnep</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: linear-gradient(135deg, #1a237e 0%, #283593 30%, #3949ab 60%, #5c6bc0 100%);
        }

        .auth-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .auth-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            width: 100%;
            max-width: 440px;
            animation: slideUp 0.5s ease-out;
        }

        .auth-card-header {
            background: linear-gradient(135deg, #1a237e 0%, #3949ab 100%);
            color: #fff;
            padding: 2rem;
            text-align: center;
        }
        .auth-card-header h4 {
            font-weight: 700;
            margin-bottom: 0.3rem;
        }
        .auth-card-header p {
            opacity: 0.8;
            font-size: 0.85rem;
            margin-bottom: 0;
        }
        .auth-card-header .icon-circle {
            width: 60px;
            height: 60px;
            background: rgba(255,255,255,0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
        }

        .auth-card-body {
            padding: 2rem;
        }

        .form-floating > .form-control {
            border-radius: 8px;
        }
        .form-floating > .form-control:focus {
            border-color: #3949ab;
            box-shadow: 0 0 0 0.2rem rgba(57,73,171,0.2);
        }

        .btn-auth {
            background: linear-gradient(135deg, #1a237e 0%, #3949ab 100%);
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 0.75rem;
            border-radius: 8px;
            width: 100%;
            font-size: 1rem;
            transition: transform 0.15s, box-shadow 0.15s;
        }
        .btn-auth:hover {
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(26,35,126,0.4);
        }

        .auth-footer {
            text-align: center;
            padding: 1.5rem 0;
            color: rgba(255,255,255,0.6);
            font-size: 0.8rem;
        }
        .auth-footer a { color: rgba(255,255,255,0.8); text-decoration: none; }
        .auth-footer a:hover { color: #fff; }

        .alert { border-radius: 8px; border: none; font-size: 0.85rem; }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <div class="auth-container">
        <div>
            <?= $this->renderSection('content') ?>

            <div class="auth-footer">
                <p class="mb-1">
                    <i class="bi bi-shield-lock me-1"></i>
                    <strong>VulnSchool</strong> — Dibuat untuk pembelajaran Keamanan Informasi
                </p>
                <p class="mb-0">
                    D3 Teknik Informatika · <a href="https://polnep.ac.id">Politeknik Negeri Pontianak</a>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
