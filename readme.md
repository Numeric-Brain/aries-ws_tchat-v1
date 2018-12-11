# Projet de tchat basé sur les websockets
A l'origine, ce projet devait être développé intégralement en JS (natif côté client, Node.js côté serveur). De très nombreux utilisateurs n'ayant pas les connaissances ou la possibilité de déployer Node.js côté serveur, je suis parti sur un mix classique mais potenciellement complexe : PHP côté serveur, JS côté client. Le but étant de se servir le moins possible de librairies pour faire du bas niveau, seul Ratchet a été utilisé.

## Technologies / Langages
* **Serveur :** PHP avec Ratchet
* **Client :** JS natif

## Mode d'emploi :
1. Lancez le serveur WS depuis la console (fichier "*chat-server.php*" dans le dossier "*bin*")
2. Ouvrez l'interface de l'administrateur (fichier "*admin.html*" dans le dossier "*web*")
  * Cliquez sur le bouton de connexion
3. Ouvrez les interfaces des utilisateurs (fichier "*index.html*" dans le dossier "*web*")
  * Identifiez-vous
  * Cliquez sur le bouton de connexion
4. Tchatez !

## Sitemap :
* **bin/chat-server.php** (instanciation et démarrage du serveur WS)
* **src/Chat.php** (classe principale de l'application)
* **web/css/normalize.css** (fichier CSS de normalisation)
* **web/css/style.css** (fichier CSS principal)
* **web/images/logo.png** (logo Aries)
* **web/images/on_off.png** (interrupteur)
* **web/js/admin.js** (gestion administrateur côté client)
* **web/js/client.js** (gestion utilisateur côté client)
* **admin.html** (vue administrateur)
* **index.html** (vue utilisateur)
