use master
go
IF EXISTS (SELECT * FROM sys.databases WHERE name = 'bookstore_php')
BEGIN
    DROP DATABASE bookstore_php;
    PRINT 'Database bookstore đã bị xóa.';
END
--tạo db
CREATE DATABASE bookstore_php;
GO
USE bookstore_php;
GO
--tạo bảng
CREATE TABLE books (
    id INT IDENTITY(1,1) PRIMARY KEY,
    title NVARCHAR(255) NOT NULL,
    author NVARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    published_year INT NOT NULL
);
