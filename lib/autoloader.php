<?php
/**
 * Implementazione di un Autoloader PSR-4
 * Questo file è in 'lib/' e carica classi che sono in 'lib/'
 */
spl_autoload_register(function(string $class): void
{
    // 1. Il Prefisso Base del Namespace
    $prefix = 'MyClasses\\';

    // 2. La Directory Base dove si trovano i file del codice
    // Poiché l'autoloader è in 'lib/' e le classi sono in 'lib/',
    // la directory base è la directory corrente (lib/).
    $base_dir = __DIR__ . '/'; 

    // Controlla se la classe usa il prefisso del namespace 'App\'
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // Ottiene la parte rimanente del nome della classe (es. 'Form' o 'Debug')
    $relative_class = substr($class, $len);

    // Mappa la parte rimanente (es. 'Form') in un percorso relativo (es. 'Form.php')
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // Se il file esiste, lo include
    if (file_exists($file)) {
        require $file;
    }
});