<?php

    class Brand
    {
        private $name;
        private $id;

        function __construct($name, $id = null)
        {
            $this->name = $name;
            $this->id = $id;
        }

        function getName()
        {
            return $this->name;
        }

        function setName($new_name)
        {
            $this->name = $new_name;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
            $save = $GLOBALS['DB']->prepare("INSERT INTO brands (name) VALUES (:name);");
            $save->execute([':name' => $this->getName()]);
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function find($id)
        {
            $returned_store = $GLOBALS['DB']->query("SELECT * FROM brands WHERE id = {$id};");
            $store = $returned_store->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Brand', ['name', 'id']);
            return $store[0];
        }

        static function getAll()
        {
            $returned_brands = $GLOBALS['DB']->query("SELECT * FROM brands;");
            if ($returned_brands) {
                $brands = $returned_brands->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Brand', ['name', 'id']);
            } else {
                $brands = [];
            }
            return $brands;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM brands;");
        }
    }

?>
