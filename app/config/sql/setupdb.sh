#!/bin/sh
echo Setting up database, please enter MySQL root password when prompted...

mysql -u root -p < setupdb.sql

echo Finished!
