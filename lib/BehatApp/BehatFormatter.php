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
}