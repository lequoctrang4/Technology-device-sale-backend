<?php

declare(strict_types=1);
return [
    "public" => [
        "routes" => [
            ['POST', '/signUp', ['Main\Controllers\AccountController', 'signUp']],
            ['POST', '/signIn', ['Main\Controllers\AccountController', 'signIn']],
            ['GET', '/review/get', ['Main\Controllers\ReviewController', 'getReviewByProductId']],
            ['GET', '/categories', ['Main\Controllers\ProductController', 'getCategories']],
            ['GET', '/products', ['Main\Controllers\ProductController', 'getProductsHandler']],
            ['GET', '/product', ['Main\Controllers\ProductController', 'getProductById']],
            ['GET', '/product/attribute', ['Main\Controllers\ProductController', 'getProductAttributes']],
            ['POST', '/user/forgetPassword', ['Main\Controllers\UserController', 'forgetPassword']],
        ],
        "middlewares" => []
    ],
    "user" => [
        "routes" => [
            ['GET', '/user/profile', ['Main\Controllers\UserController', 'getProfile']],
            ['GET', '/user/getAvatar', ['Main\Controllers\UserController', 'getAvatar']],
            ['POST', '/user/setAvatar', ['Main\Controllers\UserController', 'setAvatar']],
            ['PATCH', '/user/editProfile', ['Main\Controllers\UserController', 'editProfile']],
            ['PATCH', '/user/changePassword', ['Main\Controllers\UserController', 'changePassword']],
            ['GET', '/users', ['Main\Controllers\UserController', 'getUsers']],
            ['POST', '/review/post', ['Main\Controllers\ReviewController', 'addReview']],
            ['DELETE', '/review/delete', ['Main\Controllers\ReviewController', 'deleteReview']],
            ['PATCH', '/review/edit', ['Main\Controllers\ReviewController', 'editReview']],
            ['GET', '/orders', ['Main\Controllers\OrderController', 'getOrdersByUserId']],
            ['GET', '/order', ['Main\Controllers\OrderController', 'getOrderByOrderId']],
            ['POST', '/order', ['Main\Controllers\OrderController', 'createOrder']]

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
            'Main\Middlewares\AdminAuthorizationMiddleware',
        ]
    ]

];
