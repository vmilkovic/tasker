gunzip < $PWD/backup/$1.gz | docker exec -i postgres-container psql -U postgres -d tasker
