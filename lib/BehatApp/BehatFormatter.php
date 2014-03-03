<?php namespace BehatApp;

class BehatFormatter
{
    public function plainToHtml($test)
    {
        $output = '';
        $test_to_array = explode("\n", $test);
        foreach($test_to_array as $line) {
            switch($line) {
                case (strpos($line, "Feature") !== FALSE):
                case (strpos($line, "@") !== FALSE):
                    $output .= $line . "<br>";
                    break;
                case (strpos($line, "Scenario") !== FALSE):
                    $output .= "&nbsp;&nbsp;$line<br>";
                    break;
                default:
                    $output .= "&nbsp;&nbsp;&nbsp;&nbsp;$line<br>";
            }
        }
        return $output;
    }

    public function htmlToPlain($test)
    {
        $output = '';
        $test_to_array = explode("<br>", $test);
        $count         = 1;
        foreach($test_to_array as $line) {
            $string = $this->replaceNbsp($line);
            $string = ($count < count($test_to_array) - 1) ? $string . "\n" : $string;
            $count++;
            $output.= $string;
        }
        return $output;
    }

    public function replaceNbsp($string)
    {
        return str_replace('&nbsp;', '', $string);
    }

    public function replaceBr($string)
    {
        return str_replace('<br>', "\n", $string);
    }
}