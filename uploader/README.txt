CREATE DATABASE mf_img;

CREATE TABLE `icon` (
  `user_id` varchar(15) NOT NULL,
  `icon_ext` varchar(4) DEFAULT NULL,
  `icon_name` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `imgs` (
  `img_id` int(11) NOT NULL,
  `img_d` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `icon`
  ADD PRIMARY KEY (`user_id`);

ALTER TABLE `imgs`
  ADD PRIMARY KEY (`img_id`);

ALTER TABLE `imgs`
  MODIFY `img_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT;