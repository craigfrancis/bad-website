
DROP TABLE IF EXISTS user;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` tinytext NOT NULL,
  `password` tinytext NOT NULL,
  `admin` enum('false','true') NOT NULL,
  `picture_url` tinytext NOT NULL,
  PRIMARY KEY (id)
) ;

INSERT INTO user VALUES (1, 'admin', 'password', 'true', '');
INSERT INTO user VALUES (13, 'made-you-look', 'hahaha', 'false', 'https://www.krang.org.uk/misc/scientist-100.jpg');
INSERT INTO user VALUES (666, 'evil', '666', 'false', '/security/uploads/profile-pictures/666.js');
INSERT INTO user VALUES (726, 'craig', 'password', 'false', '/security/uploads/profile-pictures/726.jpg');
INSERT INTO user VALUES (727, 'amy', 'yiecMK7eznbTwFuzDJEZXsYsycWaxX', 'false', '/security/uploads/profile-pictures/727.jpg');
