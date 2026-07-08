<?php
namespace MyClasses;

class DB extends \PDO
{
    /**
     * Crea l'oggetto DB.
     * 
     * Crea l'oggetto, setta il DEFAULT_FETCH_MODE a FETCH_NUM e permette 
     * l'estensione di PDOStatement. Se $dsn è un array assume che si tratti di 
     * un elenco di file SQLite, usa il primo per creare la connessione ed i 
     * successivi come "attachments". I path dei files SQLite devono essere
     * assoluti!
     * 
     * @inheritDoc
     */
    public function __construct(string|array $dsn, ?string $user=null, ?string $pw=null, ?array $opts=null)
    {
        if (is_array($dsn)) {
            foreach ($dsn as $i=>$item) {
                $name=pathinfo($item,PATHINFO_FILENAME);
                if ($i==0) {
                    parent::__construct('sqlite:'.$item);
                } else {
                    $this->exec("ATTACH DATABASE '{$item}' AS {$name}");
                }
            }
        } else {
            parent::__construct($dsn,$user,$pw,$opts);
        }
        $this->setAttribute(SELF::ATTR_ERRMODE,SELF::ERRMODE_EXCEPTION);
        $this->setAttribute(SELF::ATTR_DEFAULT_FETCH_MODE,SELF::FETCH_NUM);
        $this->setAttribute(SELF::ATTR_STATEMENT_CLASS,['\MyClasses\DBStatement']);
    }
    /**
     * Converte una tabella in un array associativo.
     * 
     * @param string $query Query che restituisce due colonne di una tabella:
     *                      la prima sarà la chiave dell'array, la seconda sarà 
     *                      il valore.
     * 
     * @return array Array associativo in cui la chiave è la prima colonna ed il
     *               valore la seconda.
     */
    public function toAssoc(string $query): array
    {
        $rows=$this->query($query);
        $r=[];
        while ($row=$rows->fetch(SELF::FETCH_NUM)) {
            $r[$row[0]]=$row[1];
        }
        return $r;
    }
    /**
     * Chiama il costruttore DBTable.
     * 
     * @param $tableName Nome della tabella.
     */
    public function getTable(string $tableName)
    {
        return new DBTable($this,$tableName);
    }
    /**
     * Data ISO in data italiana.
     * 
     * Converte una data in formato ISO in una data in formato italiano.
     * 
     * @param string $iso Data in formato YYYY-MM-DD.
     * 
     * @return string Data in formato DD-MM-YYYY o False in caso d'errore.
     */
    public function fromISO(string $iso): string|bool
    {
        $r=preg_match('/^(\\d{4})-(\\d{2})-(\\d{2})/',$iso,$found);
        if ($r) return $found[3].'-'.$found[2].'-'.$found[1];
        else return false;
    }
    /**
     * Data italiana in formato ISO.
     * 
     * Converte una data italiana in formato ISO.
     * 
     * @param string $loc Data in formato DD-MM-YYYY o DD/MM/YYYY.
     * 
     * @return string Data in formato YYYY-MM-DD o False in caso d'errore.
     */
    public function toISO(string $loc): string|bool
    {
        $r=preg_match('#^(\\d{2})[/-](\\d{2})[/-](\\d{4})#',$loc,$found);
        if ($r) return $found[3].'-'.$found[2].'-'.$found[1];
        else return false;
    }
}
