#!/bin/sh
echo Restoring database...

mysql -u backup -p backup < backup.sql

echo Finished!
