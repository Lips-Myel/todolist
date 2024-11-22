# Projet Backend : Application de Gestion des Tâches

## Contexte :

Vous êtes des développeurs backend et votre mission est de créer l'API nécessaire pour une **application de gestion de tâches**. L'objectif est de permettre aux utilisateurs de créer, supprimer, et marquer des tâches comme complétées.

Le **frontend** (HTML, CSS, et JavaScript) vous est fourni. Vous devrez donc vous concentrer exclusivement sur la réalisation du backend et de l'API.

## Objectifs :

- Créer une API RESTful pour gérer les utilisateurs et leurs tâches.
- Gérer l’inscription, la connexion, la déconnexion des utilisateurs.
- Permettre la création, la modification (complétion) et la suppression des tâches.
- Retourner les données au format JSON pour que le frontend puisse fonctionner correctement.

## Consignes :

1. **Concevoir la base de données** : À partir de la description du projet ci-dessous, vous devez modéliser et créer la base de données.
2. **Créer l'API** en respectant les spécifications décrites plus bas.
3. **Assurer la sécurité** de l'application, notamment en protégeant les mots de passe.

---

## Fonctionnalités attendues :

Voici les fonctionnalités que l’API doit fournir :

### 1. **Inscription d’un utilisateur** :

- **Méthode** : `POST /api/register.php`
- **Données attendues (Body)** :
  - `username` : Le nom d'utilisateur (string)
  - `password` : Le mot de passe de l'utilisateur (string)
- **Exemple de réponse attendue (en JSON)** :
  ```json
  {
    "message": "Inscription réussie"
  }
### 2. **Connexion d’un utilisateur** :

- **Méthode** : `POST /api/login.php`
- **Données attendues (Body)** :
  - `username` : Le nom d'utilisateur (string)
  - `password` : Le mot de passe de l'utilisateur (string)
- **Exemple de réponse attendue (en JSON)** :
  ```json
  {
    "message": "Connexion réussie"
  }
### 3. **Déconnexion d’un utilisateur** :

- **Méthode** : `POST /api/logout.php`
- **Exemple de réponse attendue (en JSON)** :
  ```json
  {
    "message": "Déconnexion réussie"
  }
### 4. **Ajout d’une tâche** :

- **Méthode** : `POST /api/tasks.php`
- **Données attendues (Body)** :
  - `title` : Le titre de la tâche (string)
  - `description` : La description de la tâche (HTML ou texte brut)
- **Exemple de réponse attendue (en JSON)** :
  ```json
  {
    "message": "Tâche ajoutée avec succès"
  }
### 5. **Marquer une tâche comme complétée** :

- **Méthode** : `POST /api/tasks.php`
- **Données attendues (Body)** :
  - `id` : L’ID de la tâche (int)
  - `completed` : 1 pour marquer la tâche comme terminée (int)
  - `action` : `complete` (pour indiquer qu'il s'agit d'une mise à jour de complétion)
- **Exemple de réponse attendue (en JSON)** :
  ```json
  {
    "message": "Tâche mise à jour avec succès"
  }
### 6. **Suppression d’une tâche** :

- **Méthode** : `delete /api/tasks.php`
- **Données attendues (Body)** :
  - `id` : L’ID de la tâche (int)
- **Exemple de réponse attendue (en JSON)** :
  ```json
  {
    "message": "Tâche supprimée avec succès"
  }
### 7. **Récupération de la liste des tâches** :

- **Méthode** : `GET /api/tasks.php`
- **Exemple de réponse attendue (en JSON)** :
  ```json
  [
    {
      "id": 1,
      "title": "Ma première tâche",
      "description": "Description de la tâche",
      "completed": 0
    },
    {
      "id": 2,
      "title": "Ma deuxième tâche",
      "description": "Autre description de tâche",
      "completed": 1
    }
  ]
## Spécifications pour le Backend :

### 1. **Modélisation de la base de données** :

Vous devez créer deux tables principales :
- **`users`** : Pour gérer les utilisateurs.
- **`tasks`** : Pour stocker les tâches de chaque utilisateur.

Voici les informations dont vous disposez pour concevoir les tables :

#### **Table `users`** :
- Chaque utilisateur possède un **id** unique (clé primaire).
- Chaque utilisateur possède un **username** (chaîne de caractères, unique).
- Le mot de passe doit être **haché** avant d'être stocké en base de données.

#### **Table `tasks`** :
- Chaque tâche possède un **id** unique (clé primaire).
- Chaque tâche est liée à un **user_id** (clé étrangère vers la table `users`).
- Une tâche a un **title** (titre) et une **description**.
- Chaque tâche a un champ **completed** (booléen : 0 ou 1) pour indiquer si elle est terminée.
- Chaque tâche a un champ **created_at** pour stocker la date de création.

### 2. **Sécurité des mots de passe** :
- Les mots de passe doivent être **hachés** en utilisant la fonction PHP `password_hash()`.
- Pour la connexion, utilisez `password_verify()` pour vérifier les mots de passe hachés.

---

## Détails techniques et retour des données attendues par le frontend :

1. **Format des données** :
   - Toutes les requêtes doivent renvoyer les données sous forme de **JSON**.

2. **Gestion des erreurs** :
   - Assurez-vous de retourner des messages d'erreur explicites en cas d’échec (par exemple, une tâche non trouvée ou un mot de passe incorrect).
   - Utilisez les bons **codes d'état HTTP** : `200 OK`, `400 Bad Request`, `401 Unauthorized`, `403 Forbidden`, `500 Internal Server Error`.

---

## Contraintes :

- **Langage :** PHP
- **Base de données :** MySQL ou MariaDB
- **Pas de framework** : L’API doit être écrite en PHP natif, sans utiliser de framework comme Laravel ou Symfony.
- **Authentification** : Utilisez des **sessions PHP** pour gérer l'authentification de l'utilisateur.

---

## Exemples d’utilisation des superglobales PHP :

- Utilisez **`$_POST`** pour gérer les données envoyées en méthode POST.
- Pour une requête GET, vous pouvez utiliser **`$_GET`**.
- Vous pouvez également utiliser **`$_SESSION`** pour stocker l'identifiant de l'utilisateur après la connexion et vérifier les requêtes authentifiées.

---

## Étapes :

1. **Créer la base de données** en vous basant sur la description donnée.
2. **Implémenter les endpoints** décrits dans la section Fonctionnalités attendues.
3. **Tester** les fonctionnalités via un outil comme **Postman** ou **Thunder Client** pour simuler les requêtes.
4. **Vérifier** que le frontend peut interagir correctement avec votre API.

## Livrables attendus :

1. **Code PHP** contenant l’API RESTful qui permet de réaliser les fonctionnalités décrites ci-dessus.
2. **Fichier SQL** avec les commandes SQL nécessaires pour créer la base de données.
3. Un fichier **README.md** expliquant comment configurer et utiliser l'API (pré-requis, installation, etc.).
