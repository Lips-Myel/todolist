const express = require('express');
const path = require('path');
const cors = require('cors'); // Importation de CORS

const app = express();

// Activation de CORS
app.use(cors()); // Cela autorise toutes les origines


// Servir les fichiers statiques (HTML, CSS, JS, etc.)
app.use(express.static(path.join(__dirname, 'public')));

// Route pour la page d'accueil
app.get('/', (req, res) => {
  res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

// Démarrage du serveur
const PORT = 3003;
app.listen(PORT, () => {
  console.log(`Serveur démarré sur http://localhost:${PORT}`);
});

