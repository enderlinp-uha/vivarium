<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> | <?= app_name(); ?></title>
    <meta name="robots" content="noindex, nofollow">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="favicon.ico">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/font/bootstrap-icons.min.css">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/style.css">
</head>
<body>

    <?php 
        // Inclusion de la barre de navigation horizontale
        include('navbar.php'); 
    ?>

    <div class="container mt-5 mb-3">

        <h3 class="pt-4"><?= esc($title); ?></h3>
        