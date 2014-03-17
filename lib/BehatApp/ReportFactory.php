<?php namespace BehatApp;

class ReportFactory implements Factory {

    function make($data)
    {
        return new Report($data);
    }
}
 