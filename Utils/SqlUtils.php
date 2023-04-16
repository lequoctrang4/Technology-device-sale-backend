<?php
    function get_array_from_result($input) {
        $res = array();
        while ($row = mysqli_fetch_assoc($input)) {
                $res[] = $row;
            }
        return $res;
    }
?>