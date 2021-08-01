docker exec -t postgres-container pg_dumpall -c -U postgres | gzip > $PWD/backup/tasker_`date +%d-%m-%Y"_"%H_%M_%S`.sql.gz
