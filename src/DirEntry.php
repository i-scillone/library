<?php
namespace MyClasses;

use Exception;

/**
 * Rappresenta un file o una directory.
 * 
 * Memorizza in un oggetto i dati di un file o di una directory e li formatta.
 * 
 * @package MyClasses
 */
class DirEntry
{
    /** @var string Percorso+nome del file o della directory */
    public $path;
    /** @var int Permessi in formato numerico */
    public $mode;
    /** @var int Dimensione in Byte */
    public $size;
    /** @var  int Timestamp */
    public $time;

    /**
     * Crea un oggetto dirEntry.
     * 
     * Rappresenta il file o la directory avente path e nome specificato.
     * 
     * @param string $path Path+nome del file o della directory.
     * 
     * @return void
     */
    public function __construct(string $path)
    {
        $this->path=$path;
        $stat=@stat($path);
        if ($stat===false) {
            $this->mode=$this->size=$this->time=false;
        } else {
            $this->mode=$stat['mode'];
            $this->size=$stat['size'];
            $this->time=$stat['mtime'];
        }
    }
    /**
     * Estrae il nome del file senza il path.
     * 
     * Restituisce il basename() del file.
     * 
     * @return string Nome del file.
     */
    public function getName(): string
    {
        return basename($this->path);
    }
    /**
     * Rende leggibili i permessi del file o della directory.
     * 
     * Formatta i permessi numerici in stile "rwx".
     * 
     * @return string Permessi formattati.
     */
    public function getMode(): string
    {
        if ($this->mode===false) return '??????????';
        $r='----------';
        if ($this->mode & 040000) $r[0]='d';
        if ($this->mode & 0400) $r[1]='r';
        if ($this->mode & 0200) $r[2]='w';
        if ($this->mode & 0100) $r[3]='x';
        if ($this->mode & 040) $r[4]='r';
        if ($this->mode & 020) $r[5]='w';
        if ($this->mode & 010) $r[6]='x';
        if ($this->mode & 04) $r[7]='r';
        if ($this->mode & 02) $r[8]='w';
        if ($this->mode & 01) $r[9]='x';
        return $r;
    }
    /**
     * Estrae l'estensione.
     * 
     * Estrae l'estensione dal nome del file o della directory.
     * 
     * @return string L'estensione.
     */
    public function getExt(): string
    {
        return pathinfo($this->path,PATHINFO_EXTENSION);
    }
    /**
     * Formatta la dimensione.
     * 
     * Formatta la dimensione del file, usando i multipli del Byte.
     * 
     * @return string La dimensione del file.
     */
    public function getSize(): string
    {
        if ($this->size/1000000000) {
            $u='GB';
            $s=$this->size/1000000000;
        }
        if ($this->size>1000000) {
            $u='MB';
            $s=$this->size/1000000;
        } elseif ($this->size>1000) {
            $u='kB';
            $s=$this->size/1000;
        } else {
            $u='B';
            $s=$this->size;
        }
        return sprintf('%6.2f %s',$s,$u);
    }
    /**
     * Localizza data ed ora del file o della directory.
     * 
     * Formatta la data e l'ora secondo lo stile italiano.
     * 
     * @return string Data e ora localizzate.
     */
    public function getTime(): string
    {
        $ita=new \IntlDateFormatter('it_IT',\IntlDateFormatter::MEDIUM,\IntlDateFormatter::MEDIUM,'Europe/Rome');
        return $ita->format($this->time);
    }
}
