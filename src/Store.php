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
        }
    }

?>
