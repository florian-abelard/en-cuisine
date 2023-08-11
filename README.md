# En cuisine !

## Démarrage

### Pré-requis

* docker
* docker compose

### Mise en place de l'environnement

```bash
# Récupération des sources github
git clone https://github.com/florian-abelard/encuisine.git

# Initialisation du projet
make init

# Démarrer les containers docker et alimenter la base de données 
make up

# Afficher toutes les commandes disponibles
make
```

### Accès aux interfaces

Application `http://localhost:8080`

API `http://localhost:8080/api`
