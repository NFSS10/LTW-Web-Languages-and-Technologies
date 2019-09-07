-- Delete tables
DROP Table IF EXISTS user;
DROP Table IF EXISTS restaurant;
DROP Table IF EXISTS review;
DROP Table IF EXISTS unverified_user;


CREATE TABLE user (
  username TEXT PRIMARY KEY NOT NULL,
  password TEXT NOT NULL,
  name TEXT DEFAULT '',
  email TEXT DEFAULT '',
  city TEXT DEFAULT '',
  address TEXT DEFAULT '',
  phone INTEGER DEFAULT 000000000,
  photo TEXT DEFAULT 'resources/default_avatar.gif'
);

CREATE TABLE unverified_user (
  username TEXT PRIMARY KEY NOT NULL,
  password TEXT NOT NULL,
  email TEXT NOT NULL,
  code TEXT NOT NULL
);

CREATE TABLE restaurant (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name VARCHAR NOT NULL,
  address TEXT NOT NULL,
  city TEXT NOT NULL,
  price REAL DEFAULT 0,
  description TEXT NOT NULL,
  longitude INTEGER DEFAULT 0,
  latitude INTEGER DEFAULT 0,
  phone INTEGER
);

CREATE TABLE review (
  user TEXT REFERENCES user(username),
  restaurant INTEGER REFERENCES restaurant(id),
  score INTEGER NOT NULL,
  text TEXT,
  owner TEXT REFERENCES user(username) DEFAULT NULL,
  answer TEXT
);

CREATE TABLE ownerRestaurant (
  user TEXT REFERENCES user(username),
  restaurant INTEGER REFERENCES restaurant(id)
);

CREATE TABLE photoRestaurant (
  originalPhoto TEXT NOT NULL,
  mediumPhoto TEXT NOT NULL,
  smallPhoto TEXT NOT NULL,
  restaurant INTEGER REFERENCES restaurant(id)
);