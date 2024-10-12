# sauvegarde de la base de données
0 4 * * 1 PGPASSWORD="Rei:hPH+o_Di393" pg_dump -U flo -h localhost -p 8032 -Fp -c --if-exists -f ~/backup/sql/encuisine_$(date +\%Y-\%m-\%d).sql encuisine
