<hr>

<div class="mb-3">
    <a class="btn btn-outline-secondary" href="<?= site_url(); ?>/list">
        <i class="bi bi-arrow-left me-1"></i>
        Retour
    </a>
</div>

<div class="mb-3">
<?php
    $arrTree = family_tree_build($id);

    // Affichage de l'arbre généalogique    
    echo family_tree_display($arrTree);
?>
</div>
