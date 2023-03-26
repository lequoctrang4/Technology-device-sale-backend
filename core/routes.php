<?php
require __DIR__ . '/router.php';
require __DIR__ .'/CallbackWrapper.php';
function setupRoute() {
  $router = new Router();
  // ##################################################
  // ##################################################
  // ##################################################
  
  // Dynamic GET. Example with 2 variables
  // The $name will be available in full_name.php
  // The $last_name will be available in full_name.php
  // In the browser point to: localhost/user/X/Y
  $router->get('/products', 'product/getProducts');
  $router->get('/product/$id', 'product/getProductById');
  $router->post('/product', 'product/createProduct');
  $router->patch('/product', 'product/updateProduct');
  $router->delete('/product/$id', 'product/deleteProduct');
  // // Dynamic GET. Example with 2 variables with static
  // // In the URL -> http://localhost/product/shoes/color/blue
  // // The $type will be available in product.php
  // // The $color will be available in product.php
  // $router->get('/product/$type/color/$color', 'product.php');
  
  
  // // A route with a callback passing a variable
  // // To run this route, in the browser type:
  // // http://localhost/user/A
  // $router->get('/callback/$name', function ($name) {
  //   echo "Callback executed. The name is $name";
  // });
  
  // // A route with a callback passing 2 variables
  // // To run this route, in the browser type:
  // // http://localhost/callback/A/B
  // $router->get('/callback/$name/$last_name', function ($name, $last_name) {
  //   echo "Callback executed. The full name is $name $last_name";
  // });
  return $router;
  // ##################################################
  // ##################################################
  // ##################################################
}
?>