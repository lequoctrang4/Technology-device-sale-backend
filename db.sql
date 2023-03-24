create database if not exists `main_app`;
use `main_app`;

create table if not exists `product` (
    `id` int not null auto_increment,
    `name` varchar(100) not null,
    `color` varchar(30) not null,
    `salePercent` float not null default 0,
    `price` int not null,
    `manufacturer` varchar(100) not null,
    `desciption` varchar(2000) not null,
    primary key(`id`)
);

create table if not exists `category` (
    `id` int not null auto_increment,
    `title` varchar(100) not null,
    `content`  varchar(1000),
    primary key(`id`)
);

create table if not exists `product_category` (
    `productID` int not null,
    `categoryID` int not null,
    primary key (`productID`, `categoryID`),
    foreign key (`productID`) references `product`(`id`) ON UPDATE CASCADE ON DELETE CASCADE,
    foreign key (`categoryID`) references `category`(`id`) ON UPDATE CASCADE ON DELETE CASCADE
);

create table if not exists `user` (
    `id` int not null auto_increment,
    `firstName` varchar(20) not null,
    `middleName` varchar(20) not null,
    `lastName` varchar(20) not null,
    `mobile` varchar(20) unique not null,
    `email` varchar(50),
    `hashedPassword` varchar(50) not null,
    `registeredAt` date not null,
    `lastLogin` date not null,
    `passwordChangedAt` date not null,
    primary key(`id`)
);

create table if not exists `order` (
    `id` int not null auto_increment,
    `userId` int not null,
    `sessionId` varchar(20) not null,
    `token` varchar(20) not null,
    `status` varchar(10) not null,
    `tax` float not null,
    `subTotal` float not null,
    `voucherId` int,
    `shippingId` int not null,
    `note` varchar(100),
    primary key (`id`, `sessionId`),
    foreign key (`userId`) references `user`(`id`)
);
create table if not exists `orderItem` (
    `id` int not null auto_increment,
    `productID` int not null,
    `orderID` int not null,
    `discount` float,
    `quantity` int not null,
    `price` float not null,
    primary key(`id`),
    foreign key (`productID`) references `product`(`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
    foreign key (`orderID`) references `order`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

create table if not exists `voucher` (
    `id` int not null auto_increment,
    `code` varchar(10) not null,
    `discountPercent` float not null,
    `promo` varchar(50),
    `condition` varchar(50),
    primary key(`id`)
);

create table if not exists `voucher_user` (
    `voucherId` int not null,
    `userId` int not null,
    `startDate` date not null,
    `expiredDate` date,
    CONSTRAINT `check_voucher_date` check (`expiredDate` > `startDate`),
    primary key( `voucherId`, `userId`),
    foreign key(`voucherId`) references `voucher`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    foreign key (`userId`) references `user`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

create table if not exists `shippingInfo` (
    `id` int not null auto_increment,
    `userId` int not null,
    `phone` varchar(12) not null,
    `address` varchar(40) not null,
    `city` varchar(20) not null,
    `country` varchar(20) not null,
    primary key(`id`, `userId`),
    foreign key (`userId`) references `user`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

create table if not exists `transaction` (
    `id` int not null auto_increment,
    `userId` int not null,
    `orderId` int not null,
    `code` varchar(10),
    `type` varchar(10) not null,
    `status` int not null,
    `createdAt` date not null,
    `updatedAt` date not null,
    primary key (`id`, `userId`, `orderId`),
    foreign key (`userId`) references `user`(`id`) ON DELETE NO ACTION,
    foreign key (`orderId`) references `order`(`id`) ON DELETE NO ACTION
);

create table if not exists `cart` (
    `id` int not null auto_increment,
    `userId` int not null,
    primary key(`id`, `userId`),
    foreign key (`userId`) references `user`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

create table if not exists `cart_product` (
    `productId` int not null,
    `cartId` int not null,
    `quantity` int not null,
    primary key(`productId`, `cartId`),
    foreign key (`productId`) references `product`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    foreign key (`cartId`) references `cart`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

create table if not exists `attributes` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(50) NOT NULL,
    primary key(`id`)
);

create table if not exists `attribute_value` (
    `id` int not null auto_increment,
    `id_attribute` int(11) NOT NULL,
    `value` varchar(200) NOT NULL,
    `productId` int not null,
    primary key(`id`),
    foreign key (`productId`) references `product`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    foreign key (`id_attribute`) references `attributes`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

