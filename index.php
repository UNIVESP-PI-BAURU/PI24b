<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Conectando Interesses</title>
    <link rel="stylesheet" href="ASSETS/CSS/style.css">
</head>

<body>

    <!-- Cabeçalho -->
    <header class="header">
        <img src="ASSETS/IMG/capa.png" alt="Capa do site">
    </header>

    <!-- Navegação -->
    <nav class="navbar">
        <button onclick="window.location.href='./index.php';">Home</button>
        <button onclick="window.location.href='./sobre_nos.php';">Sobre nós</button>

        <?php
        session_start(); // Inicia a sessão para verificar o login

        // Debug: Verificando os dados de sessão
        echo "<!-- Debugging Sessão -->";
        echo "<!-- id_usuario: " . (isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : 'não definido') . " -->";
        echo "<!-- tipo_usuario: " . (isset($_SESSION['tipo_usuario']) ? $_SESSION['tipo_usuario'] : 'não definido') . " -->";

        // Verifica se o usuário está logado
        if (isset($_SESSION['id_usuario']) && isset($_SESSION['tipo_usuario'])): ?>
            <!-- Usuário logado -->
            <button onclick="window.location.href='./logout.php';">Logout</button>
        <?php else: ?>
            <!-- Usuário não logado -->
            <button onclick="window.location.href='./login.php';">Login</button>
            <button onclick="window.location.href='./cadastro.php';">Cadastro</button>
        <?php endif; ?>
    </nav>
    <!-- Fim Navegação -->

    <!-- Conteúdo Principal -->
    <main class="main-content">
        <section class="welcome-text">
            <h1>Bem-vindo à Conectando Interesses!</h1>
            <p>Aqui você encontra tudo para impulsionar seus estudos em línguas estrangeiras. Vai ficar fora dessa?</p>
            <button onclick="window.location.href='./cadastro.php';" class="signup-button">Cadastre-se já!</button>
        </section>

        <section class="carousel">
            <div class="slides">
                <div class="slide">
                    <img src="ASSETS/IMG/BANNER/1.png" alt="Banner 1">
                </div>
                <div class="slide">
                    <img src="ASSETS/IMG/BANNER/2.png" alt="Banner 2">
                </div>
                <div class="slide">
                    <img src="ASSETS/IMG/BANNER/3.png" alt="Banner 3">
                </div>
            </div>
        </section>
    </main>

    <!-- Rodapé -->
    <footer class="footer">
        <p>UNIVESP PI 2024</p>
        <p><a href="https://github.com/UNIVESP-PI-BAURU/PI24b.git" target="_blank">https://github.com/UNIVESP-PI-BAURU/PI24b.git</a></p>
    </footer>


    <!-- Scripts -->
    <script>
        // Carrossel JavaScript
        const slides = document.querySelector('.slides');
        const slideCount = document.querySelectorAll('.slide').length;
        let currentIndex = 0;

        function showNextSlide() {
            currentIndex = (currentIndex + 1) % slideCount;
            const offset = -currentIndex * 100;
            slides.style.transform = `translateX(${offset}%)`;
        }

        setInterval(showNextSlide, 3000); // Troca de imagem a cada 3 segundos
    </script>

</body>

</html>
