-- POPULATE GENERICOBJECT

INSERT INTO genericobject(ObjectID,Note,Url,Tag) VALUES
("OBJ1","Computer del 1945",null,"Computer 1945 antico"),
("OBJ2","Software del 1956 sulla gestione dei computer",null,"Software computer 1956 antico"),
("OBJ3","Libro sulla TDD",null,"Libro programmazione tdd"),
("OBJ4","Rivista programmazione",null,null),
("OBJ5","Scherm",null,null);

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
("OBJ3","TDD Dev",1,1998,405,"ABCDEFG");

-- POPULATE BOOK AUTHOR
INSERT INTO bookauthor(BookID,AuthorID) VALUES
("OBJ3",1);

-- POPULATE CPU
INSERT INTO cpu(ModelName,Speed) VALUES
("I5","4GHZ"),
("I7","6GHZ"),
("I9","8GHZ");

-- POPULATE RAM
INSERT INTO ram(ModelName,Size) VALUES
("Veng","16GB"),
("Cool","32GB"),
("Micro","4GB");

-- POPULATE OS
INSERT INTO os(Name) VALUES
("Windows 10"),
("Linux"),
("IOS");

-- POPULATE COMPUTER
INSERT INTO computer(ObjectID,ModelName,Year,CpuID,RamID,HddSize,OsID) VALUES
("OBJ1","Computer antico della guerra",1945,1,1,"1TB",1);

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
("OBJ2","Software gestione dei computer",1,1,1);

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
