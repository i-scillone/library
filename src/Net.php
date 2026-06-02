<?php
namespace MyClasses;
/**
 * Estrae informazioni sulla rete.
 */
class Net
{
    /**
     * Restituisce l'URL del server.
     * 
     * Restituisce protocollo + host + porta del server PHP.
     * 
     * @return string URL del server.
     */
    public static function serverUrl(): string
    {
        return (($_SERVER['HTTPS'] ?? 'off') == 'on' ? 'https' : 'http').
               '://' . $_SERVER['HTTP_HOST'];
    }
    /**
     * Restituisce il path del server.
     * 
     * Aggiunge all'URL del server il path del file PHP in esecuzione, senza il 
     * nome del file.
     * 
     * @return string Path del server.
     */
    public static function pathOnServer(): string
    {
        return self::serverUrl().
               rtrim(dirname($_SERVER['PHP_SELF']),'/');
    }
}