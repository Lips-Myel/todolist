document.addEventListener('DOMContentLoaded', async function () {
    const registerForm = document.getElementById('register-form');
    const loginForm = document.getElementById('login-form');
    const addTaskForm = document.getElementById('add-task-form');
    const tasksList = document.getElementById('tasks-list');
    const authSection = document.getElementById('auth-section');
    const tasksSection = document.getElementById('tasks-section');
    const logoutBtn = document.getElementById('logout');


    var quill = new Quill('#task-editor', {
        theme: 'snow' // Utiliser le thème par défaut "snow"
    });

    // URL de l'API (Assure-toi que cette URL est correcte et correspond à ton backend)
    const API_URL = 'http://todolist:3003/api';


    // Vérifier si l'utilisateur est déjà connecté
    try {
        const response = await fetch(`${API_URL}/tasks.php`, {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' }
        });

        if (response.ok) {
            // Si la requête réussit, l'utilisateur est connecté
            authSection.style.display = 'none';
            tasksSection.style.display = 'block';
            loadTasks(); // Charger les tâches si l'utilisateur est connecté
        } else {
            // Si la requête échoue, l'utilisateur n'est pas connecté
            authSection.style.display = 'block';
            tasksSection.style.display = 'none';
        }
    } catch (error) {
        console.error('Erreur lors de la vérification de la connexion :', error);
    }

    // Fonction d'inscription
    registerForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const username = document.getElementById('register-username').value;
        const password = document.getElementById('register-password').value;

        try {
            const response = await fetch(`${API_URL}/register.php`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
            });

            if (!response.ok) {
                throw new Error(`Erreur lors de l'inscription : ${response.statusText}`);
            }

            const result = await response.json();
            showToast(result.message, 'success');

        } catch (error) {
            showToast('Une erreur est survenue lors de l\'inscription.', 'error'); // Afficher un toast d'erreur
        }
    });

    // Fonction de connexion
    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const username = document.getElementById('login-username').value;
        const password = document.getElementById('login-password').value;

        try {
            const response = await fetch(`${API_URL}/login.php`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
            });

            if (!response.ok) {
                throw new Error(`Erreur lors de la connexion : ${response.statusText}`);
            }

            const result = await response.json();
            if (result.message === 'Connexion réussie') {
                authSection.style.display = 'none';
                tasksSection.style.display = 'block';
                loadTasks();
            }
        } catch (error) {
            showToast('Une erreur est survenue lors de la connexion.', 'error');
        }
    });

    // Fonction pour ajouter une tâche
    addTaskForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const title = document.getElementById('task-title').value;
        const description = quill.root.innerHTML;;

        try {
            const response = await fetch(`${API_URL}/tasks.php`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `title=${encodeURIComponent(title)}&description=${encodeURIComponent(description)}`
            });

            if (!response.ok) {
                throw new Error(`Erreur lors de l'ajout de la tâche : ${response.statusText}`);
            }

            const result = await response.json();
            showToast(result.message, 'success');
            loadTasks();
        } catch (error) {
            showToast('Une erreur est survenue lors de l\'ajout de la tâche.', 'error');
        }
    });

    // Fonction pour récupérer et afficher les tâches
    async function loadTasks() {
        try {
            const response = await fetch(`${API_URL}/tasks.php`, {
                method: 'GET',
                headers: { 'Content-Type': 'application/json' }
            });

            if (!response.ok) {
                throw new Error(`Erreur lors du chargement des tâches : ${response.statusText}`);
            }

            const result = await response.json();
            tasksList.innerHTML = ''; // Réinitialise la liste des tâches

            result.tasks.forEach(task => {
                const li = document.createElement('li');
                console.log(task);
                
                li.classList.toggle('completed', task.completed === 1); // Applique la classe "completed" si la tâche est terminée

                li.innerHTML = `
                    <div class="task-header">
                        <h3>${task.title}</h3>
                        <div class="btn-container">
                            <button data-id="${task.id}" class="btn-delete">Supprimer</button>
                            ${task.completed === 0 ? `<button data-id="${task.id}" class="btn-complete">Terminer</button>` : ''}
                        </div>
                    </div>
                    <div class="task-content">
                        ${task.description}
                    </div>
                `;
                // Supprimer une tâche
                li.querySelector('.btn-delete').addEventListener('click', async () => {
                    try {
                        const deleteResponse = await fetch(`${API_URL}/tasks.php?id=${task.id}`, {
                            method: 'DELETE',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                        });

                        const result = await deleteResponse.json();
                        showToast(result.message, 'success');
                        loadTasks();
                    } catch (error) {
                        console.error(error);
                        showToast('Une erreur est survenue lors de la suppression de la tâche.', 'error');
                    }
                });

                // Gestion de la complétion de la tâche
                const completeButton = li.querySelector('.btn-complete');
                if (completeButton) {
                    completeButton.addEventListener('click', async () => {
                        try {
                            const completeResponse = await fetch(`${API_URL}/tasks.php`, {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                body: `id=${task.id}&completed=1&action=complete`
                            });

                            const result = await completeResponse.json();
                            if (completeResponse.ok) {
                                showToast(result.message, 'success');
                                loadTasks(); // Recharger les tâches
                            } else {
                                showToast(result.message, 'error');
                            }
                        } catch (error) {
                            showToast('Une erreur est survenue lors de la mise à jour de la tâche.', 'error');
                        }
                    });
                }
                tasksList.appendChild(li);
            });
        } catch (error) {
            showToast('Une erreur est survenue lors de la suppression de la tâche.', 'error');
        }
    }
    // Fonction pour vérifier si l'utilisateur est connecté
    async function checkLoginStatus() {
    try {
        // Envoie une requête à l'API pour vérifier l'état de la connexion
        const response = await fetch(`${API_URL}/index.php`, {
            method: 'GET',
            credentials: 'same-origin', // Envoie les cookies de session
        });

        if (response.ok) {
            // Si l'utilisateur est connecté, afficher la section des tâches
            document.getElementById('tasks-section').style.display = 'block';
            document.getElementById('auth-section').style.display = 'none';
        } else {
            // Si l'utilisateur n'est pas connecté, afficher la section de connexion
            document.getElementById('tasks-section').style.display = 'none';
            document.getElementById('auth-section').style.display = 'block';
        }
    } catch (error) {
        console.error('Erreur lors de la vérification de la session:', error);
    }
}

    // Fonction de déconnexion
    logoutBtn.addEventListener('click', async () => {
        try {
            const response = await fetch(`${API_URL}/logout.php`);

            if (!response.ok) {
                throw new Error(`Erreur lors de la déconnexion : ${response.statusText}`);
            }

            authSection.style.display = 'block';
            tasksSection.style.display = 'none';
        } catch (error) {
            showToast('Une erreur est survenue lors de la déconnexion.', 'error');
        }
    });
    checkLoginStatus();
});

function escapeHTML(str) {
    var div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}

function showToast(message, type = 'success') {
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: type,
        title: message,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });
}


