<h1>Bước 1</h1>
Chỉnh documentRoot của apache trong xamp thành folder backend: 
XAMP -> Trên dòng Apache chọn Config -> Apache(httpd.conf) -> "Ctrl + F" gõ "DocumentRoot" -> copy link thư mục backend vào đây

<h1>Bước 2</h1>
Chạy file db.sql trong phpMyADmin

<h1>Bước 3</h1>
Vào thư mục "backend/src" copy thư mục "api" sang "frontend/src" -> import module vào file app.js -> use api

<h1>Bước 4</h1>
Cài thư viện axios ở frontend:  npm i axios

<h1>Bước 5</h1>
Bên Backend:
Cài Composer nếu chưa cài (để check composer đã cài hay chưa: vào terminal gõ "composer"): https://www.geeksforgeeks.org/how-to-install-php-composer-on-windows/
Sau đó mở terminal đến Backend: Chạy lệnh "composer install" ở terminal để load thư viện


Note:
Cách upload Multifile để đồng bộ với Backend: https://www.php.net/manual/en/features.file-upload.multiple.php 
