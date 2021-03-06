<?php

    class Store
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

        function addBrand($id)
        {
            $GLOBALS['DB']->exec("INSERT INTO brands_stores (brand_id, store_id) VALUES ({$id}, {$this->getId()});");
        }

        function getBrands()
        {
            $returned_brands = $GLOBALS['DB']->query("SELECT brands.* FROM stores
                        JOIN brands_stores ON (brands_stores.store_id = stores.id)
                        JOIN brands ON (brands.id = brands_stores.brand_id)
                        WHERE stores.id = {$this->getId()};");
            if ($returned_brands) {
                $brands = $returned_brands->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Brand', ['name', 'id']);
            } else {
                $brands = [];
            }
            return $brands;
        }

        function removeBrand($id)
        {
            $GLOBALS['DB']->exec("DELETE FROM brands_stores WHERE store_id = {$this->getId()} AND brand_id = {$id};");
        }

        function update($new_name)
        {
            $this->setName($new_name);
            $update = $GLOBALS['DB']->prepare("UPDATE stores SET name = :name WHERE id = :id;");
            $update->execute([':name' => $this->getName(), ':id' => $this->getId()]);
        }

        function save()
        {
            $save = $GLOBALS['DB']->prepare("INSERT INTO stores (name) VALUES (:name);");
            $save->execute([':name' => $this->getName()]);
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM stores WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM brands_stores WHERE store_id = {$this->getId()};");
        }

        static function find($id)
        {
            $returned_store = $GLOBALS['DB']->query("SELECT * FROM stores WHERE id = {$id};");
            $store = $returned_store->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Store', ['name', 'id']);
            return $store[0];
        }

        static function getAll()
        {
            $returned_stores = $GLOBALS['DB']->query("SELECT * FROM stores;");
            if ($returned_stores) {
                $stores = $returned_stores->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Store', ['name', 'id']);
            } else {
                $stores = [];
            }
            return $stores;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM stores;");
            $GLOBALS['DB']->exec("DELETE FROM brands_stores;");
        }

        static function noRepeat($new_name) {
            $stores = Store::getAll();
            $original = true;
            foreach ($stores as $store) {
                if ($store->getName() == $new_name) {
                    $original = false;
                }
            }
            return $original;
        }
    }

?>
