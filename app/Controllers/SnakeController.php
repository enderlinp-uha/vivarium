<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Config\Pagination;
use App\Config\Provider;
use App\Models\CouplingModel;
use App\Models\SnakeModel;
use App\Entities\CouplingEntity;
use App\Entities\SnakeEntity;

class SnakeController
{
    /**
     * Liste des serpents
     *
     * @return void
     */
    public function index(): void
    {
        helper(['alert', 'pagination']);

        $strColumn = $_GET['column'] ?? 'id';
        $strOrder  = $_GET['order']  ?? 'ASC';
        $strRace   = $_GET['race']   ?? '';
        $strGender = $_GET['gender'] ?? '';

        $intPage   = (int) ($_GET['page']     ?? 1);
        $intLimit  = (int) ($_GET['per_page'] ?? Pagination::PER_PAGE_DEFAULT);
        $intOffset = ($intPage - 1) * $intLimit;

        $strToggleOrder = $strOrder === 'ASC' ? 'DESC' : 'ASC';

        $objSnakes = new SnakeModel();

        // Application des filtres, tri et pagination
        $objSnakes->where([
            'race'   => $strRace,
            'gender' => $strGender
        ])->orderBy($strColumn, $strOrder)
          ->limit($intLimit, $intOffset);

        // Récupération des données préparées
        $arrSnakes    = $objSnakes->findAll();
        $intTotalRows = $objSnakes->countFiltered();
        
        // Récupération du nombre de mâles et de femelles
        foreach($objSnakes->getGenderCount() as $row) {
            $arrMaleFemale[$row->gender] = $row->count;
        }

        // Récupération de la liste de races de serpents
        foreach($objSnakes->getCurrentRaces() as $row) {
            $arrRaces[$row->race] = $row->race;
        }

        view('index', [
             'title'             => 'Liste des serpents',
             'objSnakes'         => $objSnakes,
             'arrSnakes'         => $arrSnakes,
             'intCurrentPage'    => $intPage,
             'intPerPage'        => $intLimit,
             'intPerPageDefault' => Pagination::PER_PAGE_DEFAULT,
             'arrPages'          => Pagination::PER_PAGE_VALUES,
             'intTotalRows'      => $intTotalRows,
             'strRace'           => $strRace,
             'strGender'         => $strGender,
             'strColumn'         => $strColumn,
             'strOrder'          => $strOrder,
             'arrRaces'          => $arrRaces ?? [],
             'strToggleOrder'    => $strToggleOrder,
             'intMale'           => $arrMaleFemale['Mâle']    ?? 0,
             'intFemale'         => $arrMaleFemale['Femelle'] ?? 0
        ]);
    }

    /**
     * Ajouter un serpent
     *
     * @return void
     */
    public function add(): void
    {
        config('provider');
        helper('alert');

        $strName      = $_POST['name']       ?? '';
        $strBirthDate = $_POST['birth_date'] ?? '';
        $strGender    = $_POST['gender']     ?? '';
        $strRace      = $_POST['race']       ?? '';

        $intWeight    = (int) ($_POST['weight']   ?? 0);
        $intLifespan  = (int) ($_POST['lifespan'] ?? 0);

        view('add', [
             'title'        => 'Ajouter un serpent',
             'arrRaceNames' => array_keys(Provider::$fertility_by_race),
             'objSnakes'    => new SnakeModel(),
             'objSnake'     => new SnakeEntity(),
             'strName'      => $strName,
             'strBirthDate' => $strBirthDate,
             'strGender'    => $strGender,
             'strRace'      => $strRace,
             'intWeight'    => $intWeight,
             'intLifespan'  => $intLifespan
        ]);
    }

    /**
     * Modifier un serpent
     *
     * @param integer $id
     * @return void
     */
    public function edit(int $id): void
    {
        config('provider');
        helper('alert');

        $objSnakes = new SnakeModel();

        // Chargement des caractéristiques du serpent
        $arrSnakes = $objSnakes->findById($id);

        // Récupération des valeurs des champs du formulaire
        $intId         = $_POST['id']         ?? $arrSnakes->id_snake ?? '';
        $strName       = $_POST['name']       ?? $arrSnakes->name     ?? '';
        $strBirthDate  = $_POST['birth_date'] ?? create_from_format('Y-m-d H:i:s', $arrSnakes->birth_date, 'd/m/Y H:i') ?? '';
        $strStatus     = $_POST['status']     ?? $arrSnakes->status   ?? '';
        $strGender     = $_POST['gender']     ?? $arrSnakes->gender   ?? '';
        $strRace       = $_POST['race']       ?? $arrSnakes->race     ?? '';
        $intWeight     = $_POST['weight']     ?? $arrSnakes->weight   ?? 0;
        $intLifespan   = $_POST['lifespan']   ?? $arrSnakes->lifespan ?? 0;

        $objSnake  = new SnakeEntity();
        
        // Remplissage de l'entité de l'objet serpent
        $objSnake->id         = $intId;
        $objSnake->name       = $strName;
        $objSnake->birth_date = $strBirthDate;
        $objSnake->status     = $strStatus;
        $objSnake->gender     = $strGender;
        $objSnake->race       = $strRace;
        $objSnake->weight     = $intWeight;
        $objSnake->lifespan   = $intLifespan;

        view('edit', [
             'id'           => $id,
             'title'        => 'Modifier un serpent', 
             'arrRaceNames' => array_keys(Provider::$fertility_by_race),
             'objSnakes'    => $objSnakes,
             'objSnake'     => $objSnake,
             'intId'        => $intId,
             'strRace'      => $strRace
        ]);
    }

    /**
     * Supprimer un serpent
     *
     * @param integer $id
     * @return void
     */
    public function delete(int $id): void
    {
        // Si la requête n'est pas une requête AJAX on redirige l'utilisateur et on affiche une erreur 500
        if (is_xmlhttprequest() === false) 
        {
            $_SESSION['message'] = array(lang('error_500'), 'danger');
            
            redirect('/list');
        }

        // Chargement du modèle de serpents
        $objSnakes = new SnakeModel();

        // Suppression du serpent
        if ($objSnakes->delete($id)) 
        {
            $arrMessage = array(lang('snake_deleted'), 'success');
        } 
        else 
        {
            $arrMessage = array(lang('retry_later'), 'warning');
        }
        
        $_SESSION['message'] = $arrMessage;

        // Redirection vers la liste des serpents
        redirect('/list');
    }

    /**
     * Arbre généalogique d'un serpent
     *
     * @param integer $id
     * @return void
     */
    public function family_tree(int $id): void
    {
        helper('family_tree');

        view('family_tree', [
             'id'        => $id,
             'title'     => 'Arbre généalogique',
             'objSnakes' => new SnakeModel(),
        ]);
    }

    /**
     * Accouplement de serpents
     *
     * @return void
     */
    public function coupling(): void
    {
        helper(['alert', 'provider']);

        $objSnakes    = new SnakeModel();
        $objCouplings = new CouplingModel();
        $objCoupling  = new CouplingEntity();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_male'], $_POST['id_female'])) {
            $idMale   = (int) $_POST['id_male'];
            $idFemale = (int) $_POST['id_female'];

            $male   = $objSnakes->findById($idMale);
            $female = $objSnakes->findById($idFemale);

            if ($male && $female && $male->gender === 'Mâle' && $female->gender === 'Femelle') {
                $objCoupling->id_male        = $idMale;
                $objCoupling->id_female      = $idFemale;
                $objCoupling->children_count = 0;
                $objCoupling->max_children   = provider_fertility($male->race);

                if ($objCouplings->insert($objCoupling)) {
                    $_SESSION['message'] = array(lang('coupling_created'), 'success');
                } else {
                    $_SESSION['message'] = array(lang('retry_later'), 'danger');
                }
            } else {
                $_SESSION['message'] = array(lang('coupling_gender_invalid'), 'warning');
            }
        } else {
            $_SESSION['message'] = array(lang('coupling_no_data'), 'warning');
        }

        redirect('/list');
    }
    
    /**
     * Liste des accouplements
     * 
     * @return void
     */
    public function couplings(): void
    {
        helper('pagination');

        $strColumn = $_GET['column'] ?? 'id_coupling';
        $strOrder  = $_GET['order']  ?? 'ASC';
        $intPage   = (int) ($_GET['page']     ?? 1);
        $intLimit  = (int) ($_GET['per_page'] ?? Pagination::PER_PAGE_DEFAULT);
        $intOffset = ($intPage - 1) * $intLimit;

        $strToggleOrder = $strOrder === 'ASC' ? 'DESC' : 'ASC';

        $objSnakes = new CouplingModel();

        // Application des tri et pagination
        $objSnakes->orderBy($strColumn, $strOrder)
                  ->limit($intLimit, $intOffset);

        // Récupération des données préparées
        $arrCouplings = $objSnakes->getCouplings();
        $intTotalRows = $objSnakes->countFiltered();

        view('couplings', [
            'title'             => 'Liste des accouplements',
            'objSnakes'         => $objSnakes,
            'arrCouplings'      => $arrCouplings,
            'intCurrentPage'    => $intPage,
            'intPerPage'        => $intLimit,
            'intPerPageDefault' => Pagination::PER_PAGE_DEFAULT,
            'arrPages'          => Pagination::PER_PAGE_VALUES,
            'intTotalRows'      => $intTotalRows,
            'strColumn'         => $strColumn,
            'strOrder'          => $strOrder,
            'strToggleOrder'    => $strToggleOrder
        ]);
    }

    /**
     * Peuplement de nouveaux serpents
     *
     * @return void
     */
    public function populate(): void
    {
        helper('provider');

        // Chargement d'un nouveau modèle de serpents
        $objSnakes = new SnakeModel();

        // On récupère le nombre de nouveaux serpents à générer
        $count = provider_max_to_generate();

        for ($i = 0; $i < $count; $i++)
        {
            // Création d'un nouveau jeu de données de serpents
            $data = provider_build();

            // Chargement de l'entité de l'objet serpent
            $objSnake = new SnakeEntity();

            // Remplissage de l'entité de l'objet serpent
            $objSnake->name       = $data['name'];
            $objSnake->weight     = $data['weight'];
            $objSnake->lifespan   = $data['lifespan'];
            $objSnake->birth_date = $data['birth_date'];
            $objSnake->race       = $data['race'];
            $objSnake->gender     = $data['gender'];

            $objSnakes->insert($objSnake);
        }

        $key = 'snake_populate' . ($count > 1 ? '_plural' : '');
        $_SESSION['message'] = array(sprintf(lang($key), $count), 'success');
        
        redirect('/list');
    }

    /**
     * Génération automatique de nouveaux serpents
     *
     * @return void
     */
    public function reproduce(): void
    {
        header('Content-Type: application/json');

        if (is_xmlhttprequest() === false) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Requête invalide.']);
            return;
        }

        $objSnakes = new SnakeModel();
        $generated = $objSnakes->autoReproduce();

        echo json_encode(['success' => true, 'generated' => $generated]);
    }
    
    /**
     * Mettre à jour le statut d'un serpent
     *
     * @return void
     */
    public function update_status(): void
    {
        // Si la requête n'est pas une requête AJAX on redirige l'utilisateur et on affiche une erreur 500
        if (is_xmlhttprequest() === false) 
        {
            $_SESSION['message'] = array(lang('error_500'), 'danger');
            
            redirect('/list');
        }

        // Chargement du modèle de serpents
        $objSnakes = new SnakeModel();

        // Mise à jour de la variable 'status' pour les serpents ayant atteint la fin de leur durée de vie
        $objSnakes->updateStatus();
    }
}
