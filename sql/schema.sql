DROP TABLE IF EXISTS `EventFavorites`; -- : Event, User
DROP TABLE IF EXISTS `Event`; -- : User
DROP TABLE IF EXISTS `Session`; -- : User
DROP TABLE IF EXISTS `User`;

CREATE TABLE IF NOT EXISTS `User` (
	`id`       INT          NOT NULL AUTO_INCREMENT,
	`username` VARCHAR(255) NOT NULL,
	`password` VARCHAR(255) NOT NULL,
	`created`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`admin`    TINYINT(1)   NOT NULL DEFAULT 0,

	PRIMARY KEY (`id`),
	UNIQUE KEY (`username`)
);

CREATE TABLE IF NOT EXISTS `Session` ( -- : User
	`id`         VARCHAR(255) NOT NULL,
	`ownerID`    INT          NOT NULL, -- : User.id
	`created`    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`expiration` TIMESTAMP,
	`active`     TINYINT(1)   NOT NULL DEFAULT 1,

	PRIMARY KEY (`id`),

	CONSTRAINT `Session_fk_ownerID`
		FOREIGN KEY (`ownerID`)
		REFERENCES `User` (`id`)
		ON UPDATE CASCADE
		ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `Event` (
	`id`        INT          NOT NULL AUTO_INCREMENT,
	`updated`   TIMESTAMP,
	`name`      VARCHAR(255) NOT NULL,
	`ownerID`   INT          NOT NULL,
	`start`     TIMESTAMP    NOT NULL,
	`end`       TIMESTAMP    NOT NULL,
	`location`  VARCHAR(255),
	`building`  VARCHAR(255),
	`room`      VARCHAR(255),
	`fromNJIT`  TINYINT(1)   NOT NULL DEFAULT 0,

	CONSTRAINT `Event_pk`
		PRIMARY KEY (`id`),
	
	CONSTRAINT `Event_fk_ownerID`
		FOREIGN KEY (`ownerID`)
		REFERENCES `User` (`id`)
		ON UPDATE CASCADE
		ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `EventFavorites` ( -- : Event, User
	`eventID` INT NOT NULL, -- : Event.id
	`userID`  INT NOT NULL, -- : User.id
	
	CONSTRAINT `EventFavorites_pk`
		PRIMARY KEY (`eventID`, `userID`),

	CONSTRAINT `EventFavorites_fk_eventID`
		FOREIGN KEY (`eventID`)
		REFERENCES `Event` (`id`)
		ON UPDATE CASCADE
		ON DELETE CASCADE,

	CONSTRAINT `EventFavorites_fk_userID`
		FOREIGN KEY (`userID`)
		REFERENCES `User` (`id`)
		ON UPDATE CASCADE
		ON DELETE CASCADE
);
