
-- Create user 'guest' if it doesn't already exist, with password '3394dzwHi0HJimrA13JO' and grant access only from localhost
CREATE USER IF NOT EXISTS 'bookQuest'@'localhost' IDENTIFIED BY '3394dzwHi0HJimrA13JO';

-- Grant SELECT and INSERT permissions to user 'guest' on table 'users' in database 'userdb'
GRANT SELECT, INSERT, DELETE, UPDATE ON bookShelf.* TO 'bookQuest'@'localhost';