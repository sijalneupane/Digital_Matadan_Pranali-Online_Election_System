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
    let name = document.getElementById("name").value;
    let gender = document.getElementById("gender").value;
    let local_address = document.getElementById("local_address").value;
    let district = document.getElementById("district").value;
    let citizenship = document.getElementById("citizenshipNumber").value;
    let dob = document.getElementById("dateOfBirth").value;
    let regionNo = document.getElementById("regionNo").value;
    let email = document.getElementById("email").value;
  
    // Regular expressions for validation
    let emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
  
    let isValid = true;
  
    if (name.trim() === "") {
      showError("nameError", "Full Name is required");
      isValid = false;
    }
  
    if (gender === "") {
      showError("genderError", "Please select a gender");
      isValid = false;
    }
  
    if (local_address.trim() === "") {
      showError("addressError", "Local Address is required");
      isValid = false;
    }
  
    if (district.trim() === "") {
      showError("districtError", "District is required");
      isValid = false;
    }
  
    if (email.trim() === "") {
      showError("emailError", "Email is required");
      isValid = false;
    } else if (!emailPattern.test(email)) {
      showError("emailError", "Invalid email format");
      isValid = false;
    }
  
    if (citizenship.trim() === "" || isNaN(citizenship)) {
      showError("citizenshipError", "Please enter a valid Citizenship Number");
      isValid = false;
    }
  
    if (regionNo.trim() === "") {
      showError("regionNoError", "Enter Constituent No");
      isValid = false;
    }
  
    // Validate Date of Birth
    if (dob.trim() === "") {
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
  