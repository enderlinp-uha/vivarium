<?php 
    // Tableau des erreurs
    $arrErrors = array();

    // Test de l'envoi du formulaire
    if (isset($_POST) && count($_POST) > 0) 
    {
        // Gestion des erreurs
        if ($objSnake->id < 1)     $arrErrors['id']   = lang('snake_id_posint_required');
        if ($objSnake->name == '') $arrErrors['name'] = lang('snake_name_required');

        if ($objSnake->weight == 0) {
            $arrErrors['weight'] = lang('snake_weight_required');
        } else if ($objSnake->weight < 0) {
            $arrErrors['weight'] = lang('snake_weight_posint_required');
        }      

        if ($objSnake->lifespan == 0) {
            $arrErrors['lifespan'] = lang('snake_lifespan_required');
        } else if ($objSnake->lifespan < 0) {
            $arrErrors['lifespan'] = lang('snake_lifespan_posint_required');
        }  

        if ($objSnake->birth_date == '') {
            $arrErrors['birth_date'] = lang('snake_birth_date_required');
        } else {
            if (!preg_match('/\d{2}\/\d{2}\/\d{4} \d{2}:\d{2}/', $objSnake->birth_date)) {
                $arrErrors['birth_date'] = lang('snake_birth_date_format_required');
            } else {
                if (create_from_format('d/m/Y H:i', $objSnake->birth_date, 'Y-m-d H:i') > (new DateTime())->format('Y-m-d H:i')) {
                    $arrErrors['birth_date'] = lang('snake_birth_date_lte_now');
                }
            }
        }

        if ($objSnake->status == '') $arrErrors['status'] = lang('snake_status_required');
        if ($objSnake->race   == '') $arrErrors['race']   = lang('snake_race_required');
        if ($objSnake->gender == '') $arrErrors['gender'] = lang('snake_gender_required');

        $_SESSION['message'] = array($arrErrors, 'danger');

        // Si les valeurs des champs du formulaire sont valides
        if (count($arrErrors) === 0) 
        {
            // Mise à jour du serpent dans la base de données
            if ($objSnakes->update($objSnake))
            {
                $_SESSION['message'] = array(lang('snake_updated'), 'success');

                // Redirection vers la liste des serpents
                redirect('/list');
            }
            else
            {
                $_SESSION['message'] = array(lang('retry_later'), 'warning');
            }
        }
    }
?>

    <hr>

    <form action="<?= site_url(); ?>/edit/<?= $intId; ?>" method="POST">

        <?php alert(); ?>

        <input type="hidden" name="id" value="<?= $objSnake->id; ?>">

        <div class="mb-3">
            <label class="form-label" for="name">Nom <span class="text-danger">*</span></label>
            <input class="form-control <?= isset($arrErrors['name']) ? 'is-invalid' : ''; ?>" type="text" name="name" id="name" value="<?= $objSnake->name; ?>" placeholder="Nom du serpent" autofocus>
        </div>

        <div class="mb-3">
            <label class="form-label" for="weight">Poids (g)<span class="text-danger">*</span></label>
            <input class="form-control <?= isset($arrErrors['weight']) ? 'is-invalid' : ''; ?>" type="number" name="weight" id="weight" value="<?= $objSnake->weight; ?>" placeholder="Poids du serpent (en grammes)" min="0">
        </div>

        <div class="mb-3">
            <label class="form-label" for="lifespan">Durée de vie (années)<span class="text-danger">*</span></label>
            <input class="form-control <?= isset($arrErrors['lifespan']) ? 'is-invalid' : ''; ?>" type="number" name="lifespan" id="lifespan" value="<?= $objSnake->lifespan; ?>" placeholder="Durée de vie du serpent (en années)" min="0">
        </div>

        <div class="mb-3">
            <label class="form-label" for="birth_date">Date et heure de naissance <span class="text-danger">*</span></label>
            <input class="form-control <?= isset($arrErrors['birth_date']) ? 'is-invalid' : ''; ?>" type="text" name="birth_date" id="birth_date" value="<?= $objSnake->birth_date; ?>" placeholder="jj/mm/aaaa hh:mm">
        </div>

        <div class="mb-3">
            <label class="form-label d-block" for="status">Statut <span class="text-danger">*</span></label>
            <div class="form-check form-check-inline">
                <input class="form-check-input <?= isset($arrErrors['status']) ? 'is-invalid' : ''; ?>" type="radio" value="Vivant" name="status" id="status-v" <?php if ($objSnake->status == 'Vivant') echo 'checked'; ?>>
                <label class="form-check-label" for="status-v">Vivant</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input <?= isset($arrErrors['status']) ? 'is-invalid' : ''; ?>" type="radio" value="Mort" name="status" id="status-m" <?php if ($objSnake->status == 'Mort') echo 'checked'; ?>>
                <label class="form-check-label" for="status-m">Mort</label>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label" for="race">Race <span class="text-danger">*</span></label>
            <select class="form-select <?= isset($arrErrors['race']) ? 'is-invalid' : ''; ?>" name="race" id="race">
                <option value="">-- Sélectionner une race de serpent</option>
                <?php foreach($arrRaceNames as $strRaceName): ?>
                <option value="<?= $strRaceName; ?>" <?php if ($strRace == $strRaceName) echo 'selected'; ?>>
                    <?= $strRaceName; ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label d-block" for="gender">Genre <span class="text-danger">*</span></label>
            <div class="form-check form-check-inline">
                <input class="form-check-input <?= isset($arrErrors['gender']) ? 'is-invalid' : ''; ?>" type="radio" value="Mâle" name="gender" id="gender-m" <?php if ($objSnake->gender == esc('Mâle', 'html')) echo 'checked'; ?>>
                <label class="form-check-label" for="gender-m">Mâle</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input <?= isset($arrErrors['gender']) ? 'is-invalid' : ''; ?>" type="radio" value="Femelle" name="gender" id="gender-f" <?php if ($objSnake->gender == 'Femelle') echo 'checked'; ?>>
                <label class="form-check-label" for="gender-f">Femelle</label>
            </div>
        </div>

        <div class="mb-3">
            <a class="btn btn-outline-secondary" href="<?= site_url(); ?>/list">
                Annuler
            </a>
            <button class="btn btn-primary" type="submit">
                <i class="me-1 bi bi-cloud-download"></i> 
                Enregistrer
            </button>
        </div>

    </form>
