<?php

require_once __DIR__ . "/../core/Controller.php";
class Product extends Controller
{
    function getProducts() // Return: [product]
    {
        // controller request: 
        $con = $this->model("ProductModel");
        $result =  $con->getAllProducts();
        // echo "<br>";
        // while ($row = mysqli_fetch_assoc($result)) { // Important line !!! Check summary get row on array ..
            // echo "<tr>";
            // foreach ($row as $field => $value) { // I you want you can right this line like this: foreach($row as $value) {
                // echo  " ". $value ; // I just did not use "htmlspecialchars()" function. 
            // }
            // echo "<br>";
        // }
        $res = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $res[] = $row;
        }
        echo json_encode($res);
    }

    function getProductById($id) //Return type: [product]
    {
        $id = $id[0];
        $con = $this->model("ProductModel");
        $result = $con->getProductById($id);
        $res = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $res[] = $row;
        }
        echo json_encode($res);
    }

    function createProduct()
    {
        // Takes raw data from the request
        $data = json_decode(file_get_contents( 'php://input' ) ); 
        // print_r($data);
        // echo implode("_",$data);
        $con = $this->model("ProductModel");
        $con->createProduct($data);
    }

    function updateProduct() {
        $data = json_decode(file_get_contents('php://input'));
        $con = $this->model("ProductModel");
        $con->updateProduct($data);
    }

    function deleteProduct($id) {
        $con = $this->model("ProductModel");
        $con->deleteProduct($id[0]);
    }
}
?>