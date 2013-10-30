<?php

namespace SmartLoader\Component;

interface IComponent
{
    public function headerOutput();
    public function footerOutput();
    
    public function forceHTTP();
    public function setTemplate($tpl);
    
    public function loadView($view);
}