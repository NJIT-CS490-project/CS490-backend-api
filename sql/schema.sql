DROP TABLE IF EXISTS `Event`; -- : User
DROP TABLE IF EXISTS `Session`; -- : User
DROP TABLE IF EXISTS `User`;

CREATE TABLE IF NOT EXISTS `User` (
	`id`       BIGINT       NOT NULL AUTO_INCREMENT,
	`username` VARCHAR(255) NOT NULL,
	`password` VARCHAR(255) NOT NULL,
	`created`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,

	PRIMARY KEY (`id`),
	UNIQUE KEY (`username`)
);

CREATE TABLE IF NOT EXISTS `Session` ( -- : User
	`id`         VARCHAR(255) NOT NULL,
	`ownerID`    BIGINT       NOT NULL, -- : User.userID
	`created`    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`expiration` TIMESTAMP,
	`active`     TINYINT(1)   NOT NULL DEFAULT 1,

	PRIMARY KEY (`id`),

	CONSTRAINT `Session_fk_ownerID`
		FOREIGN KEY (`ownerID`)
		REFERENCES `User` (`userID`)
		ON UPDATE CASCADE
		ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `Event` (
	`id`        BIGINT       NOT NULL AUTO_INCREMENT,
	`ownerID`   BIGINT       NOT NULL,
	`name`      VARCHAR(255) NOT NULL,
	`timeStart` TIMESTAMP    NOT NULL,
	`timeEnd`   TIMESTAMP    NOT NULL,
	`location`  VARCHAR(255) NOT NULL,

	CONSTRAINT `Event_pk`
		PRIMARY KEY (`id`),

	CONSTRAINT `Event_fk_ownerID`
		FORIEGN KEY `ownerID`
		REFERENCES `User`.`id`
		ON UPDATE CASCADE
		ON DELETE CASCADE
);

