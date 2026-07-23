<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? 'Admin - E-Shop' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-image: url('https://images.unsplash.com/photo-1497215728101-856f4ea42174?auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }
        body::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.3);
            z-index: -1;
        }
        .login-box { width: 360px; margin: 0; }
        .login-logo { font-size: 35px; text-align: center; margin-bottom: 25px; font-weight: 300; color: #fff; text-shadow: 0 1px 3px rgba(0,0,0,0.3); }
        .login-logo a { color: #fff; text-decoration: none; }
        .login-box-body { background: #fff; padding: 25px; border-top: 0; color: #666; border-radius: 4px; }
        .form-control { border-radius: 0; box-shadow: none; border-color: #d2d6de; }
        .btn-flat { border-radius: 0; -webkit-box-shadow: none; -moz-box-shadow: none; box-shadow: none; border-width: 1px; }
    </style>
</head>
<body class="flex flex-col items-center justify-center min-h-screen">
    <div class="login-box">
        <div class="login-logo">
            <a href="#"><b>Admin</b> Panel</a>
        </div>

        <?php if (App\Core\Session::hasFlash('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4 text-sm">
            <?= App\Core\Session::getFlash('error') ?>
        </div>
        <?php endif; ?>

        <div class="login-box-body shadow-sm">
            <?= $content ?>
        </div>
    </div>
</body>
</html>
