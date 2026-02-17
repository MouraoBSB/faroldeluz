<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Admin - Farol de Luz' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'azul-noite': '#0B1020',
                        'azul-cosmico': '#111C3A',
                        'dourado-luz': '#F2C97D',
                        'dourado-intenso': '#E6A94A',
                        'azul-turquesa': '#2FA4A9',
                        'azul-medio': '#1E6F8C',
                        'cinza-azulado': '#9AA4B2'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-azul-noite text-white">
