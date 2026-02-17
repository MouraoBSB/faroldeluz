<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - <?= SITE_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'azul-noite': '#0B1020',
                        'azul-cosmico': '#111C3A',
                        'dourado-luz': '#F2C97D',
                        'dourado-intenso': '#E6A94A'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-azul-noite min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md px-4">
        <div class="bg-azul-cosmico rounded-2xl shadow-2xl p-8 border border-dourado-luz/20">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-dourado-luz mb-2">Farol de Luz</h1>
                <p class="text-gray-400">Painel Administrativo</p>
            </div>
            
            <?php if (isset($_SESSION['admin_error'])): ?>
            <div class="bg-red-500/20 border border-red-500 text-red-200 px-4 py-3 rounded mb-6">
                <?= $_SESSION['admin_error'] ?>
            </div>
            <?php unset($_SESSION['admin_error']); endif; ?>
            
            <form method="POST" action="<?= base_url('admin/login') ?>">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                
                <div class="mb-6">
                    <label class="block text-gray-300 mb-2">E-mail</label>
                    <input type="email" name="email" required class="w-full px-4 py-3 bg-azul-noite border border-gray-600 rounded-lg focus:outline-none focus:border-dourado-luz text-white">
                </div>
                
                <div class="mb-6">
                    <label class="block text-gray-300 mb-2">Senha</label>
                    <input type="password" name="password" required class="w-full px-4 py-3 bg-azul-noite border border-gray-600 rounded-lg focus:outline-none focus:border-dourado-luz text-white">
                </div>
                
                <button type="submit" class="w-full bg-dourado-luz text-azul-noite py-3 rounded-lg hover:bg-dourado-intenso transition font-bold">
                    Entrar
                </button>
            </form>
        </div>
    </div>
</body>
</html>
