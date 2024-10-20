<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Conectando Interesses</title>
    <link rel="stylesheet" href="ASSETS/CSS/style.css">
</head>

<body>

    <!-- Inicia a sessão -->
    <?php session_start(); ?>

    <!-- Cabeçalho -->
    <header class="header">
        <img src="ASSETS/IMG/capa.png">
    </header>

    <!-- Navegação -->
    <nav class="navbar">
        <a href="./index.php">Home</a>
        <a href="./sobre_nos.php">Sobre nós</a>

        <?php if (isset($_SESSION['id_aluno']) || isset($_SESSION['id_tutor'])): ?>
            <!-- Usuário logado -->
            <a href="./logout.php">Logout</a>
        <?php else: ?>
            <!-- Usuário não logado -->
            <a href="./login.php">Login</a>
            <a href="./cadastro.php">Cadastro</a>
        <?php endif; ?>
    </nav>
    <!-- fim Navegação -->

    <!-- Conteúdo Principal -->
    <main class="main-content">
        <section class="welcome-text">
            <h1>Bem-vindo à Conectando Interesses!</h1>
            <p>Aqui você encontra tudo para impulsionar seus estudos em línguas estrangeiras. Vai ficar fora dessa?</p>
            <a href="./cadastro.php" class="signup-button">Cadastre-se já!</a>
        </section>

        <section class="carousel">
            <div class="slides">
                <div class="slide">
                    <img src="ASSETS/IMG/BANNER/1.png">
                </div>
                <div class="slide">
                    <img src="ASSETS/IMG/BANNER/2.png">
                </div>
                <div class="slide">
                    <img src="ASSETS/IMG/BANNER/3.png">
                </div>
            </div>
        </section>
    </main>

    <!-- Rodapé -->
    <footer class="footer">
        <p>UNIVESP PI 2024</p>
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
