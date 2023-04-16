<?php

namespace Main\Models;

use Main\Models\DbConnection;

class AttributeModel
{
    protected $con = null;
    function __construct()
    {
        $db = new DbConnection();
        $this->con = $db->getInstance();
    }
    function listAttributes()
    {
        $qr = "SELECT * FROM `attributes`";
        $res = $this->con->query($qr);
        return $res;
    }

    function getAttributeById($id)
    {
        $qr = "SELECT * FROM `attributes` where id = \"$id\";";
        $res = $this->con->query($qr);
        return $res;
    }

    function getAttbuteId($value)
    {
        $qr = "SELECT * FROM `attributes` where value=\"$value\"";
        $res = $this->con->query($qr);
        return $res;
    }

    function createAttribute($value, $group)
    {
        $qr = "insert into `attributes` (value, group) values (\"$value\", \"$group\");";
        $res = $this->con->query($qr);
    }
}
