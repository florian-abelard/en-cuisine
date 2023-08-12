#!/bin/sh

npm install

chown -R `stat -c "%u:%g" package.json` ./node_modules/

exec npm run dev
