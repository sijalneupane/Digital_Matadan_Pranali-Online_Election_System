function togglePasswordVisibility() {
  const passwordInput = document.getElementById('password');
  const toggleIcon = document.getElementById('togglePasswordIcon');
  if (passwordInput.type === 'password') {
      passwordInput.type = 'text';
      toggleIcon.className = 'fas fa-eye-slash toggle-password';
  } else {
      passwordInput.type = 'password';
      toggleIcon.className = 'fas fa-eye toggle-password';
  }
}