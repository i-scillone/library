<?php
class Debug
{
    /**
     * File (path incluso) in cui scrivere il Log.
     * 
     * @var string
     */
    private $log;

    /**
     * Costruttore.
     * 
     * Crea l'oggetto Debug e, se non altrimenti specificato, scrive il Log 
     * nella directory dello script chiamante, usando il nome "debug.log".
     * 
     * @param string $log Path e nome del file in cui scrivere il Log.
     */
    public function __construct(string $log='')
    {
        $trace=debug_backtrace();

        if ($log=='') {
            $this->log=dirname($trace[0]['file']).'/debug.log';
        } else {
            $this->log=$log;
        }
    }
    public function log(mixed $x): void
    {
        $now=new DateTimeImmutable();
        $trace=debug_backtrace();
        $f=fopen($this->log,'a');
        fwrite($f,$now->format('❬d M, H:i:s.u❭ '));
        fprintf($f,'❬%s:%d❭ ',basename($trace[0]['file']),$trace[0]['line']);
        if (is_scalar($x) && !is_bool($x)) {
            fwrite($f,$x);
        } else {
            fwrite($f,json_encode($x,JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT));
        }
        fwrite($f,PHP_EOL);
        fclose($f);
    }
}