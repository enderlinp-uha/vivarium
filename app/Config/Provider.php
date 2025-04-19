<?php

declare(strict_types=1);

namespace App\Config;

/**
 * Paramètres du helper 'provider'
 */
class Provider
{
    // Nombre maximum de serpents à générer
    public static int $max_to_generate = 10;
    
    // Délai minimum entre deux reproductions (en minutes)
    public static int $reproduction_interval = 3;

    // Nom des serpents
    public static array $name = [
        'Aspen', 'Athena', 'Aurora', 'Blaze', 'Boa', 'Cobra', 'Cosmo', 'Draco', 'Echo', 'Emerald', 
        'Fang', 'Gizmo', 'Hades', 'Hydra', 'Ivy', 'Jade', 'Kaa', 'Loki', 'Lucille', 'Medusa', 
        'Nagini', 'Naga', 'Nyx', 'Onyx', 'Opal', 'Orochi', 'Pebble', 'Phoenix', 'Python', 'Quetzal', 
        'Rex', 'Ruby', 'Sapphire', 'Scales', 'Seraph', 'Shadow', 'Slyther', 'Slinky', 'Slither', 'Spike', 
        'Storm', 'Thunder', 'Titan', 'Topaz', 'Twister', 'Venom', 'Viper', 'Xena', 'Zephyr', 'Ziggy'
    ];

    // Poids des serpents
    public static array $weight = [5, 150000];
    
    // Durée de vie des serpents
    public static array $lifespan = [1, 40];

    // Statut mort ou vivant des serpents
    public static array $status = ['Mort', 'Vivant'];

    // Genre des serpents
    public static array $gender = ['Mâle', 'Femelle'];

    // Race et fertilité des serpents (en nombre d'enfants)
    public static array $fertility_by_race = [
        'Anaconda jaune'                 => [6, 12],
        'Boa arc-en-ciel'                => [4, 7],
        'Boa caoutchouc'                 => [2, 5],
        'Boa constricteur'               => [5, 10],
        'Boa de Madagascar'              => [3, 6],
        'Boa des sables kenyans'         => [2, 4],
        'Boa domestique africain'        => [3, 6],
        'Boa émeraude'                   => [3, 5],
        'Boa mexicain'                   => [2, 5],
        'Couleuvre à museau de taureau'  => [6, 10],
        'Couleuvre d’Esculape'           => [4, 7],
        'Couleuvre de Montpellier'       => [4, 6],
        'Couleuvre japonaise'            => [4, 7],
        'Couleuvre noire'                => [5, 8],
        'Couleuvre rayée'                => [5, 9],
        'Couleuvre rayée des plaines'    => [4, 8],
        'Couleuvre rayée du Texas'       => [4, 7],
        'Couleuvre rhinocéros'           => [2, 4],
        'Indigo mexicain'                => [2, 4],
        'Python améthyste'               => [8, 15],
        'Python birman'                  => [6, 12],
        'Python d’eau de Timor'          => [5, 9],
        'Python royal'                   => [4, 8],
        'Python sanguin'                 => [5, 9],
        'Python tapis'                   => [6, 10],
        'Python tête noire'              => [4, 7],
        'Serpent à nez plat occidental'  => [2, 3],
        'Serpent chat'                   => [3, 5],
        'Serpent corail faux-lait'       => [2, 4],
        'Serpent des blés'               => [4, 6],
        'Serpent fouet des sables'       => [4, 6],
        'Serpent fouet diadème'          => [4, 7],
        'Serpent fouisseur malgache'     => [2, 3],
        'Serpent iridescent'             => [3, 5],
        'Serpent lait'                   => [4, 6],
        'Serpent roi de Californie'      => [5, 8],
        'Serpent roi gris'               => [4, 7],
        'Serpent vert arboricole'        => [3, 6],
    ];

    // Fertilité par défaut (en années)
    public static array $fertility_default = [2, 5];
}
