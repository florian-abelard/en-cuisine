#!/bin/sh

database=encuisine
user=flo
password=secret
dumpFile=dump.sql

PGPASSWORD=${password} dropdb -U $user $database
PGPASSWORD=${password} createdb -U $user $database
PGPASSWORD=${password} psql -U $user -d $database -1 -f ${dumpFile}
