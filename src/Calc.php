<?php
/**
 * User: choyunsung
 * Date: 2018. 8. 3.
 */

namespace steven;


class calc_class
{

    protected $__digit;
    protected $__point_string;
    protected $__max_point_postion = 0;
    public $__float_list;
    public $family_over_int = false;
    public $___calc;
    public $_max_length;

    function __construct()
    {
        $this->__digit = false;
        $this->__point_string = '.';
        $this->__float_list = array();
        $this->_max_length = strlen(PHP_INT_MAX)-1;
        var_dump($this->_max_length);
        echo "<br>";
    }

    public function query($calc_string,$_digit=false, $_point_string='.')
    {
        if($_digit!==false)
            $this->__digit = $_digit;

        $this->__point_string = $_point_string;
        preg_match('/(.*)(\+|\-|\/|\*)(.*)/i',$calc_string,$calc_string_match);
        if(count($calc_string_match)==0)
        {
            return (string)$calc_string;
        }else{
            $this->setPointString($calc_string_match);
            $_return = $this->calculator();
            return (string)$_return;
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
                {
                    $this->__max_point_postion = $_ret['point_position'];
                }

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

        if(PHP_INT_MAX < $_expolde_str_int)
        {
            $this->family_over_int = true;
            return array('int' => $this->over_int($_expolde_str_int),'point_position' => $_ret );
        }else{
            return array('int' => $_expolde_str_int,'point_position' => $_ret );
        }

    }

    private function over_int($calc_string)
    {
        $_loop = ceil(strlen($calc_string)/$this->_max_length);
        $_basic_lang = strlen($calc_string) - $this->_max_length;
        $_return = array(
            substr($calc_string,-$this->_max_length,$this->_max_length)
        );
        for($k=1; $k < $_loop; ++$k)
        {
            $_splite_count = pow($this->_max_length,$k);
            $_selected_string = strlen($calc_string) -$_splite_count ;
            $_return[] = substr($calc_string,-$_splite_count,$_selected_string );
//                .str_repeat('0',$_splite_count);
        }
        return $_return;
    }

    private function calculator()
    {
        foreach ($this->__float_list as $key => $val )
        {

            if(!@in_array($val['int'],array('+','-','*','/')))
            {
                if($this->__max_point_postion > $val['point_position'])
                {

                    if(is_array($val['int']))
                    {
                        foreach ($val['int'] as $key1 => $val1 )
                        {
                            $_rt[] = $val1.str_repeat('0',$this->__max_point_postion - $val['point_position']);
                        }
                        $this->___calc[] = $_rt;
                    }else {
                        $this->___calc[] = $val['int'].str_repeat('0',$this->__max_point_postion - $val['point_position']);
                    }

                }else{
                    $this->___calc[] = $val['int'];
                }
            }
            else{
                $this->___calc[] = $val;
            }
        }


       if($this->family_over_int === true)
       {
            foreach ($this->___calc[0] as $k => $v )
            {
                if(is_array($this->___calc[2]))
                {
                    $_add = $this->___calc[2][$k];
                }else{
                    $_add = ($k==0)?$this->___calc[2]:'0';
                }
                
                $_new_calc[] = $this->subcalc($v . $this->___calc[1] . $_add);

            }

           return $this->insSubstr($_new_calc,$this->__point_string,$this->__max_point_postion);

       }else{
           return $this->subcalc(implode($this->___calc));
       }



    }

    private function subcalc($ddd)
    {
        if(preg_match('/(\d+)(?:\s*)([\+\-\*\/])(?:\s*)(\d+)/', $ddd, $matches) !== FALSE){
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
            if ($this->family_over_int === true)
                return (string)$p;
            else
                return $this->insSubstr($p, $this->__point_string, $this->__max_point_postion);
        }
    }

    function insSubstr($str, $sub, $posEnd){
        if($this->family_over_int === false)
            $str = sprintf("%s",$str);
        if($this->family_over_int === false && @strpos($sub,'.') )
        {
            $_tmp_str = explode('.',$str);
            return $_tmp_str[0]. $sub. substr(end($_tmp_str),0,$posEnd);
        }else{
            if($this->family_over_int === true)
            {
                $str_tmp = $str[1].$str[0];
                $str = $str_tmp;
                $endString = mb_substr($str, -$posEnd,$posEnd);
                $posStart = strlen($str) - strlen($endString);
                echo mb_substr($str, 0, $posStart)."<br>";
                echo $endString."<br>";
                echo substr($endString,0,$this->__digit)."<br>";
                return (string)mb_substr($str, 0, $posStart) . $sub . (($this->__digit!==false)?substr($endString,0,$this->__digit):$endString);

            }else{
                $endString = mb_substr($str, -$posEnd,$posEnd);
                $posStart = strlen($str) - strlen($endString);
                return mb_substr($str, 0, $posStart) . $sub . (($this->__digit!==false)?substr($endString,0,$this->__digit):$endString);
            }

        }

    }

}

function & calc($calc_string,$_digit=false , $_point_string='.')
{
    $_a = new calc_class();
    $_return = $_a->query($calc_string,$_digit, $_point_string);
    return $_return;
}