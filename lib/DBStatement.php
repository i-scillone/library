<?php
namespace MyClasses;
use \MyClasses\DB;

class DBStatement extends \PDOStatement
{
    /**
     * Converte una tabella in un array associativo.
     * 
     * Restituisce un array associativo in cui la chiave è la prima colonna
     * della tabella ed il valore la seconda.
     * 
     * @return array Array associativo col formato suddetto.
     */
    public function toAssoc(): array
    {
        $r=[];
        while ($row=$this->fetch(DB::FETCH_NUM)) {
            $r[$row[0]]=$row[1];
        }
        return $r;
    }
}