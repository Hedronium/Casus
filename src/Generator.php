<?php
namespace solidew\Casus;

abstract class Generator
{
    protected $secure = false;
    protected $max = 0;

    public function isSecure()
    {
        return $this->secure;
    }

    protected function generate($min = 0, $max = PHP_INT_MAX, $secure = true){
        if ($secure) {
            return $this->integer($min, $max);
        } else {
            return mt_rand($min, $max);
        }
    }

    public function float($min = 0, $max = 1, $points = 4, $secure = true)
    {
        $e = pow(10, $points);
        return ($this->generate($min*$e, $max*$e, $secure)/$e);
    }

    public function boolean($secure = true)
    {
        if ($this->generate(0, 1, $secure) === 1) {
            return true;
        } else {
            return false;
        }
    }

    public function alpha($length = 32, $case_randomization = true, $secure = true)
    {
        if ($case_randomization) {
            $ranges = [[65,90],[97,122]];
        } else {
            $ranges = [[97,122]];
        }

        return $this->asciiRange($length, $ranges, $secure);
    }

    public function alphanum($length = 32, $case_randomization = true, $secure = true)
    {
        if ($case_randomization) {
            $ranges = [[65,90],[97,122]];
        } else {
            $ranges = [[97,122]];
        }

        $ranges[] = [48,57];
        return $this->asciiRange($length, $ranges, $secure);
    }

    public function asciiRange($length = 32, $ranges = [[65,90],[97,122],[48,57]], $secure = true)
    {
        if (is_array($ranges)) {
            if (!is_array($ranges[0])) {
                $ranges = [$ranges];
            }
        } else {
            throw new errors\InvalidRange('Specified Range is Invalid');
        }

        $ranges_count = count($ranges);

        if ($ranges_count < 1) {
            throw new errors\InvalidRange('No Range Specified');
        }

        $str = '';

        for ($i = 0; $i < $length; $i++) {
            $rand = $this->generate(0, $ranges_count-1, $secure);

            $min = isset($ranges[$rand][0])?$ranges[$rand][0]:0;
            $max = isset($ranges[$rand][1])?$ranges[$rand][1]:255;

            $str.= chr($this->generate($min, $max, $secure));
        }

        return $str;
    }

    public function integerArray($min = 0, $max = PHP_INT_MAX, $length = 10, $secure = true)
    {
        $array = [];

        for($i = 0; $i < $length; $i++){
            $array[] = $this->generate($min, $max, $secure);
        }

        return $array;
    }

    public function floatArray($min = 0, $max = 1, $points = 4, $length = 10, $secure = true)
    {
        $array = [];

        for($i = 0; $i < $length; $i++){
            $array[] = $this->float($min, $max, $points, $secure);
        }

        return $array;
    }

    public function randomize($input, $secure = true)
    {
        if (is_string($input)) {
            $count = strlen($input);
        } elseif (is_array($input)) {
            $count = count($input);
        } else {
            throw new errors\InvalidInput('Input must be a String or Array');
        }

        $rand = 0;
        $old = null;

        for ($i = 0; $i < $count; $i++) {
            $rand = $this->generate(0, $count-1, $secure);
            $old = $input[$i];
            $input[$i] = $input[$rand];
            $input[$rand] = $old;
        }

        return $input;
    }

    public function selectRandom($input, $length = 1, $secure = true)
    {
        if (is_string($input)) {
            $count = strlen($input);
        } elseif (is_array($input)) {
            $count = count($input);
        } else {
            throw new errors\InvalidInput('Input must be a String or Array');
        }

        $rand = 0;
        $old = null;

        for ($i = 0; $i < $length; $i++) {
            $rand = $this->generate(0, $count-1, $secure);
            $old = $input[$i];
            $input[$i] = $input[$rand];
            $input[$rand] = $old;
        }

        if (is_string($input)) {
            return substr($input, 0, $length);
        } elseif (is_array($input)) {
            return array_slice($input, 0, $length);
        }
    }

    public function byte($secure = true)
    {
        return pack('C', $this->generate(0, 255, $secure));
    }

    public function byteString($length = 32, $secure = true)
    {
        $str = null;
        for($i=0; $i<$length; $i++){
            $str.= pack('C', $this->generate(0, 255, $secure));
        }

        return $str;
    }

    abstract public function integer($mix, $max);
}