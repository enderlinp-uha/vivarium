<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur <?= esc_html($code); ?> | Snakes</title>
    <meta name="robots" content="noindex, nofollow">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/font/bootstrap-icons.min.css">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/style.css">
</head>
<body class="text-bg-light">
    
    <main class="container d-flex flex-column align-items-center justify-content-center h-100vh">   
        
        <h2 class="display-3 mb-2">Erreur <?= esc_html($code); ?></h2>
        <p class="h3 mb-0">Oups... Vous venez de trouver une page d'erreur</p>
        <p class="text-muted mb-5"><?= esc_html($message); ?></p>

        <?php if (in_array($code, [404, 405])): ?>
        <a href="<?= site_url(); ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Retour à l’accueil
        </a><!-- .btn -->
        <?php endif; ?>

    </main><!-- .container -->

</body>
</html>