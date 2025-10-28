<?php
namespace MyClasses;

class Debug
{
    /** @var string File (path incluso) in cui scrivere il Log */
    private $logFile;
    /** @var string Classe del tag PRE contenente il dump di una variabile */
    private $class;

    /**
     * Costruttore.
     * 
     * Crea l'oggetto Debug e, se non altrimenti specificato, scrive il Log 
     * nella directory dello script chiamante, usando il nome "debug.log".
     * 
     * @param string $logFile Path e nome del file in cui scrivere il Log.
     * @param string $class Classe da usare per varDump.
     */
    public function __construct(string $logFile='', string $class='')
    {
        $trace=debug_backtrace();

        if ($logFile=='') {
            $this->logFile=dirname($trace[0]['file']).'/debug.log';
        } else {
            $this->logFile=$logFile;
        }
        $this->class=$class;
    }
    /**
     * Legge la proprietà logFile
     * 
     * @return string 
     */
    public function getFile(): string
    {
        return $this->logFile;
    }
    /**
     * Registra nel Log un'informazione.
     * 
     * @param mixed $x Il dato da registrare
     */
    public function log(mixed $x): void
    {
        $now=new \DateTimeImmutable();
        $trace=debug_backtrace();
        $f=fopen($this->logFile,'a');
        fwrite($f,$now->format('⟨d M, H:i:s.u⟩ '));
        fprintf($f,'⟨%s:%d⟩ ',basename($trace[0]['file']),$trace[0]['line']);
        if (is_scalar($x) && !is_bool($x)) {
            fwrite($f,$x);
        } else {
            fwrite($f,json_encode($x,JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT));
        }
        fwrite($f,PHP_EOL);
        fclose($f);
    }
    /**
     * Miglioramento di var_dump().
     * 
     * @param array $vars Le variabili di cui fare il dumping.
     */
    public function dump(...$vars): void
    {
        $trace=debug_backtrace();
        echo "<pre class='{$this->class}'>⟨".basename($trace[0]['file']).':'
            .$trace[0]['line']."⟩\n";
        foreach ($vars as $v) {
            $t=gettype($v);
            if ($t=='object') $t=get_class($v).' object';
            echo "⟨{$t}⟩ ";
            if (is_scalar($v)) {
                echo json_encode($v,JSON_UNESCAPED_SLASHES);
            } else {
                echo json_encode($v,JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
            }
            echo PHP_EOL;
        }
        echo "</pre>\n";
    }
    /**
     * Ispeziona un oggetto.
     * 
     * Ispeziona l'oggetto fornito come argomento,
     * se $view è True visualizza il risultato,
     * altrimenti lo restituisce
     * 
     * @param object $x L'oggetto da ispezionare.
     * @param bool $view Se True visualizza l'output.
     * 
     * @return string
     */
    public function inspect(object $x, bool $view=true): string
    {
        $ref=new \ReflectionObject($x);
        $buf='Class: '.$ref->getName().PHP_EOL;
        $buf.='Properties: ';
        $list=$ref->getProperties();
        if (!$list) {
            $buf.='⟨None⟩';
        } else {
            foreach ($list as $item) {
                $name=$item->getName();
                if ($item->isPrivate()) $name.='⟨private⟩';
                elseif ($item->isProtected()) $name.='⟨protected⟩';
                $buf.=$name.', ';
            }
        }
        $buf=rtrim($buf,', ').PHP_EOL;
        $buf.='Methods: ';
        $list=$ref->getMethods();
        if (!$list) {
            $buf.='⟨None⟩';
        } else {
            foreach ($list as $item) {
                $name=$item->getName();
                if ($item->isPrivate()) $name.='⟨private⟩';
                elseif ($item->isProtected()) $name.='⟨protected⟩';
                $buf.=$name.', ';
            }
        }
        $buf=rtrim($buf,', ').PHP_EOL;
        if ($view) echo "<pre class='{$this->class}'>{$buf}</pre>\n";
        return $buf;
    }
    /**
     * Fa il dump nella console del browser.
     * 
     * @param array $vars Le variabili di cui fare il dump.
     */
    public function toConsole(...$vars): void
    {
        $trace=debug_backtrace();
        $label=json_encode(basename($trace[0]['file']).':'.$trace[0]['line']);
        echo "<script>\n";
        foreach ($vars as $v) {
            $escaped=str_replace('</', '<\/',json_encode($v,JSON_UNESCAPED_SLASHES));
            echo "console.log({$label},{$escaped});\n";
        }
        echo "</script>\n";
    }
}