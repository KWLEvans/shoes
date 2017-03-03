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

    $app->delete('/store/{id}', function($id) use ($app) {
        $store = Store::find($id);
        $store->delete();
        return $app->redirect('/');
    });

    $app->patch('/store/{id}', function($id) use ($app) {
        $store = Store::find($id);
        $store->update($_POST['name']);
        return $app->redirect('/');
    });

    $app->get('/store/{id}/edit', function($id) use ($app) {
        $store = Store::find($id);
        return $app['twig']->render('edit_store.html.twig', ['store' => $store]);
    });

    $app->post('/store/{id}/new_brand', function($id) use ($app) {
        $store = Store::find($id);
        $brand = new Brand($_POST['new_brand']);
        $brand->save();
        $store->addBrand($brand->getId());
        return $app->redirect('/store/'.$id);
    });

    $app->get('stores/delete_all', function() use ($app) {
        Store::deleteAll();
        return $app->redirect('/');
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

    $app->get('/brands/delete_all', function() use ($app) {
        Brand::deleteAll();
        return $app->redirect('/');
    });

    $app->get('/remove_store/{brand_id}/{store_id}', function($brand_id, $store_id) use ($app) {
        $brand = Brand::find($brand_id);
        $brand->removeStore($store_id);
        return $app->redirect('/brand/'.$brand_id);
    });

    $app->get('/remove_brand/{store_id}/{brand_id}', function($store_id, $brand_id) use ($app) {
        $store = Store::find($store_id);
        $store->removeBrand($brand_id);
        return $app->redirect('/store/'.$store_id);
    });

    return $app
?>
