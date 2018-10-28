# sti_web-project



Pour lancer le projet, la méthode la plus simple est d'utiliser le container docker sti:project2018

```dockerfile
docker run -ti -v //C/Users/username/docker/sti:/usr/share/nginx/ -d -p 8080:80 --name sti_project --hostname sti arubinst/sti:project2018
```

```
docker exec -u root sti_project service nginx start
docker exec -u root sti_project service php5-fpm start
```

Il suffit d'avoir les fichiers placés au bon endroit (p.ex. ici c:/Users/username/docker/sti)