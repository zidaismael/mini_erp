# INTRO
Tous les prix dans l'application sont considérés en TTC.
Elle permet de retracer la provenance du produit en gardant l'information de la référence du produit commandé auprès du fournisseur.

J'ai développé la partie API de l'ERP car je voulais découper l'application en 2 parties (backend en Phalcon et frontend en Angular ou VueJS).
Je n'ai malheureusement pas eu le temps de développer le front.
Aussi je précise que je ne connaissais pas le framework Phalcon auparavant (mais je ne regrette pas cette expérience, mise à part le bug des classes Model sur Phalcon 4.1.* avec PHP7.4. sur ubuntu focal qui m'a fait downgrader la version).

# SETUP

- En construisant l'image docker
    #### Cloner le dépot git 
    ```bash 
    	git clone https://github.com/zidaismael/mini_erp.git
    ```
    
    Aller dans [PROJET_ROOT]/docker
    
    Lancer 
    ```bash 
    	docker build .
    ```
- Avec l'image docker prête à démarrer
	```bash 
    docker pull zidaismael/mini_erp:latest
    docker image tag zidaismael/mini_erp:latest docker_mini_erp:latest
    ```
# RUN
Aller dans [PROJET_ROOT]/docker
```bash 
	docker-compose up -d
```
/!\ si le conteneur mini_erp ne s'est pas lancé, rejouer la commande de lancement sans stopper les autres conteneurs. 

L'api sera disponible sur l'ip de l'hôte à l'adresse: **http://[HOST_IP]:8888/api**  

Afin de tester les routes et règles de l'API, vous pouvez utiliser la collection POSTMAN v2.1 mise à disposition dans [PROJET_ROOT]/doc.

(Les routes sont gérées et définies par l'objet \ERP\ErpRouter)

# DOC

La PHP doc est disponible ici **http://[HOST_IP]:9999**

La PHP doc peut être regénérée depuis la racine du projet  en lançant 
```bash 
	composer generate-doc
```

Le reste de la documentation se touve dans [PROJET_ROOT]/doc

# TEST

Les tests unitaires ne concerne que le core de l'application qui représente la logique applicative indépendamment du contexte, pas phalcon et son modèle MVC.
 

Pour lancer les TU, aller à la racine du projet puis
```bash 
	composer test
```
PS:Je n'ai pas eu le temps de tester toutes les méthodes, mais j'ai fait les principaux tests.

# DEBUG

- Vous pouvez voir les données de la BDD ici **http://[HOST_IP]:7777**
- Des logs applicatifs et apaches sont générés dans **/data/log/**


# TODO (RAF)

 [ ] Implémenter la pagination
 
 [ ] Ajouter des codes d'erreurs personalisés à l'API
 
 [ ] Finir les T.U.
 
 [ ] Faire le frontend