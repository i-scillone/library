<?php
namespace MyClasses;

class DB extends \PDO
{
    /**
     * Crea l'oggetto DB
     * 
     * Crea l'oggetto, setta il DEFAULT_FETCH_MODE a FETCH_NUM e permette 
     * l'estensione di PDOStatement. Se $dsn è un array assume che si tratti di 
     * un elenco di file SQLite, usa il primo per creare la connessione ed i 
     * successivi come "attachments"
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
}