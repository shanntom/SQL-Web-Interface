/*
Every movie has a unique identification number
*/
CREATE TABLE Movie (
  id 		INT NOT NULL,
  title	 	VARCHAR(100),
  year 		INT,
  rating 	VARCHAR (10),
  company	VARCHAR(50),
  PRIMARY KEY(id)
) ENGINE=INNODB;

/*
Every actor has a unique identification number
The date of death should not be before the date of birth
*/
CREATE TABLE Actor (
  id		INT NOT NULL,
  last		VARCHAR(20),
  first 	VARCHAR (20),
  sex 		VARCHAR(6),
  dob		DATE,
  dod		DATE,
  PRIMARY KEY(id),
  CHECK (dod >= dob)
) ENGINE=INNODB;

/*
Every movie id should reference an id from Movie
The number of tickets sold should not be negative
The total income should not be negative
*/
CREATE TABLE Sales (
  mid			INT,
  ticketsSold	INT,
  totalIncome	INT,
  FOREIGN KEY(mid) references Movie(id),
  CHECK (ticketsSold >= 0),
  CHECK (totalIncome >= 0)
) ENGINE=INNODB;

/*
Every director has a unique identification number
*/
CREATE TABLE Director (
  id		INT NOT NULL,
  last		VARCHAR(20),
  first 	VARCHAR(20),
  dob		DATE,
  dod		DATE,
  PRIMARY KEY(id)
) ENGINE=INNODB;

/*
Every movie id should reference an id from Movie
*/
CREATE TABLE MovieGenre (
  mid	INT,
  genre VARCHAR(20),
  FOREIGN KEY(mid) references Movie(id)
) ENGINE=INNODB;

/*
Every movie id should reference an id from Movie
Every director id should reference an id from Director
*/
CREATE TABLE MovieDirector (
  mid 	INT,
  did	INT,
  FOREIGN KEY(mid) references Movie(id),
  FOREIGN KEY(did) references Director(id)
) ENGINE=INNODB;

/*
Every movie id should reference an id from Movie
Every actor id should reverence an id from Actor
*/
CREATE TABLE MovieActor (
  mid 	INT,
  aid	INT,
  role	VARCHAR(50),
  FOREIGN KEY(mid) references Movie(id),
  FOREIGN KEY(aid) references Actor(id)
) ENGINE=INNODB;

/*
Every movie id should reference an id from Movie
*/
CREATE TABLE MovieRating (
  mid 	INT,
  imdb	INT,
  rot	INT,
  FOREIGN KEY(mid) references Movie(id)
) ENGINE=INNODB;

/*
Every movie id should reference an id from Movie
*/
CREATE TABLE Review (
  name		VARCHAR(20),
  time 		TIMESTAMP,
  mid  		INT,
  rating 	INT,
  comment	VARCHAR(500),
  FOREIGN KEY(mid) references Movie(id)
) ENGINE=INNODB;

CREATE TABLE MaxPersonID (
  id 	INT
) ENGINE=INNODB;

CREATE TABLE MaxMovieID (
  id 	INT
) ENGINE=INNODB;