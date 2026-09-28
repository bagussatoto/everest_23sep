<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 12/13/2018
 * Time: 12:21 PM
 */

class FieldCalculator
{
    const PATTERN = '/(?:\-?\d+(?:\.?\d+)?[\+\-\*\/])+\-?\d+(?:\.?\d+)?/';

    const PARENTHESIS_DEPTH = 10;

    public function calculate($input)
    {
        if (strpos($input, '+') != null || strpos($input, '-') != null || strpos($input, '/') != null || strpos($input, '*') != null) {
            //  Remove white spaces and invalid math chars
            $input = str_replace(',', '.', $input);
            $input = preg_replace('[^0-9\.\+\-\*\/\(\)]', '', $input);

            //  Calculate each of the parenthesis from the top
            $i = 0;
            while (strpos($input, '(') || strpos($input, ')')) {
                $input = preg_replace_callback('/\(([^\(\)]+)\)/', 'self::callback', $input);

                $i++;
                if ($i > self::PARENTHESIS_DEPTH) {
                    break;
                }
            }

            /*
 * obat exponent bro
 */
            if (preg_match('/[0-9]+\.[0-9]+[Ee][-+]?[0-9]+/', $input)) {
                //echo "Ini exponent.". $input;
                $input = number_format($input,10);
            } else {
                //cekMErah("bukan exponent lolos aja");
            }
            //  Calculate the result
            if (preg_match(self::PATTERN, $input, $match)) {
                return $this->compute($match[0]);
            }
            // To handle the special case of expressions surrounded by global parenthesis like "(1+1)"

            if (is_numeric($input)) {
                return $input;
            }

            return 0;
        }

        return $input;
    }

    private function compute($input)
    {
       // cekHitam(":: rumus : $input");
        $compute = create_function('', 'return ' . $input . ';');
// cekHitam($compute);
        if ($compute == null) {
//            cekBiru("YESSSS");
            return 0;
        }
        else {

            return 0 + $compute();
        }
    }

    private function callback($input)
    {
        if (is_numeric($input[1])) {
            return $input[1];
        }
        elseif (preg_match(self::PATTERN, $input[1], $match)) {
            return $this->compute($match[0]);
        }

        return 0;
    }


    public function multiExplode($input)
    {
        $output = preg_split("/(\+|\-|\*|\/|\(|\))/", $input);
        return $output;
    }
}