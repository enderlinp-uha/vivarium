# Vivarium

## Qu'est-ce que Vivarium ?

Vivarium est une application web de gestion de vivarium de serpents domestiques développée en PHP.

Elle permet notamment de :
- Ajouter, modifier ou supprimer des serpents ;
- Filtrer par race et genre ;
- Visualiser la répartition des genres ;
- Effectuer des accouplements ;
- Suivre la généalogie des serpents ;
- Générer un peuplement aléatoire ;
- Lister les accouplements ;
- Lancer la reproduction automatique si les conditions sont réunies.

## Installation

### Configuration

Le fichier contenant les paramètres généraux de l'application se trouve dans le dossier [app/Config/App.php](https://github.com/enderlinp-uha/vivarium/blob/main/app/Config/App.php), dont en particulier la constante `BASE_URL`.

Modifiez ces paramètres, tel que figuré ci-dessous :

```php
<?php

declare(strict_types=1);

namespace App\Config;

/**
 * Paramètres de configuration de l'application
 */
class App 
{
    /**
     * URL de base de l'application
     */
    public const BASE_URL = 'http://localhost:8080/';

    /**
     * Nom de l'application
     */
    public const NAME = 'Vivarium';

    /**
     * Environnement de l'application
     */
    public const ENVIRONMENT = 'production';

    /**
     * Localisation
     */
    public const LOCALE = 'fr_FR';

    /**
     * Fuseau horaire
     */
    public const TIMEZONE = 'Europe/Paris';
}
```

Le fichier contenant les paramètres de connexion à la base de données MySQL se trouve dans le dossier [app/Config/Database.php](https://github.com/enderlinp-uha/vivarium/blob/main/app/Config/Database.php).

Modifiez ces paramètres comme ci-dessous :

```php
<?php

declare(strict_types=1);

namespace App\Config;

/**
 * Paramètres de connexion à la base de données
 */
class Database
{
    /**
     * Nom d'hôte de la base de données
     */
    public const HOST = 'localhost';

    /**
     * Nom de la base de données
     */
    public const NAME = 'vivarium';

    /**
     * Numéro de port de la base de données
     */
    public const PORT = 3306;

    /**
     * Jeu de caractères de la base de données 
     */
    public const CHARSET = 'utf8mb4';

    /**
     * Nom d'utilisateur de la base de données
     */
    public const USERNAME = 'root';

    /**
     * Mot de passe de la base de données
     */
    public const PASSWORD = '';
}
```

### Téléversement des fichiers

Téléversez l'ensemble des dossiers et fichiers dans le dossier `www`, `htdocs` ou équivalent de votre serveur web (XAMPP, WAMP, Laragon, etc.).

### Importation de la base de données

Dans PhpMyAdmin :

1. Connectez-vous à l'interface d'administration.
2. Créez une base de données nommée `vivarium` (ou le nom que vous avez défini dans `database.php`).
3. Sélectionnez cette base, puis ouvrez l’onglet **"Importer"**.
4. Importez le fichier [sql/create_tables.sql](https://github.com/enderlinp-uha/vivarium/blob/main/sql/create_tables.sql).

> Si vous souhaitez créer la base manuellement dans PhpMyAdmin avant l'import :
> commentez ou supprimez les lignes suivantes dans le fichier `.sql` avant de l’importer :

```sql
-- 
-- Création de la base de données
-- 
-- CREATE DATABASE IF NOT EXISTS `vivarium`;

-- 
-- Utilisation de la base de données
--
-- USE `vivarium`;
```

### Reproduction automatique des serpents

La reproduction automatique est déclenchée **côté client** via un **timer JavaScript** qui interroge périodiquement le serveur. Si les conditions sont réunies (par exemple : un accouplement valide), de nouveaux serpents peuvent être générés automatiquement.

Elle repose sur la fonction JavaScript suivante :

```js
setInterval(autoReproduce, REFRESH_RATE * 1000);
```

- `REFRESH_RATE` correspond à l’intervalle d’appel au serveur (en secondes).
- La fonction `autoReproduce()` envoie une requête `PATCH` vers `/reproduce` et affiche une alerte dynamique si de nouveaux serpents sont nés.

#### Paramétrage côté serveur

Les paramètres de peuplement automatique sont définis dans la classe [app/config/provider.php](https://github.com/enderlinp-uha/vivarium/blob/main/app/Config/Provider.php) :

```php
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

    ...
}
```

Ce système permet de simuler un vivarium vivant, dans lequel les serpents peuvent se reproduire automatiquement à intervalle régulier, sans intervention manuelle.

### Accès à l'application

Assurez-vous que votre domaine ou sous-domaine (ex : `localhost`, `vivarium.local`, etc.) pointe vers le dossier **`public/`** du projet, et non vers la racine.

Ouvrez ensuite votre navigateur et accédez à :
```
http://localhost:8080/
```

## Structure du projet

```
vivarium/
├── app/
│   ├── config/       → Paramètres de configuration de l'application (base de données, app, etc.)
│   ├── controllers/  → Logique de traitement (MVC) : les actions appelées via les routes
│   ├── core/         → Classes de base du framework (routeur)
│   ├── entities/     → Représentations orientées objet des données (ex : SnakeEntity)
│   ├── helpers/      → Fonctions utilitaires globales (formatage, nom aléatoire, pagination...)
│   ├── models/       → Accès aux données via des requêtes SQL ou méthodes ORM
│   └── views/        → Fichiers de présentation HTML/PHP (liste, formulaire, fenêtre modale, etc.)
├── public/           → Point d’entrée de l'application
│   ├── assets/       → Ressources front-end : CSS, JavaScript, polices de caractères
│   ├── .htaccess     → Réécriture d'URL vers index.php
│   └── index.php     → Front controller : point d'entrée unique de l'application
├── vendor/           → Dépendances installées via Composer (si utilisé)
├── sql/              → Scripts SQL pour créer la base de données
├── writable/         → Contient le dossier logs
```

## Exigences du serveur

- PHP version 8.0 ou supérieure ;
- MySQL/MariaDB version 5.7 ou supérieure ;
- Apache (ou autre serveur compatible avec `.htaccess`) ;
- PhpMyAdmin (recommandé).

## Licence

© 2025 enderlinp-uha. Reproduction interdite sans autorisation de leurs auteurs.