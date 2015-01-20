/* Create database */
CREATE DATABASE `a3k` /*!40100 DEFAULT CHARACTER SET latin1 */;

/* create color table */
CREATE  TABLE `a3k`.`colors` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(20) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) );

/* create city table */
CREATE  TABLE `a3k`.`cities` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) );

/*create votes table */
CREATE  TABLE `a3k`.`votes` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `color_id` INT NOT NULL ,
  `city_id` INT NOT NULL ,
  `votes` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  INDEX `fk_votes_city` (`city_id` ASC) ,
  INDEX `fk_votes_color` (`color_id` ASC) ,
  CONSTRAINT `fk_votes_city`
    FOREIGN KEY (`city_id` )
    REFERENCES `a3k`.`cities` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_votes_color`
    FOREIGN KEY (`color_id` )
    REFERENCES `a3k`.`colors` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

/* insert data */
INSERT INTO `a3k`.`cities` (`id`, `name`) VALUES (1, 'Anchorage');
INSERT INTO `a3k`.`cities` (`id`, `name`) VALUES (2, 'Brooklyn');
INSERT INTO `a3k`.`cities` (`id`, `name`) VALUES (3, 'Detroit');
INSERT INTO `a3k`.`cities` (`id`, `name`) VALUES (4, 'Selma');

INSERT INTO `a3k`.`colors` (`id`, `name`) VALUES (1, 'Red');
INSERT INTO `a3k`.`colors` (`id`, `name`) VALUES (2, 'Orange');
INSERT INTO `a3k`.`colors` (`id`, `name`) VALUES (3, 'Yellow');
INSERT INTO `a3k`.`colors` (`id`, `name`) VALUES (4, 'Green');
INSERT INTO `a3k`.`colors` (`id`, `name`) VALUES (5, 'Blue');
INSERT INTO `a3k`.`colors` (`id`, `name`) VALUES (6, 'Indigo');
INSERT INTO `a3k`.`colors` (`id`, `name`) VALUES (7, 'Violet');

INSERT INTO `a3k`.`votes` (`id`, `color_id`, `city_id`, `votes`) VALUES (1, 5, 1, 10000);
INSERT INTO `a3k`.`votes` (`id`, `color_id`, `city_id`, `votes`) VALUES (2, 3, 1, 15000);
INSERT INTO `a3k`.`votes` (`id`, `color_id`, `city_id`, `votes`) VALUES (3, 1, 2, 100000);
INSERT INTO `a3k`.`votes` (`id`, `color_id`, `city_id`, `votes`) VALUES (4, 5, 2, 250000);
INSERT INTO `a3k`.`votes` (`id`, `color_id`, `city_id`, `votes`) VALUES (5, 1, 3, 160000);
INSERT INTO `a3k`.`votes` (`id`, `color_id`, `city_id`, `votes`) VALUES (6, 3, 4, 15000);
INSERT INTO `a3k`.`votes` (`id`, `color_id`, `city_id`, `votes`) VALUES (7, 7, 4, 5000);

