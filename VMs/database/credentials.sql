
-- Create user 'guest' if it doesn't already exist, with password '3394dzwHi0HJimrA13JO' and grant access only from localhost
CREATE USER IF NOT EXISTS 'guest'@'localhost' IDENTIFIED BY '3394dzwHi0HJimrA13JO';

-- Grant SELECT and INSERT permissions to user 'guest' on table 'users' in database 'userdb'
GRANT SELECT, INSERT, DELETE ON userdb.* TO 'guest'@'localhost';
GRANT SELECT, INSERT, DELETE ON booksdb.* TO 'guest'@'localhost';