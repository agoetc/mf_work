CREATE DATABASE mf_img;

CREATE TABLE `icon` (
 `user_id` varchar(15) NOT NULL,
 `icon_ext` varchar(4) DEFAULT NULL,
 `icon_name` varchar(25) NOT NULL,
 PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

CREATE TABLE `imgs` (
 `img_id` int(11) NOT NULL AUTO_INCREMENT,
 `img_d` date DEFAULT NULL,
 PRIMARY KEY (`img_id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8
