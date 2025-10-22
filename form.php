<?php
/**
 * Crea i campi di un form e li riempie.
 */
class Form
{
    /**
     * Dati con cui riempire i campi del form.
     * 
     * @var array
     */
    private $data=[];

    /**
     * Crea l'oggetto Form.
     * 
     * @param array $data Dati con cui riempire i campi del form.
     */
    public function __construct(array $data=[])
    {
        $this->data=$data;
    }
    /**
     * Gestisce gli attributi e chiude il tag HTML.
     * 
     * @param string $buf Buffer contenente la prima parte del tag.
     * @param array $attributes Gli attributi oltre quelli fondamentali.
     */
    private function finalize(string &$buf,array $attributes):void
    {
        if ($attributes) foreach ($attributes as $k=>$v) {
            if ($v===true) $buf.=$k.' ';
            elseif (!is_bool($v)) $buf.=sprintf('%s="%s" ',$k,htmlspecialchars($v));
        }
        $buf=trim($buf).'>';
    }
    /**
     * Crea un campo testo o checkbox.
     * 
     * @param string $name Nome del campo.
     * @param string $type Tipo del campo.
     * @param array $attributes Eventuali attributi del campo, in un array associativo.
     * 
     * @return string Stringa contenente il campo del form.
     */
    public function input(string $name,string $type='text',array $attributes=[])
    {
        $buf="<input id=\"{$name}\" name=\"{$name}\" type=\"{$type}\" ";
        switch (strtolower($type)) {
            case 'text':
                $buf.=sprintf('value="%s" ',htmlspecialchars($this->data[$name]??''));
                break;
            case 'checkbox':
                if (isset($this->data[$name])) {
                    if ($this->data[$name]!=false) $buf.='checked ';
                }
                break;
        }
        return $this->finalize($buf,$attributes);
    }
    /**
     * Crea un insieme di bottoni radio.
     * 
     * @param string $name Nome del campo.
     * @param array $values Valori selezionabili, contenuti in un array associativo.
     * @param array $attributes Eventuali attributi del campo, in un array associativo.
     * 
     * @return string Stringa contenente il campo del form.
     */
    public function radio(string $name, array $values, array $attributes=[])
    {
        $buf='';
        foreach ($values as $k=>$v) {
            $buf.=sprintf(
                '<input id="%s" name="%s" type="radio" value="%s" ',
                $name,$name,htmlspecialchars($k)
            );
            if (isset($this->data[$name]) && $this->data[$name]==$k) {
                $buf.='checked ';
            }
            $buf=$this->finalize($buf,$attributes).'&nbsp;'.htmlspecialchars($v).' ';
        }
        return $buf;
    }
    /**
     * Crea una textarea
     * 
     * @param string $name Nome del campo.
     * @param array $attributes Eventuali attributi del campo, in un array associativo.
     * 
     * @return string Stringa contenente il campo del form.
     */
    public function textarea(string $name, array $attributes=[])
    {
        $buf="<textarea id=\"{$name}\" name=\"{$name}\" ";
        $this->finalize($buf,$attributes);
        return $buf.htmlspecialchars($this->data[$name]).'</textarea>';
    }
}