<?php
    date_default_timezone_set("America/Los_Angeles");
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Store.php";
    require_once __DIR__."/../src/Brand.php";

    $app = new Silex\Application();

    $app['debug'] = true;

    $app->register(new Silex\Provider\TwigServiceProvider(), ['twig.path' => __DIR__."/../views"]);

    $server = "mysql:host=localhost:8889;dbname=shoes";
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    $app->get('/', function() use ($app) {
        $stores = Store::getAll();
        $brands = Brand::getAll();
        return $app['twig']->render('home.html.twig', ['stores' => $stores, 'brands' => $brands]);
    });

    $app->get('/store/{id}', function($id) use ($app) {
        $store = Store::find($id);
        $brands = $store->getBrands();
        $available_brands = array_udiff(Brand::getAll(), $brands, function($a, $b) {
            if ($a->getId() == $b->getId()) {
                return 0;
            } else if ($a->getId() > $b->getId()) {
                return 1;
            } else {
                return -1;
            }
        });
        return $app['twig']->render('store.html.twig', ['store' => $store, 'brands' => $brands, 'available_brands' => $available_brands]);
    });

    $app->post('/store/{id}', function($id) use ($app) {
        $store = Store::find($id);
        $store->addBrand($_POST['brand']);
        return $app->redirect('/store/'.$id);
    });

    $app->get('/brand/{id}', function($id) use ($app) {
        $brand = Brand::find($id);
        $stores = $brand->getStores();
        $available_stores = array_udiff(Store::getAll(), $stores, function($a, $b) {
            if ($a->getId() == $b->getId()) {
                return 0;
            } else if ($a->getId() > $b->getId()) {
                return 1;
            } else {
                return -1;
            }
        });
        return $app['twig']->render('brand.html.twig', ['brand' => $brand, 'stores' => $stores, 'available_stores' => $available_stores]);
    });

    $app->post('/brand/{id}', function($id) use ($app) {
        $brand = Brand::find($id);
        $brand->addStore($_POST['store']);
        return $app->redirect('/brand/'.$id);
    });

    $app->get('/remove_store/{brand_id}/{store_id}', function($brand_id, $store_id) {
        $brand = Brand::find($brand_id);
        $brand->removeStore($store_id);
        return $app->redirect('/brand/'.$brand_id);
    });

    return $app
?>
