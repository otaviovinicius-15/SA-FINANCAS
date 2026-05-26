<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<aside class="w-64 bg-gray-900 min-h-screen flex flex-col flex-shrink-0">
    <div class="px-6 py-5 border-b border-gray-700">
        <p class="text-white font-extrabold text-base">💰 Controle Financeiro</p>
        <p class="text-gray-500 text-xs">Área restrita</p>
    </div>
    <nav class="flex-1 p-4 space-y-2">
        <a href="dashboard.php" class="block rounded-lg px-4 py-3 text-sm font-medium transition hover:bg-gray-800 hover:text-white <?= $currentPage === 'dashboard.php' ? 'bg-gray-800 text-white' : 'text-gray-300' ?>">
            📊 Dashboard
        </a>
        <a href="movimentacoes.php" class="block rounded-lg px-4 py-3 text-sm font-medium transition hover:bg-gray-800 hover:text-white <?= $currentPage === 'movimentacoes.php' ? 'bg-gray-800 text-white' : 'text-gray-300' ?>">
            💵 Movimentações
        </a>
        <a href="categorias.php" class="block rounded-lg px-4 py-3 text-sm font-medium transition hover:bg-gray-800 hover:text-white <?= $currentPage === 'categorias.php' ? 'bg-gray-800 text-white' : 'text-gray-300' ?>">
            🏷️ Categorias
        </a>
        <a href="logout.php" class="block rounded-lg px-4 py-3 text-sm font-medium text-red-300 hover:bg-red-500/10 hover:text-red-100">
            🚪 Sair
        </a>
    </nav>
</aside>