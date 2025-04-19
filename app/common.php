<?php 

declare(strict_types=1);

use App\Config\App;

/*
| ----------------------------------------------------
| Fonctions transversales
| ----------------------------------------------------
*/

/**
 * Fonction permettant d'ajouter un paramètre GET à l'URL actuelle en supprimant toute occurrence existante
 *
 * @param string $key
 * @param string $value
 * @return string
 */
function add_query_param(string $key, string $value): string {
    $url = current_url();

    $parts = parse_url($url);
    $base  = $parts['path'] ?? '';
    $query = [];

    if (isset($parts['query'])) {
        parse_str($parts['query'], $query);
    }

    $query[$key] = $value;

    $queryString = http_build_query($query);

    return $base . '?' . $queryString;
}

/**
 * Fonction permettant de retourner le nom de l'application
 *
 * @return string
 */
function app_name(): string
{
    return esc(App::NAME);
}

/**
 * Fonction permettant de retourner l'URL de base de l'application
 *
 * @return string
 */
function base_url(): string
{
    $base_url = App::BASE_URL;
    
    if (empty($base_url))
    {
        $protocol = (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ||
                    (! empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
                    ? 'https://' : 'http://';
    
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        
        $base_url = $protocol . $host;
    }

    if (substr($base_url, -1) !== '/')
    {
        $base_url .= '/';
    }

    return esc($base_url);
}

/**
 * Fonction permettant de charger un ou plusieurs fichiers de configuration
 *
 * @param mixed $mixed
 * @return boolean
 */
function config(mixed $mixed): bool
{
    if (! is_array($mixed)) $mixed = array($mixed);

    foreach ($mixed as $filename)
    {
        $path = APPPATH . 'Config/' . strtolower($filename) . '.php';
        if (file_exists($path)) include_once($path);
    }

    return false;
}

/**
 * Fonction permettant de formater des dates
 *
 * @param string $from
 * @param string $date
 * @param string $format
 * @return string
 */
function create_from_format(string $from, string $date, string $format = 'Y-m-d H:i:s'): string
{
    return DateTime::createFromFormat($from, $date)->format($format);    
}

/**
 * Fonction permettant de retourner l'URL en cours
 *
 * @return string
 */
function current_url(): string
{
    return $_SERVER['REQUEST_URI'];
}

/**
 * Fonction permettant d'afficher une page d'erreur 404
 *
 * @return void
 */
function error_404(): void
{
    error_page();
}

/**
 * Fonction permettant d'afficher une page d'erreur
 *
 * @param integer $code
 * @param array $args
 * @return void
 */
function error_page(int $code = 404, array $args = array()): void
{
    ob_clean();
    http_response_code($code);

    $message = lang('error_' . $code, $args);

    include(VIEWPATH . 'errors/error_exception.php');
    exit;
}

/**
 * Fonction permettant d'échapper une chaîne de caractères
 *
 * @param  string $str
 * @param  string $context
 * @return string
 */
function esc(string $str, $context = ''): string 
{
    switch ($context)
    {
        case 'html':
            $str = filter_var($str, FILTER_SANITIZE_SPECIAL_CHARS);
        break;
        
        case 'url':
            $str = filter_var($str, FILTER_SANITIZE_ENCODED);
        break;
        
        default:
            $str = htmlentities($str, ENT_QUOTES, 'UTF-8');
        break;
    }

    return $str;
}

/**
 * Fonction permettant d'échapper une chaîne de caractères pour les attributs HTML
 *
 * @param string $str
 * @return string
 */
function esc_html(string $str): string
{
    return esc($str, 'html');
}

/**
 * Fonction permettant d'échapper une chaîne de caractères pour les URL
 *
 * @param string $str
 * @return string
 */
function esc_url(string $str): string
{
    return esc($str, 'url');
}

/**
 * Fonction permettant de charger un ou plusieurs helper
 *
 * @param mixed $mixed
 * @return boolean
 */
function helper(mixed $mixed): bool
{
    if (! is_array($mixed)) $mixed = array($mixed);

    foreach ($mixed as $filename)
    {
        $path = APPPATH . 'Helpers/' . strtolower($filename) . '_helper.php';
        if (file_exists($path)) include_once($path);
    }

    return false;
}

/**
 * Fonction permettant de tester s'il s'agit d'une requête AJAX
 *
 * @return boolean
 */
function is_xmlhttprequest(): bool 
{
    return (! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
}

/**
 * Fonction permettant de déterminer si l'environnement de l'application est 'production'
 *
 * @return boolean
 */
function is_production(): bool
{
    return App::ENVIRONMENT === 'production';
}

/**
 * Fonction permettant de retourner un message d'alerte ou d'erreur
 *
 * @param string $str
 * @param array $args
 * @return string
 */
function lang(string $str, array $args = array()): string
{
    $message = \App\Config\Alert::$messages[$str];

    if (! empty($args))
    {
        $message  = sprintf($message, ...$args);
    }

    return $message;
}

/**
 * Fonction permettant de retourner un ou plusieurs espaces insécables
 *
 * @param integer $repeat
 * @return string
 */
function nbs(int $repeat = 1): string
{
    return str_repeat('&nbsp;', $repeat);
}

/**
 * Fonction permettant de rediriger vers une autre page
 *
 * @param  string  $str
 * @param  boolean $replace
 * @param  integer $response_code
 * @return void
 */
function redirect(string $str, bool $replace = true, int $response_code = 0): void 
{
    $location = sprintf('Location:%s', $str);
    
    header($location, $replace, $response_code);
    exit;
}

/**
 * Fonction permettant de retourner l'URL de base de l'application sans le '/' final
 *
 * @return string
 */
function site_url(): string
{
    return rtrim(base_url(), '\\/');
}

/**
 * Fonction permettant d'inclure une vue
 *
 * @param string $filename
 * @param array $data
 * @return void
 */
function view(string $filename, array $data = []): void
{
    $filename = str_replace(['../', './'], '', $filename);

    $file_path = VIEWPATH . $filename . '.php';

    if (! file_exists($file_path)) {
        error_page(500);
    }

    extract($data);

    include(VIEWPATH . '_partials/header.php');
    include($file_path);
    include(VIEWPATH . '_partials/footer.php');
}
