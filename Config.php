<?php

class Config
{
    protected $db;
    private $host = 'localhost';
    private $user = 'root';
    private $password = '345612';

    public function __construct(){
        $this->db = new PDO('mysql:dbname=form;host='.$this->host.';charset=UTF8',$this->user,$this->password);
    }
}