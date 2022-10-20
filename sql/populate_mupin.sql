-- POPULATE GENERICOBJECT

INSERT INTO genericobject(ObjectID,Note,Url,Tag) VALUES
("OBJ1","Computer portatile",null,"Computer 2018 nuovo veloce ufficio"),
("OBJ2","Microsoft Word",null,"Software Word documenti Microsoft Ufficio"),
("OBJ3","Test Driven Development",null,"Libro programmazione tdd test driven development guida"),
("OBJ4","Rivista programmazione",null,"Rivista programmazione"),
("OBJ5","Schermo ASUS LED 24",null,"schermo asus monitor led 24 pc computer"),
("OBJ6","Macintosh 128K",null,"Computer Macintosh 128K 1984"),
("OBJ7","Macbook Air 13''",'https://www.apple.com/it/shop/buy-mac/macbook-air',"Computer Apple Macbook Mac 13 Laptop Portatile"),
("OBJ9","Visual Studio Code",null,"Software programmazione ide vs vscode code studio"),
("OBJ10","Git",null,"Software versionamento repository commit push"),
("OBJ11","Refactoring",null,"Libro programmazione refactoring guida"),
("OBJ12","C++",null,"Libro programmazione c++ c");
-- POPULATE AUTHOR

INSERT INTO author(firstname,lastname) VALUES
("Mario","Rossi"),
("Luca","Verdi"),
("Sandro","Gialli");

-- POPULATE PUBLISHER

INSERT INTO publisher(Name) VALUES
("Einaudi"),
("Mondadori"),
("Zanichelli");

-- POPULATE BOOK

INSERT INTO book(ObjectID,Title,PublisherID,Year,Pages,ISBN) VALUES
("OBJ3","Test Driven Development",1,1998,405,"ABCDEFG"),
("OBJ11","Refactoring",1,1998,405,"ABCDEFG"),
("OBJ12","C++",1,1998,405,"ABCDEFG");

-- POPULATE BOOK AUTHOR
INSERT INTO bookauthor(BookID,AuthorID) VALUES
("OBJ3",1);

-- POPULATE CPU
INSERT INTO cpu(ModelName,Speed) VALUES
("I5","2GHZ"),
("I7","3.5GHZ"),
("AMD Ryzen 5","4.2GHZ"),
("I9","4GHZ");

-- POPULATE RAM
INSERT INTO ram(ModelName,Size) VALUES
("Corsair Vengeance","16GB"),
("Asus ROG","8GB"),
("Kingston","8GB");

-- POPULATE OS
INSERT INTO os(Name) VALUES
("Windows 10"),
("Linux"),
("MacOS");

-- POPULATE COMPUTER
INSERT INTO computer(ObjectID,ModelName,Year,CpuID,RamID,HddSize,OsID) VALUES
("OBJ1","Computer portatile ultima generazione",2018,1,1,"1TB",1),
("OBJ6","Macintosh 128K",1984,1,1,"1TB",1),
("OBJ7","Macbook Air 13''",2020,1,1,"1TB",1);

-- POPULATE MAGAZINE
INSERT INTO magazine(ObjectID,Title,MagazineNumber,Year,PublisherID) VALUES
("OBJ4","Rivista interessante",31,1999,1);

-- POPULATE PERIPHERAL TYPE
INSERT INTO peripheraltype(Name) VALUES
("Mouse"),
("Keyboard"),
("Display");

-- POPULATE PERIPHERAL
INSERT INTO peripheral(ObjectID,ModelName,PeripheralTypeID) VALUES
("OBJ5","Schermo hdr",3);

-- POPULATE SOFTWARE TYPE
INSERT INTO softwaretype(Name) VALUES
("Office"),
("Cloud"),
("Utility");

-- POPULATE SUPPORT TYPE
INSERT INTO supporttype(Name) VALUES
("CD"),
("DVD"),
("Online");

-- POPULATE SOFTWARE
INSERT INTO software(ObjectID,Title,OsID,SoftwareTypeID,SupportTypeID) VALUES
("OBJ2","Microsoft Word",1,1,1),
("OBJ9","Visual Studio Code",1,1,1),
("OBJ10","Git",1,1,1);

-- POPULATE USER

INSERT INTO user(Email,Password,firstname,lastname,Privilege) VALUES
('admin','$2y$11$McH3cxMJ1J/R1rj4DH4B8uxWIYJAdD2JuMWKGI0EwypAgqqbPO9dO','Cristian','Rossi',1),
('test','$2y$11$McH3cxMJ1J/R1rj4DH4B8uxWIYJAdD2JuMWKGI0EwypAgqqbPO9dO','Test','Test',0),
('test1','$2y$11$McH3cxMJ1J/R1rj4DH4B8uxWIYJAdD2JuMWKGI0EwypAgqqbPO9dO','Test','Test',0),
('test2','$2y$11$McH3cxMJ1J/R1rj4DH4B8uxWIYJAdD2JuMWKGI0EwypAgqqbPO9dO','Test','Test',0),
('test3','$2y$11$McH3cxMJ1J/R1rj4DH4B8uxWIYJAdD2JuMWKGI0EwypAgqqbPO9dO','Test','Test',0),
('test4','$2y$11$McH3cxMJ1J/R1rj4DH4B8uxWIYJAdD2JuMWKGI0EwypAgqqbPO9dO','Test','Test',0),
('test5','$2y$11$McH3cxMJ1J/R1rj4DH4B8uxWIYJAdD2JuMWKGI0EwypAgqqbPO9dO','Test','Test',0),
('test6','$2y$11$McH3cxMJ1J/R1rj4DH4B8uxWIYJAdD2JuMWKGI0EwypAgqqbPO9dO','Test','Test',0),
('test7','$2y$11$McH3cxMJ1J/R1rj4DH4B8uxWIYJAdD2JuMWKGI0EwypAgqqbPO9dO','Test','Test',0),
('test8','$2y$11$McH3cxMJ1J/R1rj4DH4B8uxWIYJAdD2JuMWKGI0EwypAgqqbPO9dO','Test','Test',0),
('test9','$2y$11$McH3cxMJ1J/R1rj4DH4B8uxWIYJAdD2JuMWKGI0EwypAgqqbPO9dO','Test','Test',0),
('test10','$2y$11$McH3cxMJ1J/R1rj4DH4B8uxWIYJAdD2JuMWKGI0EwypAgqqbPO9dO','Test','Test',0);
