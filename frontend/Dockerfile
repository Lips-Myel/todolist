# Utiliser une image officielle Node.js comme image de base
FROM node:20.9.0-alpine

# Définir le répertoire de travail dans le conteneur
WORKDIR /var/www

# Copier les fichiers package.json et package-lock.json dans le conteneur
COPY . . 

# Installer les dépendances avant de copier le reste
RUN npm install

# Exposer le port par défaut de Node.js
EXPOSE 3003

# Commande pour démarrer l'application Node.js
CMD ["npm", "start"]
