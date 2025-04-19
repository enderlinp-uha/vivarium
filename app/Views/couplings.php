<?php
    // Colonnes affichées
    $arrColumnNames = array(
        'id_male'     => 'Mâle',
        'id_female'   => 'Femelle',
        'created_at'  => 'Date d\'accouplement'
    );

    // Transformation des données en entités
    $arrCouplingsToDisplay = [];
    foreach($arrCouplings as $arrCoupling) {
        $objCoupling = new \App\Entities\CouplingEntity();
        
        $objCoupling->male_name = $arrCoupling->male_name;
        $objCoupling->female_name = $arrCoupling->female_name;
        foreach(array_keys($arrColumnNames) as $strField) {
            $objCoupling->{$strField} = $arrCoupling->{$strField};
        }

        $arrCouplingsToDisplay[] = $objCoupling;
    } 
?>

<p class="lead">
    Nombre d'accouplements : <?= $intTotalRows; ?>
</p>

<hr>

<!-- #formList -->
<form id="formList" method="GET" action="<?= site_url(); ?>/coupling">

    <div class="mb-3">
        <a class="btn btn-outline-secondary" href="<?= site_url(); ?>/list">
            <i class="bi bi-arrow-left me-1"></i>
            Retour
        </a>
    </div>

    <table class="table table-hover">
        <thead>
            <tr>
                <th class="text-muted">#</th>
                <?php foreach($arrColumnNames as $strColumnName => $strColumnLabel): ?>
                    <th class="with-ordering 
                        <?= $strColumn === $strColumnName ? 'active' : ''; ?> 
                        order-<?= $strOrder === 'ASC' ? 'asc' : 'desc'; ?>">
                        <a class="text-decoration-none text-black" 
                            href="<?= add_query_param('column', $strColumnName) . '&order=' . $strToggleOrder ?>">
                            <?= $strColumnLabel; ?>
                        </a>
                    </td>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php if (count($arrCouplingsToDisplay) > 0): ?>
                <?php foreach ($arrCouplingsToDisplay as $key => $objSnake): ?>
                    <tr>
                        <td class="text-muted"><?= $key + 1; ?></td>
                        <td><?= $objSnake->male_name; ?> (<a href="<?= site_url(); ?>/edit/<?= $objSnake->id_male; ?>">#<?= $objSnake->id_male; ?></a>)</td>
                        <td><?= $objSnake->female_name; ?> (<a href="<?= site_url(); ?>/edit/<?= $objSnake->id_female; ?>">#<?= $objSnake->id_female; ?></a>)</td>
                        <td><?= $objSnake->getFormattedDate(); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr class="text-center">
                    <td colspan="<?= count($arrColumnNames) + 1; ?>">
                        <?= lang('coupling_none'); ?>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table><!-- .table -->

    <?php include(VIEWPATH . '_partials/pagination.php'); ?>

</form>
