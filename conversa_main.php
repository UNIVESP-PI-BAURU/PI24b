<main class="main-content">
    <section class="signup-section">

        <h3>Conversa</h3>

        <!-- Exibindo as mensagens -->
        <div id="mensagens">
            <?php foreach ($mensagens as $mensagem): ?>
                <div class="mensagem">
                    <span class="remetente"><?php echo htmlspecialchars($mensagem['remetente']); ?>:</span>
                    <span><?php echo htmlspecialchars($mensagem['conteudo']); ?></span>
                    <br>
                    <span class="data-envio"><?php echo htmlspecialchars($mensagem['data_envio']); ?></span>
                    <?php if ($mensagem['status_leitura'] == 'não_lida'): ?>
                        <span class="status-leitura">(Não lida)</span>
                    <?php else: ?>
                        <span class="status-leitura">(Lida)</span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Formulário para enviar uma nova mensagem -->
        <form method="post" action="">
            <textarea name="mensagem" rows="3" cols="50" placeholder="Digite sua mensagem"></textarea>
            <button type="submit">Enviar</button>
        </form>

    </section>
</main>
