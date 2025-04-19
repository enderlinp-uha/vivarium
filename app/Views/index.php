<?php 
    // Colonnes affichées
    $arrColumnNames = [
        'name'     => 'Nom',
        'weight'   => 'Poids',
        'lifespan' => 'Durée de vie',
        'status'   => 'Statut',
        'race'     => 'Race',
        'gender'   => 'Genre'
    ];

    // Transformation des données en entités
    $arrSnakesToDisplay = [];
    foreach($arrSnakes as $arrSnake) {
        $objSnake = new \App\Entities\SnakeEntity();

        $objSnake->id = $arrSnake->id_snake;
        foreach(array_keys($arrColumnNames) as $strField) {
            $objSnake->{$strField} = $arrSnake->{$strField};
        }

        $arrSnakesToDisplay[] = $objSnake;
    }
?>

<p>
    <div class="lead">Mâles : <?= $intMale; ?> / Femelles : <?= $intFemale; ?></div>
    <div id="couplingCount" class="text-muted">Sélectionnés : 0 / 2</div>
</p>

<hr>

<!-- #formCoupling -->
<form id="formCoupling" method="POST" action="<?= site_url(); ?>/coupling" class="d-none">
    <input type="hidden" name="id_male" value="">
    <input type="hidden" name="id_female" value="">
</form>

<!-- #formList -->
<form id="formList" method="GET" action="<?= site_url(); ?>/list">
    
    <?php alert(); ?>

    <div class="mb-3">
        <a class="btn btn-primary" href="<?= site_url(); ?>/add" title="Ajouter">
            <i class="me-1 bi bi-plus"></i> 
            Ajouter
        </a>
        <a class="btn btn-outline-primary" href="<?= site_url(); ?>/populate" title="Peuplement aléatoire">
            <i class="me-1 bi bi-shuffle"></i> 
            Peuplement
        </a>
        <button class="btn btn-outline-primary" type="button" id="btnCoupling" title="Accoupler">
            <i class="me-1 bi bi-link-45deg"></i> 
            Accoupler
        </button>
        <span class="text-muted">|</span>
        <a class="btn btn-outline-secondary" href="<?= site_url(); ?>/coupling" title="Liste des accouplements">
            <i class="me-1 bi bi-list"></i>
            Liste des accouplements
        </a>

        <div class="float-end row gx-2 align-items-center">
            <div class="col-auto">
                Filtrer
            </div>
            <div class="col-auto">
                <select class="form-select" name="race" id="filterRace">
                    <option value="">Toutes races</option>
                    <?php foreach($arrRaces as $value): ?>
                        <option value="<?= esc_html($value); ?>" <?= $strRace == $value ? 'selected' : ''; ?>>
                            <?= esc_html($value); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-auto">
                <select class="form-select" name="gender" id="filterGender">
                    <option value="">Tous genres</option>
                    <option value="Mâle" <?= $strGender == 'Mâle' ? 'selected' : ''; ?>>Mâle</option>
                    <option value="Femelle" <?= $strGender == 'Femelle' ? 'selected' : ''; ?>>Femelle</option>
                </select>
            </div>
        </div>
    </div>

    <table class="table table-hover">
        <thead>
            <tr>
                <th class="text-muted" title="Accouplement">Accoupl.</th>
                <?php foreach($arrColumnNames as $strColumnName => $strColumnLabel): ?>
                <th class="with-ordering 
                    <?= $strColumn === $strColumnName ? 'active' : ''; ?> 
                    order-<?= $strOrder === 'ASC' ? 'asc' : 'desc'; ?>">
                    <a class="text-decoration-none text-black" 
                        href="<?= add_query_param('column', $strColumnName) . '&order=' . $strToggleOrder; ?>">
                        <?= $strColumnLabel; ?>
                    </a>
                </th>
                <?php endforeach; ?>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if (count($arrSnakesToDisplay) > 0): ?>
            <?php foreach($arrSnakesToDisplay as $objSnake): ?>
                <tr>
                    <td>
                        <input class="form-check-input checkId"
                               type="checkbox" 
                               name="id[<?= $objSnake->id; ?>]" 
                               value="<?= $objSnake->id; ?>" 
                               <?= $objSnake->status == 'Mort' ? 'disabled' : ''; ?>>
                    </td>
                    <td><?= $objSnake->name; ?></td>
                    <td><?= $objSnake->getFormattedWeight(); ?></td>
                    <td><?= $objSnake->getFormattedLifespan(); ?></td>
                    <td><?= $objSnake->status; ?></td>
                    <td><?= $objSnake->race; ?></td>
                    <td><?= $objSnake->gender; ?></td>
                    <td class="text-end">
                        <a class="btn btn-outline-secondary" href="<?= site_url(); ?>/edit/<?= $objSnake->id; ?>" title="Modifier">
                            <i class="me-lg-1 bi bi-pencil"></i> 
                            <span class="d-none d-lg-inline-block">Modifier</span>
                        </a>
                        <a class="btn btn-success" href="<?= site_url(); ?>/family_tree/<?= $objSnake->id; ?>" title="Arbre généalogique">
                            <i class="bi bi-tree"></i>
                        </a>
                        <button type="button" 
                                class="btn btn-danger btn-delete"
                                data-bs-toggle="modal"
                                data-bs-target="#modalDelete" 
                                data-id="<?= $objSnake->id; ?>"
                                title="Supprimer">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            <?php endforeach ?>
        <?php else: ?>
            <tr class="text-center">
                <td colspan="<?= count($arrColumnNames) + 2; ?>">
                    <?= lang('snake_none'); ?>
                </td>
            </tr>
        <?php endif ?>
        </tbody>
    </table><!-- .table -->

    <?php include(VIEWPATH . '_partials/pagination.php'); ?>

</form>

<?php 
    // Inclusion de la fenêtre modale 
    include(VIEWPATH . '_partials/modal.php');
