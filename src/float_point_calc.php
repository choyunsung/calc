<?php
/**
 * User: choyunsung
 * Date: 2018. 8. 3.
 */

namespace steven\calc;


class calc_class
{

    protected $__digit;
    protected $__point_string;
    protected $__max_point_postion = 0;
    public $__float_list = [];
    public $___calc;

    function __construct()
    {
        $this->__digit = 8;
        $this->__point_string = '.';
    }

    public function query($calc_string,$_digit=deafult, $_point_string='.')
    {
        $this->__digit = $_digit;
        $this->__point_string = $_point_string;
        preg_match('/(.*)(\+|\-|\/|\*)(.*)/i',$calc_string,$calc_string_match);
        if(count($calc_string_match)==0)
        {
            return (float)$calc_string;
        }else{
            $this->setPointString($calc_string_match);
            $_return = $this->calculator();
            return (float)$_return;
        }

    }

    private function setPointString($calc_string)
    {
        $calc_string = array_values($calc_string);
        array_shift($calc_string);
        foreach ($calc_string as $val )
        {
            if(preg_match('/\d+/',$val))
            {
                $_ret = $this->instcheck($val);
                if($this->__max_point_postion < $_ret['point_position'])
                    $this->__max_point_postion = $_ret['point_position'];

                $this->__float_list[] = $_ret;
            }else{
                $this->__float_list[] = $val;
            }

        }

    }

    private function instcheck($calc_string)
    {
        $calc_string = trim($calc_string);
        $_expolde_str = explode($this->__point_string,$calc_string);
        $_expolde_str_int = implode('',$_expolde_str);
        $_ret = strlen(end($_expolde_str));
        return [
            'int' => $_expolde_str_int,
            'point_position' => $_ret
        ];
    }

    private function calculator()
    {
        foreach ($this->__float_list as $key => $val )
        {
            if(@preg_match('/\d+/',$val['int']))
            {
                if($this->__max_point_postion > $val['point_position'])
                    $this->___calc[] = $val['int'].str_repeat('0',$this->__max_point_postion - $val['point_position']);
                else
                    $this->___calc[] = $val['int'];
            }
            else{
                $this->___calc[] = $val;
            }
        }

        if(preg_match('/(\d+)(?:\s*)([\+\-\*\/])(?:\s*)(\d+)/', implode($this->___calc), $matches) !== FALSE){
            $operator = $matches[2];

            switch($operator){
                case '+':
                    $p = $matches[1] + $matches[3];
                    break;
                case '-':
                    $p = $matches[1] - $matches[3];
                    break;
                case '*':
                    $p = $matches[1] * $matches[3];
                    break;
                case '/':
                    $p = $matches[1] / $matches[3];
                    break;
            }

        }

        return $this->insSubstr($p,$this->__point_string,$this->__max_point_postion);
    }

    function insSubstr($str, $sub, $posEnd){
        $endString = mb_substr($str, -$posEnd,$posEnd);
        $posStart = strlen($str) - strlen($endString);
        return mb_substr($str, 0, $posStart) . $sub . $endString;
    }

}

function & calc($calc_string,$_digit=deafult , $_point_string='.')
{
    $_a = new calc_class();
    $_return = $_a->query($calc_string,$_digit, $_point_string);
    return $_return;
}