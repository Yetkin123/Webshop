<?php

namespace View\Framework;

abstract class View {

    abstract function show();

    function __construct(){
        $this->show();
    }
}