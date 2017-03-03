<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Brand.php";

    $server = 'mysql:host=localhost:8889;dbname=shoes_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class BrandTest extends PHPUnit_Framework_TestCase
    {

        protected function tearDown()
        {
            Brand::deleteAll();
        }

        function test_save()
        {
            //Assert
            $name = "Shoe Thang";
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
            $name = "Shoe Thang";
            $test_brand = new Brand($name);
            $test_brand->save();

            $name = "Shoe: There it is";
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
            $name = "Shoe Thang";
            $test_brand = new Brand($name);
            $test_brand->save();

            $name = "Shoe: There it is";
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
            $name = "Shoe Thang";
            $test_brand = new Brand($name);
            $test_brand->save();

            $name = "Shoe: There it is";
            $test_brand2 = new Brand($name);
            $test_brand2->save();

            //Act
            $result = Brand::find($test_brand->getId());

            //Assert
            $this->assertEquals($test_brand, $result);
        }
    }
?>
