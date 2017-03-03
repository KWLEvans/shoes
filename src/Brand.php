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

        function addStore($id)
        {
            $GLOBALS['DB']->exec("INSERT INTO brands_stores (brand_id, store_id) VALUES ({$this->getId()}, {$id});");
        }

        function getStores()
        {
            $returned_stores = $GLOBALS['DB']->query("SELECT stores.* FROM brands
                            JOIN brands_stores ON (brands_stores.brand_id = brands.id)
                            JOIN stores ON (stores.id = brands_stores.store_id)
                            WHERE brands.id = {$this->getId()};");
            if ($returned_stores) {
                $stores = $returned_stores->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Store', ['name', 'id']);
            } else {
                $stores = [];
            }
            return $stores;
        }

        function removeStore($id)
        {
            $GLOBALS['DB']->exec("DELETE FROM brands_stores WHERE brand_id = {$this->getId()} AND store_id = {$id};");
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
            $GLOBALS['DB']->exec("DELETE FROM brands_stores;");
        }

        static function noRepeat($new_name)
        {
            $brands = Brand::getAll();
            $original = true;
            foreach ($brands as $brand) {
                if ($brand->getName() == $new_name) {
                    $original = false;
                }
            }
            return $original;
        }
    }

?>
