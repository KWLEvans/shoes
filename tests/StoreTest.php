<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Store.php";

    $server = 'mysql:host=localhost:8889;dbname=shoes_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class StoreTest extends PHPUnit_Framework_TestCase
    {

        protected function tearDown()
        {
            Store::deleteAll();
        }

        function test_save()
        {
            //Assert
            $name = "Shoe Thang";
            $test_store = new Store($name);

            //Act
            $test_store->save();
            $result = Store::getAll();

            //Assert
            $this->assertEquals($test_store, $result[0]);
        }

        function test_getAll()
        {
            //Assert
            $name = "Shoe Thang";
            $test_store = new Store($name);
            $test_store->save();

            $name = "Shoe: There it is";
            $test_store2 = new Store($name);
            $test_store2->save();

            //Act
            $result = Store::getAll();

            //Assert
            $this->assertEquals([$test_store, $test_store2], $result);
        }

        function test_deleteAll()
        {
            //Assert
            $name = "Shoe Thang";
            $test_store = new Store($name);
            $test_store->save();

            $name = "Shoe: There it is";
            $test_store2 = new Store($name);
            $test_store2->save();

            //Act
            Store::deleteAll();
            $result = Store::getAll();

            //Assert
            $this->assertEquals([], $result);
        }

        function test_update()
        {
            //Assert
            $name = "Shoe Thang";
            $test_store = new Store($name);
            $test_store->save();

            //Act
            $name2 = "You Shoes, You Lose";
            $test_store->update($name2);
            $result = Store::getAll();

            //Assert
            $this->assertEquals($name2, $result[0]->getName());
        }

        function test_delete()
        {
            //Assert
            $name = "Shoe Thang";
            $test_store = new Store($name);
            $test_store->save();

            $name = "Shoe: There it is";
            $test_store2 = new Store($name);
            $test_store2->save();

            //Act
            $test_store->delete();
            $result = Store::getAll();

            //Assert
            $this->assertEquals([$test_store2], $result);
        }
    }
?>
