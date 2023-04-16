<?php

declare(strict_types=1);
return [
    "public" => [
        "routes" => [
            ['POST', '/signUp', ['Main\Controllers\AccountController', 'signUp']],
            ['POST', '/signIn', ['Main\Controllers\AccountController', 'signIn']],
        ],
        "middlewares" => []
    ],
    "user" => [
        "routes" => [
            ['GET', '/products', ['Main\Controllers\ProductController', 'getProductsHandler']],
            ['GET', '/product', ['Main\Controllers\ProductController', 'getProductById']],
            ['GET', '/user/profile', ['Main\Controllers\UserController', 'getProfile']],
            ['GET', '/user/getAvatar', ['Main\Controllers\UserController', 'getAvatar']],
            ['POST', '/user/setAvatar', ['Main\Controllers\UserController', 'setAvatar']],
            ['PATCH', '/user/editProfile', ['Main\Controllers\UserController', 'editProfile']],
            ['PATCH', '/user/changePassword', ['Main\Controllers\UserController', 'changePassword']],
            ['POST', '/user/forgetPassword', ['Main\Controllers\UserController', 'forgetPassword']],
            ['GET', '/categories', ['Main\Controllers\ProductController', 'getCategories']],
            ['GET', '/product/attribute', ['Main\Controllers\ProductController', 'getProductAttributes']],
            ['GET', '/users', ['Main\Controllers\UserController', 'getUsers']],
        ],
        "middlewares" => [
            'Main\Middlewares\AuthenticationMiddleware',
        ]
    ],
    "admin" => [
        "routes" => [
            ['POST', '/product', ['Main\Controllers\ProductController', 'createProduct']],
            ['PATCH', '/product', ['Main\Controllers\ProductController', 'updateProduct']],
            ['DELETE', '/product', ['Main\Controllers\ProductController', 'deleteProduct']],
        ],
        "middlewares" => [
            'Main\Middlewares\AuthenticationMiddleware',
        ]
    ]

];
