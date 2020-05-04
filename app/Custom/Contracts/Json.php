<?php

namespace App\Custom\Contracts;

interface Json
{
    /**
     * gets main data
     * 
     * @param $data
     */
    public function __construct($data);
    
    /**
     * adds meta data to json
     * 
     * @param $meta
     */
    public function meta($meta);

    /**
     * adds message to meta
     *
     * @param $message
     */
    public function message($message);
}
