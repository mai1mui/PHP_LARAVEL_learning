use master
go
IF EXISTS (SELECT * FROM sys.databases WHERE name = 'patientdb')
BEGIN
    DROP DATABASE patientdb;
    PRINT 'Database  đã bị xóa.';
END
--tạo db
CREATE DATABASE patientdb;
GO
USE patientdb;
GO
--tạo bảng
CREATE TABLE patient (
    id INT IDENTITY(1,1) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    age INT,
    email VARCHAR(255) NOT NULL
);
