async function setMinDOB() {
  // const loader = document.getElementById("loading"); // Reference the loader
  //   loader.style.display = "block"; // Show the loader
  let dob = document.getElementById("dateOfBirth");
  dob.disabled=true;
  try {
    let response = await fetch(
      "https://timeapi.io/api/Time/current/zone?timeZone=Asia/Kathmandu"
    );
    let apiDate = await response.json();

    let currentDate = new Date(apiDate.dateTime);
    currentDate.setFullYear(currentDate.getFullYear() - 18);

    document
      .getElementById("dateOfBirth")
      .setAttribute("max", currentDate.toISOString().split("T")[0]);
  } catch (error) {
    console.error("Failed to fetch time from API. Falling back to local time.");

    // Fallback to local time
    let today = new Date();
    today.setFullYear(today.getFullYear() - 18);
    let formattedDate = today.toISOString().split("T")[0];

    document.getElementById("dateOfBirth").setAttribute("max", formattedDate);
  } finally {
    // loader.style.display = "none"; // Hide the loader
    dob.disabled=false;
  }
}
document.addEventListener("DOMContentLoaded", setMinDOB);

//validation starts here
function showError(elementId, message) {
  document.getElementById(elementId).innerHTML = message;
}

function clearErrors() {
  let errorSpans = document.querySelectorAll(".error");
  errorSpans.forEach((span) => (span.innerHTML = ""));
}

function validateForm() {
  clearErrors();

  // Get form values
  let name = document.getElementById("name").value.trim();
  let gender = document.getElementById("gender").value.trim();
  let local_address = document.getElementById("local_address").value.trim();
  let email = document.getElementById("email").value.trim();
  let password = document.getElementById("password").value.trim();
  let citizenship = document.getElementById("citizenshipNumber").value.trim();
  let dob = document.getElementById("dateOfBirth").value.trim();
  let regionNo = document.getElementById("regionNo").value.trim();
  let district = document.getElementById("district").value.trim();

  // File inputs
  let citizenshipFront = document.getElementById("citizenshipFront").files[0];
  let citizenshipBack = document.getElementById("citizenshipBack").files[0];
  let userPhoto = document.getElementById("userPhoto").files[0];

  // Regular expressions for validation
  let namePattern = /^[A-Za-z\s]{7,}$/;
  let emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z.-]+\.[a-zA-Z]{2,6}$/;
  let passwordPattern =
    /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/;

  let isValid = true;

  if (name === "") {
    showError("nameError", "Full Name is required");
    isValid = false;
  } else if (!namePattern.test(name)) {
    showError("nameError", "Full Name should only contain alphabets and more than 7 characters");
    isValid = false;
  }

  if (gender === "" || gender === "default") {
    showError("genderError", "Please select a gender");
    isValid = false;
  }

  if (local_address === "") {
    showError("addressError", "Local Address is required");
    isValid = false;
  }

  if (email === "") {
    showError("emailError", "Email is required");
    isValid = false;
  } else if (!emailPattern.test(email)) {
    showError("emailError", "Invalid email format");
    isValid = false;
  }

  if (password === "") {
    showError("passwordError", "Password is required");
    isValid = false;
  } else if (!passwordPattern.test(password)) {
    showError(
      "passwordError",
      "Password must include 6-20 characters, uppercase, lowercase, digit, and special character"
    );
    isValid = false;
  }

  if (citizenship === "") {
    showError("citizenshipError", "Please enter Citizenship Number");
    isValid = false;
  } else if (isNaN(citizenship)) {
    showError(
      "citizenshipError",
      "Citizenship Number should only contain numbers"
    );
    isValid = false;
  }

  if (regionNo === "" || regionNo === "default") {
    showError("regionNoError", "Please select Constituency");
    isValid = false;
  }
  if (district === "" || district === "default") {
    showError("districtError", "Please select your district");
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
    if (
      monthDiff < 0 ||
      (monthDiff === 0 && today.getDate() < selectedDate.getDate())
    ) {
      age--;
    }
    if (age < 18) {
      showError("dobError", "You must be at least 18 years old to register");
      isValid = false;
    }
  }

  // File input validation
  function validateImage(errorName, file, inputName) {
    if (!file) {
      showError(errorName, "Please upload" + inputName);
      return false;
    }
    const validTypes = ["image/jpeg", "image/png", "image/jpg"];
    if (!validTypes.includes(file.type)) {
      showError(errorName, inputName + "must be a JPG/PNG image");
      return false;
    }
    if (file.size > 2 * 1024 * 1024) {
      showError(errorName, inputName + "size should not exceed 2MB");
      return false;
    }
    return true;
  }

  if (
    !validateImage(
      "citizenshipFrontError",
      citizenshipFront,
      "Citizenship Front Photo"
    )
  ) {
    isValid = false;
  }
  if (
    !validateImage(
      "citizenshipBackError",
      citizenshipBack,
      "Citizenship Back Photo"
    )
  ) {
    isValid = false;
  }
  if (!validateImage("userPhotoError", userPhoto, "User Photo")) {
    isValid = false;
  }

  return isValid;
}

// Preview image function
function previewImage(input, previewId) {
  const file = input.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function (e) {
      const preview = document.getElementById(previewId);
      preview.src = e.target.result;
      preview.style.display = "block";
    };
    reader.readAsDataURL(file);
  }
}
