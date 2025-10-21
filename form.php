<?php
class Form
{   private $data=[];

    public function __construct(array $data=[])
    {
        $this->data=$data;
    }
    private function finalize(string $buf,array $attributes)
    {
        if ($attributes) foreach ($attributes as $k=>$v) {
            if ($v===true) $buf.=$k.' ';
            elseif (!is_bool($v)) $buf.=sprintf('%s="%s" ',$k,htmlspecialchars($v));
        }
        return rtrim($buf).'>';
    }
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
}