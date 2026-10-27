const form = document.getElementById('formRegistro');
      form.addEventListener('submit', function(e) {
      const pass = document.getElementById('password').value;
      const confirmar = document.getElementById('confirmar').value;
            
    if (pass !== confirmar) {
    alert('Las contraseñas no coinciden. Verifícalas.');
    e.preventDefault();
  }
});
  