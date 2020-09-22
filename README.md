# Symfony

## Comment travailler sur le projet ?

Première étape, je récupère le dépôt :

```bash
cd C:\xampp\htdocs
git clone URL PROJET
cd PROJET
```

On installe les dépendances :

```bash
composer install
```

Optionnel: Si jamais, on a un soucis avec la version de PHP :

```bash
rm composer.lock
rm -rf vendor
composer install
```

On configure la base de données dans ```.env.local``` :

```
DATABASE_URL=mysql://root:@127.0.0.1:3306/master?serverVersion=mariadb-10.5.5
```

On crée la BDD :

```bash
php bin/console doctrine:database:create
```

On crée le schéma (méthode 1 sans migration) :

```bash
php bin/console doctrine:schema:update --force
```

Méthode 2 (avec migration) :

```bash
php bin/console doctrine:migrations:migrate
```

On peut lancer les fixtures :

```bash
php bin/console doctrine:fixtures:load
```

Pour cleaner les migrations, si c'est nécessaire, on supprimer le contenu du dossier ```migrations/``` et on lance les commandes suivantes :

```bash
php bin/console doctrine:database:drop --force
php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```
