<?php
namespace MyClasses;

class DB extends \PDO
{
    /**
     * Crea l'oggetto DB
     * 
     * Crea un oggetto usando il costruttore padre e setta il DEFAULT_FETCH_MODE
     * a FETCH_NUM
     * 
     * @inheritDoc
     */
    public function __construct(string $dsn, ?string $user=null, ?string $pw=null, ?array $opts=null)
    {
        parent::__construct($dsn,$user,$pw,$opts);
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