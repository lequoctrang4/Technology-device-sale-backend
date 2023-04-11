drop database if exists `main_app`;
create database if not exists `main_app`;
use `main_app`;

create table if not exists `product` (
    `id` int not null auto_increment,
    `name` varchar(100) not null,
    `color` varchar(30) not null,
    `salePercent` int not null default 0,
    `price` int not null,
    `manufacturer` varchar(100) not null,
    `html` varchar(100),
    `image` varchar(255),
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
    `name` varchar(100) not null,
    `mobile` varchar(20) unique not null,
    `email` varchar(50) unique not null,
    `hashedPassword` varchar(200) not null,
    `registeredAt` datetime not null,
    `lastLogin` datetime not null,
    `passwordChangedAt` datetime not null,
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
    `startDate` datetime not null,
    `expiredDate` datetime,
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
    `createdAt` datetime not null,
    `updatedAt` datetime not null,
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
    `group_name` varchar(50) NOT NULL,
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

create table if not exists `review` (
    `id` int NOT NULL AUTO_INCREMENT,
    `productId` int NOT NULL,
    `userId` int NOT NULL,
    `star` int NOT NULL,
    `date` datetime NOT NULL,
    `content` varchar(2000) NOT NULL,
    primary key(`id`),
    foreign key (`productId`) references `product`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    foreign key (`userId`) references `user`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO `category` (`id`, `title`) VALUES ('1', 'Điện thoại');
INSERT INTO `category` (`id`, `title`) VALUES ('2', 'Laptop');
INSERT INTO `category` (`id`, `title`) VALUES ('3', 'Máy tính bảng');
INSERT INTO `category` (`id`, `title`) VALUES ('4', 'Đồng hồ');


INSERT INTO `product` (`id`, `name`, `color`, `salePercent`, `price`, `manufacturer`, `html`, `image`) VALUES
(1, 'iPhone 13 128GB', 'Hồng', 20, 24990000, 'Apple', '', 'https://cdn2.cellphones.com.vn/358x358,webp,q100/media /catalog/product/1/4/14_1_9_2_9.jpg'),
(2, 'iPhone 13 128GB', 'Đen', 20, 24990000, 'Apple', '', 'https://cdn2.cellphones.com.vn/358x/media/catalog/product /_/e/_vi_2_5.jpg'),
(3, 'iPhone 13 128GB', 'Xanh', 20, 24990000, 'Apple', '', 'https://cdn2.cellphones.com.vn/358x/media/catalog/product /x/n/xnnah_kas_3.png'),
(4, 'iPhone 13 128GB', 'Trắng', 20, 24990000, 'Apple', '', 'https://cdn2.cellphones.com.vn/358x/media/catalog/product /t/r/tr_ng_5.jpg'),
(5, 'iPhone 13 128GB', 'Đỏ', 20, 24990000, 'Apple', '', 'https://cdn2.cellphones.com.vn/358x/media/catalog/product /f/i/file_3_10.jpg'),
(6, 'iPhone 13 128GB', 'Xanh dương', 20, 24990000, 'Apple', '', 'https://cdn2.cellphones.com.vn/358x/media/catalog/product /d/_/d_ng_3.jpg'),
(7, 'iPhone 13 256GB', 'Hồng', 20, 27990000, 'Apple', '', 'https://cdn2.cellphones.com.vn/358x/media/catalog/product /d/_/d_ng_3.jpg'),
(8, 'iPhone 13 256GB', 'Đen', 20, 27990000, 'Apple', '', 'https://cdn2.cellphones.com.vn/358x/media/catalog/product /_/e/_vi_2_5.jpg'),
(9, 'iPhone 13 256GB', 'Xanh', 20, 27990000, 'Apple', '', 'https://cdn2.cellphones.com.vn/358x/media/catalog/product /x/n/xnnah_kas_3.png'),
(10, 'iPhone 13 256GB', 'Trắng', 20, 27990000, 'Apple', '', 'https://cdn2.cellphones.com.vn/358x/media/catalog/product /t/r/tr_ng_5.jpg'),
(11, 'iPhone 13 256GB', 'Đỏ', 20, 27990000, 'Apple', '', 'https://cdn2.cellphones.com.vn/358x/media/catalog/product /f/i/file_3_10.jpg'),
(12, 'iPhone 13 256GB', 'Xanh dương', 20, 27990000, 'Apple', '', 'https://cdn2.cellphones.com.vn/358x/media/catalog/product /d/_/d_ng_3.jpg'),
(13, 'Apple MacBook Air M1 256GB 2020', 'Trắng', 30, 28990000, 'Apple', '', 'https://cdn2.cellphones.com.vn/358x358,webp, q100/media/catalog/product/a/i/air_m2.png'),
(14, 'Laptop HP Gaming Victus 15-FA0031DX 6503849', 'Đen', 20, 22990000, 'Hp', '', 'https://cdn2.cellphones.com.vn/358x358, webp,q100/media/catalog/product/l/a/laptop-hp-gaming-victus-15-fa0031dx-6503849-6.jpg'),
(15, 'Laptop Dell Inspiron 3511 5829BLK', 'Đen', 10, 15990000, 'Dell', '', 'https://cdn2.cellphones.com.vn/358x358,webp,q100 /media/catalog/product/t/e/text_ng_n_3__1_71.png'),
(16, 'Máy tính xách tay Asus Gaming Rog Strix G15 G513IH HN015W', 'Đen', 20, 23990000, 'Asus', '', 'https://cdn2.cellphones.com.vn/358x358, webp,q100/media/catalog/product/4/h/4h43.png'),
(17, 'Laptop Lenovo Ideapad Gaming 3 15ARH7', 'Đen', 20, 25490000, 'Lenevo', '', 'https://cdn2.cellphones.com.vn/358x358,webp, q100/media/catalog/product/1/h/1h47.png'),
(18, 'Laptop Lenovo Ideapad Gaming 3 15ARH7', 'Đen', 20, 25490000, 'Lenevo', '', 'https://cdn2.cellphones.com.vn/358x358,webp, q100/media/catalog/product/1/h/1h47.png'),
(19, 'iPad 120 2021 WiFi 64GB', 'Silver', 20, 10990000, 'Apple', '', 'https://cdn2.cellphones.com.vn/358x358,webp,q100 /media/catalog/product/i/p/ipad-10-2-2021-2_6_1.png'),
(20, 'iPad Air 5 (2022) 64GB', 'Xanh dương', 10, 16990000, 'Apple', '', 'https://cdn2.cellphones.com.vn/358x358,webp ,q100/media/catalog/product/9/_/9_10_71_1_1.jpg'),
(21, 'Samsung Galaxy Tab S8 WIFI', 'Xám', 30, 17990000, 'Samsung', '', 'https://cdn2.cellphones.com.vn/358x358,webp,q100 /media/catalog/product/t/a/tab_s8_2.jpg'),
(22, 'Samsung Galaxy Tab S8 Ultra 5G', 'Xám', 20, 30990000, 'Samsung', '', 'https://cdn2.cellphones.com.vn/358x358,webp, q100/media/catalog/product/t/a/tab_s8_ultra.jpg'),
(23, 'Xiaomi Pad 5 (6GB/256GB)', 'Xám', 10, 10490000, 'Xiaomi', '', 'https://cdn2.cellphones.com.vn/358x358, webp,q100/media/catalog/product/o/1/o1cn01ijop4f1slqk1fdzto_-2201438992231_1628774717_2.jpg'),
(24, 'Redmi Pad 3GB 64GB', 'Đen', 0, 6290000, 'Xiaomi', '', 'https://cdn2.cellphones.com.vn/358x358,webp,q100/ media/catalog/product/1/w/1w2kuj.jpg'),
(25, 'Đồng hồ thông minh Amazfit GTS 4 Mini', 'Đen', 0, 2590000, 'Amazfit', '', 'https://cdn2.cellphones.com.vn/358x358, webp,q100/media/catalog/product/g/t/gts-2.jpg'),
(26, 'Đồng hồ thông minh Amazfit GTS 4 Mini', 'Trắng', 0, 2590000, 'Amazfit', '', 'https://cdn2.cellphones.com.vn/358x358, webp,q100/media/catalog/product/g/t/gts-2.jpg'),
(27, 'Apple Watch SE 2022 40mm', 'Sliver', 15, 7490000, 'Apple', '', 'https://cdn2.cellphones.com.vn/358x358,webp,q100 /media/catalog/product/w/a/watch-se.jpg'),
(28, 'Samsung Galaxy S23 Ultra 256GB', 'Đen', 20, 41990990, 'Samsung', '', 'https://cdn2.cellphones.com.vn/358x358,webp,q100 /media/catalog/product/s/2/s23-ultra-tim.png'),
(29, 'Samsung Galaxy A34 5G 8GB 128GB', 'Đen', 10, 8490000, 'Samsung', '', 'https://cdn2.cellphones.com.vn/358x358,webp, q100/media/catalog/product/s/m/sm-a346_galaxy_a34_5g_awesome_silver_front.png'),
(30, 'Xiaomi Redmi Note 11 128GB', 'Đen', 10, 4990000, 'Xiaomi', 'HFHRY43.html', 'https://cdn2.cellphones.com.vn/358x358, webp,q100/media/catalog/product/8/0/800x800-640x640-5.png');

INSERT INTO `product_category` (`productID`, `categoryID`) VALUES 
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 2),
(14, 2),
(15, 2),
(16, 2),
(17, 2),
(18, 2),
(19, 3),
(20, 3),
(21, 3),
(22, 3),
(23, 3),
(24, 3),
(25, 4),
(26, 4),
(27, 4),
(28, 1),
(29, 1),
(30, 1);


INSERT INTO `attributes` (`id`, `name`, `group_name`) VALUES
(1, 'Dung lượng Ram', 'Ram & lưu trữ'),
(2, 'Bộ nhớ trong', 'Ram & lưu trữ'),
(3, 'Kích thước màn hình', 'Màn hình'),
(4, 'Công nghệ màn hình', 'Màn hình'),
(5, 'Độ phân giải màn hình', 'Màn hình'),
(6, 'Tính năng màn hình', 'Màn hình'),
(7, 'Tần số quét', 'Màn hình'),
(8, 'Camera sau', 'Camera sau'),
(9, 'Quay video', 'Camera sau'),
(10, 'Tính năng camera', 'Camera sau'),
(11, 'Camera trước', 'Camera trước'),
(12, 'Quay video trước', 'Camera trước'),
(13, 'Chipset', 'Vi xử lý & đồ họa '),
(14, 'Loại CPU', 'Vi xử lý & đồ họa '),
(15, 'GPU', 'Vi xử lý & đồ họa '),
(16, 'Pin', 'Pin & công nghệ sạc'),
(17, 'Công nghệ sạc', 'Pin & công nghệ sạc'),
(18, 'Cổng sạc', 'Pin & công nghệ sạc'),
(19, 'Thẻ SIM', 'Giao tiếp & kết nối'),
(20, 'Hệ điều hành', 'Giao tiếp & kết nối'),
(21, 'Công nghệ NFC', 'Giao tiếp & kết nối'),
(22, 'Hỗ trợ mạng', 'Giao tiếp & kết nối'),
(23, 'Wi-Fi', 'Giao tiếp & kết nối'),
(24, 'Bluetooth', 'Giao tiếp & kết nối'),
(25, 'GPS', 'Giao tiếp & kết nối'),
(26, 'Kích thước', 'Thiết kế & Trọng lượng'),
(27, 'Trọng lượng', 'Thiết kế & Trọng lượng'),
(28, 'Chất liệu mặt lưng', 'Thiết kế & Trọng lượng'),
(29, 'Tương thích', 'Thông số khác'),
(30, 'Chỉ số kháng nước, bụi', 'Thông số khác'),
(31, 'Tiện ích khác', 'Thông số khác'),
(32, 'Kiểu màn hình', 'Thông số khác'),
(33, 'Cảm biến vân tay', 'Tiện ích khác'),
(34, 'Các loại cảm biến', 'Tiện ích khác'),
(35, 'Tính năng đặc biệt', 'Tiện ích khác'),
(36, 'Chất liệu mặt kính', 'Thiết kế & Trọng lượng'),
(37, 'Hình ảnh', 'Hình ảnh'),
(38, 'Loại card đồ họa', 'Vi xử lý & đồ họa'),
(39, 'Ổ cứng', 'Ram & lưu trữ'),
(40, 'Loại RAM', 'Ram & lưu trữ'),
(41, 'Chất liệu', 'Thông số khác'),
(42, 'Màn hình cảm ứng', 'Màn hình'),
(43, 'Chất lượng tấm nền', 'Màn hình'),
(44, 'Công nghệ âm thanh', 'Công nghệ âm thanh'),
(45, 'Khe đọc thẻ nhớ', 'Giao tiếp & kết nối'),
(47, 'Cổng giao tiếp', 'Thông số kỹ thuật'),
(48, 'Jack tai nghe 3.5', 'Giao tiếp & kết nối'),
(49, 'Chất liệu khung viền', 'Thiết kế & Trọng lượng'),
(50, 'Chống nước', 'Thông số khác'),
(51, 'Tiện ích sức khoẻ', 'Thông số khác'),
(52, 'Thời lượng pin', 'Pin & công nghệ sạc'),
(53, 'Tính năng thông minh', 'Tính năng nổi bật'),
(54, 'Chất liệu viền', 'Chất liệu mặt & Dây'),
(55, 'Chất liệu dây', 'Chất liệu mặt & Dây');

INSERT INTO `attribute_value` (`id`, `id_attribute`, `value`, `productId`) VALUES
(1, 1, '6 GB', 1),
(2, 2, '128 GB', 1),
(3, 3, '6.9 inches', 1),
(4, 8, 'Camera góc rộng: 12MP, f/1.6\nCamera góc siêu rộng: 12MP, ƒ/2.4', 1),
(5, 5, '2532 x 1170 pixels', 1),
(6, 6, 'Màn hình super Retina XDR, OLED, 460 ppi, HDR display, công nghệ True Tone Wide color (P3), Haptic Touch, Lớp phủ oleophobic chống bám vân tay', 1),
(7, 7, '60Hz', 1),
(13, 9, '4K 2160p@30fps\r\nFullHD 1080p@30fps\r\nFullHD 1080p@60fps\r\nHD 720p@30fps', 1),
(14, 10, 'Chạm lấy nét\r\nHDR\r\nNhận diện khuôn mặt\r\nQuay chậm (Slow Motion)\r\nToàn cảnh (Panorama)\r\nTự động lấy nét (AF)\r\nXóa phông\r\nNhãn dán (AR Stickers)\r\nNhận diện khuôn mặt', 1),
(15, 11, '12MP, f/2.2', 1),
(16, 13, 'Apple A15', 1),
(17, 16, '3.240mAh', 1),
(18, 17, 'Sạc nhanh 20W, Sạc không dây, Sạc ngược không dây 15W, Sạc pin nhanh, Tiết kiệm pin', 1),
(19, 18, 'Lightning', 1),
(20, 20, 'iOS 15', 1),
(21, 48, 'Không', 1),
(22, 21, 'Có', 1),
(23, 22, '5G', 1),
(24, 23, 'Wi‑Fi 6 (802.11ax)', 1),
(25, 24, 'v5.0', 1),
(26, 15, 'GPS, GLONASS, Galileo, QZSS, and BeiDou', 1),
(27, 26, '146,7 x 71,5 x 7,65mm', 1),
(28, 27, '174g', 1),
(29, 36, 'Kính', 1),
(30, 49, 'Kim loại', 1),
(31, 32, 'Tai thỏ', 1),
(32, 35, 'Hỗ trợ 5G, Sạc không dây, Nhận diện khuôn mặt, Kháng nước, kháng bụi', 1),
(33, 37, '1_main.png', 1),
(34, 37, '1_43534534.png', 1);
