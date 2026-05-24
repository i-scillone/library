<?php
namespace MyClasses;
use \MyClasses\DB;

class DBTable
{
    /** @var DB Oggetto-database che contiene la tabella. */
    private $db;
    /** @var string Nome della tabella. */
    private $name;
    /** @var array<string, string> Struttura della tabella. */
    public $struct=[];
    /** @var int N. dei campi */
    public $fieldsCount;

    /**
     * Determina il tipo di un campo.
     * 
     * Restituisce un carattere in modo che il metodo possa, in futuro, gestire
     * più tipi oltre intero e stringa.
     * 
     * @param string $x Il tipo del campo restituito dal motore di DB.
     * 
     * @return string 'i' se è un intero, 's' se è una stringa o altro.
     */
    private function unifiedType(string $x): string
    {
        if (stripos($x,'INT')!==false) return 'i';
        return 's';
    }

    /**
     * Crea l'oggetto-tabella e carica la sua struttura.
     * 
     * @param DB $object Oggetto-database che contiene la tabella.
     * @param string $table Nome della tabella.
     * 
     * @throws \Exception Se il driver del database non è supportato.
     */
    public function __construct(DB $object,string $table)
    {
        $this->db=$object;
        $this->name=$table;
        $driver = $object->getAttribute(DB::ATTR_DRIVER_NAME);
        if ($driver == 'sqlite') {
            $query = "PRAGMA table_info({$table})";
            $result = $object->query($query);
            $i=0;
            while ($row = $result->fetch(DB::FETCH_ASSOC)) {
                $this->struct[$row['name']] = $this->unifiedType($row['type']);
                $this->struct[++$i] = $this->unifiedType($row['type']);
            }
            $this->fieldsCount=$i;
        } elseif ($driver == 'mysql') {
            $query = "DESCRIBE {$table}";
            $result = $object->query($query);
            $i=0;
            while ($row = $result->fetch(DB::FETCH_ASSOC)) {
                $this->struct[$row['Field']] = $this->unifiedType($row['Type']);
                $this->struct[++$i] = $this->unifiedType($row['Type']);
            }
            $this->fieldsCount=$i;
        } else {
            throw new \Exception("Driver {$driver} non supportato.");
        }
    }

    /**
     * Fornisce il tipo unificato del campo.
     * 
     * @param string $name Nome del campo.
     */
    public function getType(string|int $name): string
    {
        return $this->struct[$name];
    }
    
    /**
     * Inserisce un record in una tabella basandosi su un array associativo.
     * 
     * @param array<string,mixed> $data Array associativo dove la chiave è il nome della colonna
     *                            e il valore è il valore da inserire.
     * 
     * @return bool True se l'inserimento è avvenuto con successo, false altrimenti.
     */
    public function insertAA(array $data): bool
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO {$this->name} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        foreach ($data as $key => $value) {
            if (($value??'')==='') $type=DB::PARAM_NULL;
            elseif ($this->getType($key)=='i') $type=DB::PARAM_INT;
            else $type=DB::PARAM_STR;
            $stmt->bindValue(":{$key}", $value, $type);
        }
        return $stmt->execute();
    }

    /**
     * Inserisce una riga, contenuta in un array indicizzato, in una tabella.
     * 
     * @param array<int,string> Array contente la riga da inserire.
     * 
     * @return bool True se l'INSERT è stato effettuato, false in caso d'errore.
     */
    public function insert(array $data): bool
    {
        $placeholders=rtrim(str_repeat('?,',$this->fieldsCount),',');
        $sql = "INSERT INTO {$this->name} VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        foreach ($data as $key => $value) {
            if (($value??'')==='') $type=DB::PARAM_NULL;
            elseif ($this->getType($key+1)=='i') $type=DB::PARAM_INT;
            else $type=DB::PARAM_STR;
            $stmt->bindValue($key+1, $value, $type);
        }
        return $stmt->execute();
    }
}