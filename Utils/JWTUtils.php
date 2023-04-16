<?php

use Firebase\JWT\JWT;

function getUserToken($user, $key)
{
    $date = new DateTimeImmutable();
    $expire_at = $date->modify('+5 days')->getTimestamp();
    $payload = array("id" => $user->id, "mobile" => $user->mobile, "email" => $user->email, "isAdmin" => $user->isAdmin, "expire_at" => $expire_at);
    $jwt = JWT::encode($payload, $key, 'HS256');
    return $jwt;
}
