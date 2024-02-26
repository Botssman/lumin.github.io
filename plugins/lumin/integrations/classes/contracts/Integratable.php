<?php namespace Lumin\Integrations\Classes\Contracts;


interface Integratable
{
    /**
     * @param array $data
     * @return mixed
     */
    public function processEntry(array $data) : mixed;
}
