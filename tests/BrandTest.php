<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Brand.php";
    require_once "src/Store.php";

    $server = 'mysql:host=localhost:8889;dbname=shoes_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class BrandTest extends PHPUnit_Framework_TestCase
    {

        protected function tearDown()
        {
            Brand::deleteAll();
            Store::deleteAll();
        }

        function test_save()
        {
            //Assert
            $name = "Kicks Tartar";
            $test_brand = new Brand($name);

            //Act
            $test_brand->save();
            $result = Brand::getAll();

            //Assert
            $this->assertEquals($test_brand, $result[0]);
        }

        function test_getAll()
        {
            //Assert
            $name = "Kicks Tartar";
            $test_brand = new Brand($name);
            $test_brand->save();

            $name = "Sports Shoes You Can't Do Sports In";
            $test_brand2 = new Brand($name);
            $test_brand2->save();

            //Act
            $result = Brand::getAll();

            //Assert
            $this->assertEquals([$test_brand, $test_brand2], $result);
        }

        function test_deleteAll()
        {
            //Assert
            $name = "Kicks Tartar";
            $test_brand = new Brand($name);
            $test_brand->save();

            $name = "Sports Shoes You Can't Do Sports In";
            $test_brand2 = new Brand($name);
            $test_brand2->save();

            //Act
            Brand::deleteAll();
            $result = Brand::getAll();

            //Assert
            $this->assertEquals([], $result);
        }

        function test_find()
        {
            //Assert
            $name = "Kicks Tartar";
            $test_brand = new Brand($name);
            $test_brand->save();

            $name = "Sports Shoes You Can't Do Sports In";
            $test_brand2 = new Brand($name);
            $test_brand2->save();

            //Act
            $result = Brand::find($test_brand->getId());

            //Assert
            $this->assertEquals($test_brand, $result);
        }

        function test_addStore()
        {
            //Assert
            $name = "Kicks Tartar";
            $test_brand = new Brand($name);
            $test_brand->save();

            $name = "Shoe Thang";
            $test_store = new Store($name);
            $test_store->save();

            //Act
            $test_brand->addStore($test_store->getId());
            $result = $test_brand->getStores();

            //Assert
            $this->assertEquals([$test_store], $result);
        }

        function test_getStores()
        {
            //Assert
            $name = "Kicks Tartar";
            $test_brand = new Brand($name);
            $test_brand->save();

            $name = "Shoe Thang";
            $test_store = new Store($name);
            $test_store->save();

            $name = "Shoe: There it is";
            $test_store2 = new Store($name);
            $test_store2->save();

            //Act
            $test_brand->addStore($test_store->getId());
            $test_brand->addStore($test_store2->getId());
            $result = $test_brand->getStores();

            //Assert
            $this->assertEquals([$test_store, $test_store2], $result);
        }
    }
?>
