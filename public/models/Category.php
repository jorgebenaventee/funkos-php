<?php

namespace models;
class Category
{
    private $id;
    private $name;
    private $is_deleted;
    private $created_at;
    private $updated_at;

    public function __construct($id, $name, $is_deleted)
    {
        $this->id = $id;
        $this->name = $name;
        $this->is_deleted = $is_deleted;
    }


}
