<?php
$serverName='localhost';
$username='jaan';
$password='123456';
$database='jaan';
$connection=new mysqli($serverName,$username,$password,$database);
$connection->set_charset('UTF8');
/*CREATE TABLE loomad(
	id int PRIMARY KEY AUTO_INCREMENT,
    nimi varchar(50),
    kirjeldus text
);
INSERT INTO loomad(nimi, kirjeldus) VALUES('kass','väga hea sõber');

SELECT * from loomad;*/
