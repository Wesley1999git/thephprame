<?php

namespace Library;

class File{

    public $inputName;
    public $name;
    public $size;
    public $type;
    public $tmpName;
    public $error;

    public function __construct($inputName,$name,$size,$type,$tmpName,$error) {
        $this->inputName = $inputName;
        $this->name = $name;
        $this->size = $size;
        $this->type = $type;
        $this->tmpName = $tmpName;
        $this->error = $error;
    }
}
