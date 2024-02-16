<?php

namespace models;

require_once __DIR__ . '/../config/Config.php';

use config\Config;

class Funko
{

    public const string DEFAULT_IMAGE = 'https://placehold.co/150x150';
    private $id;
    private $name;
    private $price;
    private $stock;
    private $image;
    private $category_id;
    private $category_name;
    private $created_at;
    private $updated_at;


    public function __get(string $name)
    {
        return $this->$name;
    }


    public function __set(string $name, $value): void
    {
        $this->$name = $value;
    }

    public function getImageUrl()
    {
        if ($this->image !== Funko::DEFAULT_IMAGE) {
            $config = Config::getInstance();
            return $config->uploadUrl . $this->image;
        }
        return $this->image;
    }
}