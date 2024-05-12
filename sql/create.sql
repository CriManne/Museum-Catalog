-- DROP DATABASE IF EXISTS museum;

-- CREATE DATABASE museum;

-- USE museum;

CREATE TABLE GenericObject(
    id VARCHAR(20) NOT NULL,
    note TEXT,
    url VARCHAR(100),
    tag VARCHAR(300),
    PRIMARY KEY (id)
);

-- COMPUTER

CREATE TABLE Cpu(
    id INTEGER NOT NULL AUTO_INCREMENT,
    modelName VARCHAR(100) NOT NULL,
    speed VARCHAR(20) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE Ram(
    id INTEGER NOT NULL AUTO_INCREMENT,
    modelName VARCHAR(100) NOT NULL,
    size VARCHAR(20) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE Os(
    id INTEGER NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    PRIMARY KEY (id)
);

CREATE TABLE Computer(
    objectId VARCHAR(20) NOT NULL,
    modelName VARCHAR(100) NOT NULL,
    year INTEGER NOT NULL,
    cpuId INTEGER NOT NULL,
    ramId INTEGER NOT NULL,
    hddSize VARCHAR(20) NULL,
    osId INTEGER NULL,
    PRIMARY KEY (objectId),
    FOREIGN KEY (objectId) REFERENCES GenericObject(id),
    FOREIGN KEY (cpuId) REFERENCES Cpu(id),
    FOREIGN KEY (ramId) REFERENCES Ram(id),
    FOREIGN KEY (osId) REFERENCES Os(id)
);

-- /COMPUTER

-- PERIPHERAL

CREATE TABLE PeripheralType(
    id INTEGER NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    PRIMARY KEY (id)
);

CREATE TABLE Peripheral(
    objectId VARCHAR(20) NOT NULL,
    modelName VARCHAR(100) NOT NULL,
    peripheralTypeId INTEGER NOT NULL,
    PRIMARY KEY (objectId),
    FOREIGN KEY (objectId) REFERENCES GenericObject(id),
    FOREIGN KEY (peripheralTypeId) REFERENCES PeripheralType(id)
);

-- /PERIPHERAL

-- BOOK & MAGAZINE

CREATE TABLE Publisher(
    id INTEGER NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    PRIMARY KEY (id)
);

CREATE TABLE Book(
    objectId VARCHAR(20) NOT NULL,
    title VARCHAR(100) NOT NULL,
    publisherId INTEGER NOT NULL,
    year INTEGER NOT NULL,
    pages INTEGER NULL,
    isbn VARCHAR(13) NULL,
    PRIMARY KEY (objectId),
    FOREIGN KEY (objectId) REFERENCES GenericObject(id),
    FOREIGN KEY (publisherId) REFERENCES Publisher(id)
);

CREATE TABLE Author(
    id INTEGER NOT NULL AUTO_INCREMENT,
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE BookHasAuthor(
    id INTEGER NOT NULL AUTO_INCREMENT,
    bookId VARCHAR(20) NOT NULL,
    authorId INTEGER NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT UNIQUE(bookId, authorId),
    FOREIGN KEY (bookId) REFERENCES Book(objectId),
    FOREIGN KEY (authorId) REFERENCES Author(id)
);

CREATE TABLE Magazine(
    objectId VARCHAR(20) NOT NULL,
    title VARCHAR(100) NOT NULL,
    magazineNumber INTEGER NOT NULL,
    year INTEGER NOT NULL,
    publisherId INTEGER NOT NULL,
    PRIMARY KEY (objectId),
    FOREIGN KEY (objectId) REFERENCES GenericObject(id),
    FOREIGN KEY (publisherId) REFERENCES Publisher(id)
);

-- / BOOK & MAGAZINE

-- SOFTWARE

CREATE TABLE SoftwareType(
    id INTEGER NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    PRIMARY KEY (id)
);

CREATE TABLE SupportType(
    id INTEGER NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    PRIMARY KEY (id)
);

CREATE TABLE Software(
    objectId VARCHAR(20) NOT NULL,
    title VARCHAR(100) NOT NULL,
    osId INTEGER NOT NULL,
    softwareTypeId INTEGER NOT NULL,
    supportTypeId INTEGER NOT NULL,
    PRIMARY KEY (objectId),
    FOREIGN KEY (objectId) REFERENCES GenericObject(id),
    FOREIGN KEY (osId) REFERENCES Os(id),
    FOREIGN KEY (softwareTypeId) REFERENCES SoftwareType(id),
    FOREIGN KEY (supportTypeId) REFERENCES SupportType(id)
);


-- /SOFTWARE

-- User

CREATE TABLE User(
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    privilege SMALLINT DEFAULT 0 NOT NULL, -- employee = 0, admin = 1
    PRIMARY KEY (email)
);