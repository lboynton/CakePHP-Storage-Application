#!/bin/sh
echo Dumping database structure...
mysqldump backup --user backup --password --no-data > schema.sql

#echo Adding user to users table...
# add user to users table
#echo "INSERT INTO \`backup\`.\`users\` (\`id\`,\`username\`,\`password\`,\`email\`,\`created\`,\`real_name\`,\`admin\`,\`quota\`,\`last_login\`,\`disabled\`) VALUES
#  (1,'lee','d3521f0f4841ff1a777252f1d0ed1671236ae505','lee@lboynton.com','2008-09-12 17:17:27','Lee Boynton',1,10485760,'2008-09-20 18:07:17',0);" >> backup.sql

echo Finished!
