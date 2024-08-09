document.getElementById('loginForm').addEventListener('submit', function(event) {
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value.trim();
    const errorDiv = document.getElementById('error');

    if (username === '' || password === '') {
        event.preventDefault();
        errorDiv.textContent = 'Nome de usuário e senha são obrigatórios.';
    } else {
        errorDiv.textContent = '';
    }
});