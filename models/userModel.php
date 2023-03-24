<?php
    require_once('dbConnection.php');
class ProductModel{

        public static function getPets($page){
            $conn = DbConnection::getInstance();
            if($page === 0)
            {
                $stmt = $conn->prepare('SELECT *, "pet" as type FROM pets');
                $stmt->execute(); 
                $result = $stmt->get_result(); 
            }
            else {   
                $record_per_page = 10;
                $start_from = ($page-1)*$record_per_page;
                $stmt = $conn->prepare('SELECT *, "pet" as type FROM pets LIMIT ?, ?');
                $stmt->bind_param('ss', $start_from,$record_per_page);
                $stmt->execute(); 
                $result = $stmt->get_result(); 
            }
            $pets = array();
            while ($row = $result->fetch_assoc()) {
                array_push($pets, $row);
            }       
            return $pets;
        }
        
        
    }
?>