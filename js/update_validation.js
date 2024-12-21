function showError(elementId, message) {
    document.getElementById(elementId).innerHTML = message;
  }
  
  function clearErrors() {
    let errorSpans = document.querySelectorAll(".error");
    errorSpans.forEach(span => (span.innerHTML = ""));
  }
  
  function validateForm() {
    clearErrors();
  
    // Get form values
    let name = document.getElementById("name").value.trim();
    let gender = document.getElementById("gender").value.trim();
    let local_address = document.getElementById("local_address").value.trim();
    let district = document.getElementById("district").value.trim();
    let citizenship = document.getElementById("citizenshipNumber").value.trim();
    let dob = document.getElementById("dateOfBirth").value.trim();
    let regionNo = document.getElementById("regionNo").value.trim();
    let email = document.getElementById("email").value.trim();
  
    // Regular expressions for validation
    let emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
  
    let isValid = true;
  
    if (name === "") {
      showError("nameError", "Full Name is required");
      isValid = false;
    }
  
    if (gender === "") {
      showError("genderError", "Please select a gender");
      isValid = false;
    }
  
    if (local_address === "") {
      showError("addressError", "Local Address is required");
      isValid = false;
    }
  
    if (district === ""|| district === "default") {
      showError("districtError", "PLease select district");
      isValid = false;
    }
  
    if (email=== "") {
      showError("emailError", "Email is required");
      isValid = false;
    } else if (!emailPattern.test(email)) {
      showError("emailError", "Invalid email format");
      isValid = false;
    }
  
    if (citizenship === "" || isNaN(citizenship)) {
      showError("citizenshipError", "Please enter a valid Citizenship Number");
      isValid = false;
    }
  
    if (regionNo === ""|| regionNo === "default") {
      showError("regionNoError", "PLease select Constituent No");
      isValid = false;
    }
  
    // Validate Date of Birth
    if (dob === "") {
      showError("dobError", "Date of Birth is required");
      isValid = false;
    } else {
      let selectedDate = new Date(dob);
      let today = new Date();
      let age = today.getFullYear() - selectedDate.getFullYear();
      let monthDiff = today.getMonth() - selectedDate.getMonth();
      if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < selectedDate.getDate())) {
        age--;
      }
      if (age < 18) {
        showError("dobError", "You must be at least 18 years old to register");
        isValid = false;
      }
    }
  
    return isValid;
  }
  