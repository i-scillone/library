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
    /**
     * Estrae le colonne della tabella.
     * 
     * Restituisce una serie di array ciascuno contenente i valori di ogni 
     * colonna.
     * 
     * @param int $mode Modalità di estrazione delle righe.
     * 
     * @return array<string, scalar[]>|array<int, scalar[]> Colonne del risultato.
     */
    public function toColumns(int $mode=DB::FETCH_DEFAULT): array
    {
        $rows=$this->fetchAll($mode);
        $keys=array_keys($rows[0]);
        $r=[];
        foreach ($keys as $k) {
            $r[$k]=array_column($rows,$k);
        }
        return $r;
    }
    /**
     * Fa il bindValue() su valori che potrebbero essere nulli.
     * 
     * Se il valore passato al metodo è una stringa vuota passa NULL, altrimenti
     * usa il valore ed il tipo passati come argomenti.
     * 
     * @inheritDoc
     */
    public function bindNullable(string|int $param, mixed $value, int $type = DB::PARAM_STR): bool
    {
        if ($value=='') {
            $r=$this->bindValue($param,null,DB::PARAM_NULL);
        } else {
            $r=$this->bindValue($param,$value,$type);
        }
        return $r;
    }
}