-- Create the database
CREATE DATABASE IF NOT EXISTS library_db;
USE library_db;

-- Create Categories table
CREATE TABLE IF NOT EXISTS Categories (
    CategoryID VARCHAR(3) PRIMARY KEY,
    CategoryDescription VARCHAR(50) NOT NULL
);

-- Create Users table
CREATE TABLE IF NOT EXISTS Users (
    Username VARCHAR(50) PRIMARY KEY,
    Password VARCHAR(255) NOT NULL,
    FirstName VARCHAR(50) NOT NULL,
    Surname VARCHAR(50) NOT NULL,
    AddressLine1 VARCHAR(100) NOT NULL,
    AddressLine2 VARCHAR(100),
    City VARCHAR(50) NOT NULL,
    Telephone VARCHAR(20),
    Mobile VARCHAR(10) NOT NULL
);

-- Create Books table
CREATE TABLE IF NOT EXISTS Books (
    ISBN VARCHAR(20) PRIMARY KEY,
    BookTitle VARCHAR(255) NOT NULL,
    Author VARCHAR(100) NOT NULL,
    Edition INT,
    Year INT,
    CategoryID VARCHAR(3),
    Reserved ENUM('Y', 'N') DEFAULT 'N',
    FOREIGN KEY (CategoryID) REFERENCES Categories(CategoryID)
);

-- Create Reservations table
CREATE TABLE IF NOT EXISTS Reservations (
    ISBN VARCHAR(20),
    Username VARCHAR(50),
    ReservedDate DATE NOT NULL,
    PRIMARY KEY (ISBN, Username),
    FOREIGN KEY (ISBN) REFERENCES Books(ISBN),
    FOREIGN KEY (Username) REFERENCES Users(Username)
);

-- Insert sample data into Categories table
INSERT INTO Categories (CategoryID, CategoryDescription) VALUES
('001', 'Health'),
('002', 'Business'),
('003', 'Biography'),
('004', 'Technology'),
('005', 'Travel'),
('006', 'Self-Help'),
('007', 'Cookery'),
('008', 'Fiction');

-- Insert sample data into Users table (ADD THIS SECTION)
INSERT INTO Users (Username, Password, FirstName, Surname, AddressLine1, City, Mobile) VALUES
('alanjmckenna', '112345', 'Alan', 'McKenna', '38 Gralley Road Fairview', 'Dublin', '85662567'),
('joecrotty', 'kj7899', 'Joseph', 'Crotty', 'Apt 5 Clyde Ro Donnybrook', 'Dublin', '876654456'),
('tommy100', '123456', 'Tom', 'Behan', '14 hyle road dalkey', 'Dublin', '876738782');

-- Insert sample data into Books table
INSERT INTO Books (ISBN, BookTitle, Author, Edition, Year, CategoryID, Reserved) VALUES
('093-403992', 'Computers in Business', 'Alicia Oneill', 3, 1997, '003', 'N'),
('23472-8729', 'Exploring Peru', 'Stephanie Birchl', 4, 2005, '005', 'N'),
('237-34823', 'Business Strategy', 'Joe Peppard', 2, 2002, '002', 'N'),
('2318-923849', 'A guide to nutrition', 'John Thorpe', 2, 1997, '001', 'N'),
('2983-3494', 'Cooking for children', 'Anabelle Sharpe', 1, 2003, '007', 'N'),
('82nB-308', 'computers for idiots', 'Susan O''Neill', 5, 1998, '004', 'N'),
('9823-23984', 'My life in picture', 'Kevin Graham', 8, 2004, '001', 'N'),
('9823-2403-0', 'DaVinci Code', 'Dan Brown', 1, 2003, '008', 'N'),
('98234-029384', 'My ranch in Texas', 'George Bush', 1, 2005, '001', 'Y'),
('9823-98345', 'How to cook Italian food', 'Jamie Oliver', 2, 2005, '007', 'Y'),
('9823-98487', 'Optimising your business', 'Cleo Blair', 1, 2001, '002', 'N'),
('988745-234', 'Tara Road', 'Maeve Binchy', 4, 2002, '008', 'N'),
('993-004-00', 'My life in bits', 'John Smith', 1, 2001, '001', 'N'),
('9987-0039882', 'Shooting History', 'Jon Snow', 1, 2003, '001', 'N');

-- Insert sample reservations (NOW THIS WILL WORK)
INSERT INTO Reservations (ISBN, Username, ReservedDate) VALUES
('98234-029384', 'joecrotty', '2008-10-11'),
('9823-98345', 'tommy100', '2008-10-11');