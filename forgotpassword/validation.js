
function validateForm() {
  const email = document.getElementById('email').value.trim();
  const password = document.getElementById('password').value.trim();

  const emailError = document.getElementById('emailError');
  const passwordError = document.getElementById('passwordError');

  const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
  const passwordRegex = /^.{8,}$/;

  let isValid = true;

  // Email validation
  if (email === "") {
    emailError.textContent = "Email should not be empty";
    isValid = false;
  } else if (!emailRegex.test(email)) {
    emailError.textContent = "Invalid email format";
    isValid = false;
  } else {
    emailError.textContent = "";
  }
  
  // Password validation
  if (password === "") {
    passwordError.textContent = "Password should not be empty";
    isValid = false;
  } else if (!passwordRegex.test(password)) {
    passwordError.textContent = "Password must be at least 8 characters";
    isValid = false;
  } else {
    passwordError.textContent = "";
  }

  // Return whether the form is valid or not
  return isValid;
}