#!/bin/sh
echo Restoring database...

mysql -u backup -pbackup backup < backup.sql

echo Finished!
