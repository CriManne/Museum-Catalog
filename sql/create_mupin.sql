-- DROP DATABASE IF EXISTS mupin;

-- CREATE DATABASE mupin;

-- USE mupin;

CREATE TABLE GenericObject(
    ObjectID VARCHAR(20) NOT NULL,
    Note TEXT,
    Url VARCHAR(100),
    Tag VARCHAR(300),
    PRIMARY KEY (ObjectID)
);

-- COMPUTER

CREATE TABLE Cpu(
    CpuID INTEGER NOT NULL AUTO_INCREMENT,
    ModelName VARCHAR(100) NOT NULL,
    Speed VARCHAR(20) NOT NULL,
    PRIMARY KEY (CpuID)
);

CREATE TABLE Ram(
    RamID INTEGER NOT NULL AUTO_INCREMENT,
    ModelName VARCHAR(100) NOT NULL,
    Size VARCHAR(20) NOT NULL,
    PRIMARY KEY (RamID)
);

CREATE TABLE Os(
    OsID INTEGER NOT NULL AUTO_INCREMENT,
    Name VARCHAR(100) NOT NULL UNIQUE,
    PRIMARY KEY (OsID)
);

CREATE TABLE Computer(
    ObjectID VARCHAR(20) NOT NULL,
    ModelName VARCHAR(100) NOT NULL,
    Year INTEGER NOT NULL,
    CpuID INTEGER NOT NULL,
    RamID INTEGER NOT NULL,
    HddSize VARCHAR(20) NULL,
    OsID INTEGER NULL,
    PRIMARY KEY (ObjectID),
    FOREIGN KEY (ObjectID) REFERENCES GenericObject(ObjectID),
    FOREIGN KEY (CpuID) REFERENCES Cpu(CpuID),
    FOREIGN KEY (RamID) REFERENCES Ram(RamID),
    FOREIGN KEY (OsID) REFERENCES Os(OsID)    
);

-- /COMPUTER

-- PERIPHERAL

CREATE TABLE PeripheralType(
    PeripheralTypeID INTEGER NOT NULL AUTO_INCREMENT,
    Name VARCHAR(100) NOT NULL UNIQUE,
    PRIMARY KEY (PeripheralTypeID)
);

CREATE TABLE Peripheral(
    ObjectID VARCHAR(20) NOT NULL,
    ModelName VARCHAR(100) NOT NULL,    
    PeripheralTypeID INTEGER NOT NULL,
    PRIMARY KEY (ObjectID),
    FOREIGN KEY (ObjectID) REFERENCES GenericObject(ObjectID),
    FOREIGN KEY (PeripheralTypeID) REFERENCES PeripheralType(PeripheralTypeID)
);

-- /PERIPHERAL

-- BOOK & MAGAZINE

CREATE TABLE Publisher(
    PublisherID INTEGER NOT NULL AUTO_INCREMENT,
    Name VARCHAR(100) NOT NULL UNIQUE,
    PRIMARY KEY (PublisherID)
);

CREATE TABLE Book(
    ObjectID VARCHAR(20) NOT NULL,
    Title VARCHAR(100) NOT NULL,
    PublisherID INTEGER NOT NULL,
    Year INTEGER NOT NULL,
    Pages INTEGER NULL,
    ISBN VARCHAR(13) NULL, 
    PRIMARY KEY (ObjectID),
    FOREIGN KEY (ObjectID) REFERENCES GenericObject(ObjectID),
    FOREIGN KEY (PublisherID) REFERENCES Publisher(PublisherID)
);

CREATE TABLE Author(
    AuthorID INTEGER NOT NULL AUTO_INCREMENT,
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    PRIMARY KEY (AuthorID)
);

CREATE TABLE BookAuthor(
    BookID VARCHAR(20) NOT NULL,
    AuthorID INTEGER NOT NULL,    
    PRIMARY KEY (BookID, AuthorID),
    FOREIGN KEY (BookID) REFERENCES Book(ObjectID),
    FOREIGN KEY (AuthorID) REFERENCES Author(AuthorID)
);

CREATE TABLE Magazine(
    ObjectID VARCHAR(20) NOT NULL,
    Title VARCHAR(100) NOT NULL,
    MagazineNumber INTEGER NOT NULL,
    Year INTEGER NOT NULL,  
    PublisherID INTEGER NOT NULL,
    PRIMARY KEY (ObjectID),
    FOREIGN KEY (ObjectID) REFERENCES GenericObject(ObjectID),
    FOREIGN KEY (PublisherID) REFERENCES Publisher(PublisherID)
);

-- / BOOK & MAGAZINE

-- SOFTWARE

CREATE TABLE SoftwareType(
    SoftwareTypeID INTEGER NOT NULL AUTO_INCREMENT,
    Name VARCHAR(100) NOT NULL UNIQUE,
    PRIMARY KEY (SoftwareTypeID)
);

CREATE TABLE SupportType(
    SupportTypeID INTEGER NOT NULL AUTO_INCREMENT,
    Name VARCHAR(100) NOT NULL UNIQUE,
    PRIMARY KEY (SupportTypeID)
);

CREATE TABLE Software(
    ObjectID VARCHAR(20) NOT NULL,
    Title VARCHAR(100) NOT NULL,
    OsID INTEGER NOT NULL,
    SoftwareTypeID INTEGER NOT NULL,
    SupportTypeID INTEGER NOT NULL,
    PRIMARY KEY (ObjectID),
    FOREIGN KEY (ObjectID) REFERENCES GenericObject(ObjectID),
    FOREIGN KEY (OsID) REFERENCES Os(OsID),
    FOREIGN KEY (SoftwareTypeID) REFERENCES SoftwareType(SoftwareTypeID),
    FOREIGN KEY (SupportTypeID) REFERENCES SupportType(SupportTypeID)
);


-- /SOFTWARE

-- User

CREATE TABLE User(
    Email VARCHAR(100) NOT NULL,
    Password VARCHAR(100) NOT NULL,
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    Privilege SMALLINT NOT NULL, -- employee = 0, admin = 1
    PRIMARY KEY (Email)
);