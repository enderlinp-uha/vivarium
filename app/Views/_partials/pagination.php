<?php
$intTotalPages = count($objSnakes->getPages($intPerPage));
?>
<div class="row">
    <div class="col-sm-6">
        <div class="row gx-2 align-items-center">
            <div class="col-auto">
                Afficher
            </div>
            <div class="col-auto">
                <select class="form-select" name="per_page" id="perPage">
                <?php foreach($arrPages as $intPage): ?>
                    <option value="<?= $intPage; ?>" 
                        <?= $intPage === $intPerPage ? 'selected' : ''; ?>>
                        <?= $intPage; ?>
                    </option>
                <?php endforeach; ?>
                </select>
            </div>
            <div class="col-auto">
                entrées
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="d-flex justify-content-end">
            <nav aria-label="Navigation des pages">
                <ul class="pagination">
                    <div class="my-1 me-2">
                        Affichage <?= max(1, $intCurrentPage * $intPerPage - $intPerPage + 1); ?>
                        à <?= min($intTotalRows, $intCurrentPage * $intPerPage); ?>
                        de <?= $intTotalRows; ?> éléments
                    </div>

                    <li class="page-item <?= $intCurrentPage == 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?= pagination_url(1); ?>">
                            <i class="bi bi-chevron-bar-left"></i>
                        </a>
                    </li>
                    <li class="page-item <?= $intCurrentPage == 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?= pagination_previous($intCurrentPage); ?>">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    </li>

                    <?php
                    $start = max(1, $intCurrentPage - 2);
                    $end   = min($intTotalPages, $intCurrentPage + 2);

                    if ($start > 1) {
                        echo '<li class="page-item"><a class="page-link" href="' . pagination_url(1) . '">1</a></li>';
                        if ($start > 2) {
                            echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
                        }
                    }

                    for ($i = $start; $i <= $end; $i++) {
                        $active = $i === $intCurrentPage ? 'active' : '';
                        echo '<li class="page-item ' . $active . '">';
                        echo '<a class="page-link" href="' . pagination_url($i) . '">' . $i . '</a>';
                        echo '</li>';
                    }

                    if ($end < $intTotalPages) {
                        if ($end < $intTotalPages - 1) {
                            echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
                        }
                        echo '<li class="page-item"><a class="page-link" href="' . pagination_url($intTotalPages) . '">' . $intTotalPages . '</a></li>';
                    }
                    ?>

                    <li class="page-item <?= $intCurrentPage == $intTotalPages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?= pagination_next($intCurrentPage); ?>">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>
                    <li class="page-item <?= $intCurrentPage == $intTotalPages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?= pagination_url($intTotalPages); ?>">
                            <i class="bi bi-chevron-bar-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div><!-- .row -->
