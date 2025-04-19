    <div class="alert alert-<?= esc_html($strClass); ?> alert-dismissible fade show" role="alert">
        <?php foreach($arrMessages as $strMessage): ?>
        <div><?= esc($strMessage); ?></div>
        <?php endforeach; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div><!-- .alert -->