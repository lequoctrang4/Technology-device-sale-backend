create database if not exists `main_app`;
use `main_app`;

create table if not exists `product` (
    `id` int not null auto_increment,
    `name` varchar(100) not null,
    `color` varchar(30) not null,
    `salePercent` float not null default 0,
    `price` int not null,
    `manufacturer` varchar(100) not null,
    `html` varchar(10000) not null,
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
    `hashedPassword` varchar(200) not null,
    `registeredAt` date not null,
    `lastLogin` date not null,
    `passwordChangedAt` date not null,
    `isAdmin` tinyint(1) not null default 0,
    `avatar` varchar(100) not null,
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
    `group` varchar(50) NOT NULL,
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

INSERT INTO `category` (`id`, `title`) VALUES ('1', 'Điện thoại');
INSERT INTO `category` (`id`, `title`) VALUES ('2', 'Laptop');
INSERT INTO `category` (`id`, `title`) VALUES ('3', 'Máy tính bảng');
INSERT INTO `category` (`id`, `title`) VALUES ('4', 'Đồng hồ');

INSERT INTO `product` (`id`, `name`, `color`, `salePercent`, `price`, `manufacturer`) VALUES ('1', 'iPhone 13 128GB', 'Pink', '0.2', '24990000', 'Apple');
INSERT INTO `product` (`id`, `name`, `color`, `salePercent`, `price`, `manufacturer`) VALUES ('2', 'iPhone 13 128GB', 'Black', '0.2', '24990000', 'Apple');
INSERT INTO `product` (`id`, `name`, `color`, `salePercent`, `price`, `manufacturer`) VALUES ('3', 'iPhone 13 128GB', 'Green', '0.2', '24990000', 'Apple');
INSERT INTO `product` (`id`, `name`, `color`, `salePercent`, `price`, `manufacturer`) VALUES ('4', 'iPhone 13 128GB', 'White', '0.2', '24990000', 'Apple');
INSERT INTO `product` (`id`, `name`, `color`, `salePercent`, `price`, `manufacturer`) VALUES ('5', 'iPhone 13 128GB', 'Red', '0.2', '24990000', 'Apple');
INSERT INTO `product` (`id`, `name`, `color`, `salePercent`, `price`, `manufacturer`) VALUES ('6', 'iPhone 13 128GB', 'Blue', '0.2', '24990000', 'Apple');

INSERT INTO `product` (`id`, `name`, `color`, `salePercent`, `price`, `manufacturer`) VALUES ('7', 'iPhone 13 256GB', 'Pink', '0.2', '27990000', 'Apple');
INSERT INTO `product` (`id`, `name`, `color`, `salePercent`, `price`, `manufacturer`) VALUES ('8', 'iPhone 13 256GB', 'Black', '0.2', '27990000', 'Apple');
INSERT INTO `product` (`id`, `name`, `color`, `salePercent`, `price`, `manufacturer`) VALUES ('9', 'iPhone 13 256GB', 'Green', '0.2', '27990000', 'Apple');
INSERT INTO `product` (`id`, `name`, `color`, `salePercent`, `price`, `manufacturer`) VALUES ('10', 'iPhone 13 256GB', 'White', '0.2', '27990000', 'Apple');
INSERT INTO `product` (`id`, `name`, `color`, `salePercent`, `price`, `manufacturer`) VALUES ('11', 'iPhone 13 256GB', 'Red', '0.2', '27990000', 'Apple');
INSERT INTO `product` (`id`, `name`, `color`, `salePercent`, `price`, `manufacturer`) VALUES ('12', 'iPhone 13 256GB', 'Blue', '0.2', '27990000', 'Apple');

--Atribute
INSERT INTO `attributes` (`id`, `name`, `group_name`) VALUES ('1', 'Dung lượng Ram', 'Ram & lưu trữ');
INSERT INTO `attributes` (`id`, `name`, `group_name`) VALUES ('2', 'Bộ nhớ trong', 'Ram & lưu trữ');
INSERT INTO `attributes` (`id`, `name`, `group_name`) VALUES ('3', 'Kích thước màn hình', 'Màn hình');
INSERT INTO `attributes` (`id`, `name`, `group_name`) VALUES ('4', 'Công nghệ màn hình', 'Màn hình');
INSERT INTO `attributes` (`id`, `name`, `group_name`) VALUES ('5', 'Độ phân giải màn hình', 'Màn hình');
INSERT INTO `attributes` (`id`, `name`, `group_name`) VALUES ('6', 'Tính năng màn hình', 'Màn hình');
INSERT INTO `attributes` (`id`, `name`, `group_name`) VALUES ('7', 'Tần số quét', 'Màn hình');
INSERT INTO `attributes` (`id`, `name`, `group_name`) VALUES ('8', 'Camera sau', 'Camera sau');
INSERT INTO `attributes` (`id`, `name`, `group_name`) VALUES ('9', 'Quay video', 'Camera sau');
INSERT INTO `attributes` (`id`, `name`, `group_name`) VALUES ('10', 'Tính năng camera', 'Camera sau');
INSERT INTO `attributes` (`id`, `name`, `group_name`) VALUES ('11', 'Camera trước', 'Camera trước');
INSERT INTO `attributes` (`id`, `name`, `group_name`) VALUES ('12', 'Quay video trước', 'Camera trước');
INSERT INTO `attributes` (`id`, `name`, `group_name`) VALUES ('13', 'Chipset', 'Vi xử lý & đồ họa ');
INSERT INTO `attributes` (`id`, `name`, `group_name`) VALUES ('14', 'Loại CPU', 'Vi xử lý & đồ họa ');
INSERT INTO `attributes` (`id`, `name`, `group_name`) VALUES ('15', 'GPU', 'Vi xử lý & đồ họa ');
INSERT INTO `attributes` (`id`, `name`, `group_name`) VALUES ('16', 'Pin', 'Pin & công nghệ sạc');
INSERT INTO `attributes` (`id`, `name`, `group_name`) VALUES ('17', 'Công nghệ sạc', 'Pin & công nghệ sạc');
INSERT INTO `attributes` (`id`, `name`, `group_name`) VALUES ('18', 'Cổng sạc', 'Pin & công nghệ sạc');
INSERT INTO `attributes` (`id`, `name`, `group_name`) VALUES ('19', 'Thẻ SIM', 'Giao tiếp & kết nối');
INSERT INTO `attributes` (`id`, `name`, `group_name`) VALUES ('20', 'Hệ điều hành', 'Giao tiếp & kết nối');
INSERT INTO `attributes` (`id`, `name`, `group_name`) VALUES ('21', 'Công nghệ NFC', 'Giao tiếp & kết nối');
INSERT INTO `attributes` (`id`, `name`, `group_name`) VALUES ('22', 'Hỗ trợ mạng', 'Giao tiếp & kết nối');
INSERT INTO `attributes` (`id`, `name`, `group_name`) VALUES ('23', 'Wi-Fi', 'Giao tiếp & kết nối');
INSERT INTO `attributes` (`id`, `name`, `group_name`) VALUES ('24', 'Bluetooth', 'Giao tiếp & kết nối');
INSERT INTO `attributes` (`id`, `name`, `group_name`) VALUES ('25', 'GPS', 'Giao tiếp & kết nối');
INSERT INTO `attributes` (`id`, `name`, `group_name`) VALUES ('26', 'Kích thước', 'Thiết kế & Trọng lượng');
INSERT INTO `attributes` (`id`, `name`, `group_name`) VALUES ('27', 'Trọng lượng', 'Thiết kế & Trọng lượng');
INSERT INTO `attributes` (`id`, `name`, `group_name`) VALUES ('28', 'Chất liệu mặt lưng', 'Thiết kế & Trọng lượng');
INSERT INTO `attributes` (`id`, `name`, `group_name`) VALUES ('29', 'Tương thích', 'Thông số khác');
INSERT INTO `attributes` (`id`, `name`, `group_name`) VALUES ('30', 'Chỉ số kháng nước, bụi', 'Thông số khác');
INSERT INTO `attributes` (`id`, `name`, `group_name`) VALUES ('31', 'Tiện ích khác', 'Thông số khác');
INSERT INTO `attributes` (`id`, `name`, `group_name`) VALUES ('32', 'Kiểu màn hình', 'Thông số khác');
INSERT INTO `attributes` (`id`, `name`, `group_name`) VALUES ('33', 'Cảm biến vân tay', 'Tiện ích khác');
INSERT INTO `attributes` (`id`, `name`, `group_name`) VALUES ('34', 'Các loại cảm biến', 'Tiện ích khác');
INSERT INTO `attributes` (`id`, `name`, `group_name`) VALUES ('35', 'Tính năng đặc biệt', 'Tiện ích khác');
INSERT INTO `attributes` (`id`, `name`, `group_name`) VALUES ('36', 'Chất liệu mặt kính', 'Thiết kế & Trọng lượng');




