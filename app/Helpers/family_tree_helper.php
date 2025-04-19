<?php 

declare(strict_types=1);

/*
| ----------------------------------------------------
| Fonctions de création d'arbres généalogiques
| ----------------------------------------------------
*/

/**
 * Fonction permettant de constuire l'arbre généalogique
 *
 * @param integer $id
 * @param integer $level
 * @return array|null
 */
function family_tree_build(int $id, int $level = 0): ?array
{
    // On limite le nombre de récursions
    if ($level > 10) return null;

    $objSnakes = new \App\Models\SnakeModel();
    $snake = $objSnakes->findByID($id);

    if (! $snake) return null;

    $array = [
        'id'      => $snake->id_snake,
        'name'    => $snake->name,
        'gender'  => $snake->gender,
        'race'    => $snake->race,
        'status'  => $snake->status,
        'parents' => array()
    ];

    if ($snake->parent1_id) {
        array_push($array['parents'], family_tree_build($snake->parent1_id, $level + 1));
    }

    if ($snake->parent2_id) {
        array_push($array['parents'], family_tree_build($snake->parent2_id, $level + 1));
    }

    return $array;
}

/**
 * Fonction permettant d'afficher l'arbre généalogique
 *
 * @param array $array
 * @param integer $level
 * @return string|null
 */
function family_tree_display(?array $array, int $level = 0): ?string
{
    if (empty($array) || count($array) === 0) return null;

    $id      = intval($array['id']);
    $name    = esc_html($array['name']);
    $race    = esc_html($array['race']);
    $gender  = esc_html($array['gender']);
    $status  = esc_html($array['status']);
    $parents = $array['parents'];

    if ($level > 0) {
        $str    = nbs(max(0, $level - 1) * 7);
        $str   .= '<span class="text-gray-500">|__</span> ';
        $str   .= '<a class="link-secondary" href="' . site_url() . '/edit/' . $id . '">' . $name . '</a>';
    } else {
        $str = '<strong>' . $name . '</strong>';
    }
    
    $str .= ' (' . $race . ', ' . $gender . ', <em>' . $status . '</em>)';
    $str .= '<br />';

    foreach($parents as $parent) {
        $str .= family_tree_display($parent, $level + 1);
    }

    return $str;
}
