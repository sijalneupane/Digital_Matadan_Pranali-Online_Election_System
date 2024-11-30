
function showError(elementId, message) {
    document.getElementById(elementId).innerHTML = message;
}

function clearErrors() {
    let errorSpans = document.querySelectorAll(".error");
    errorSpans.forEach(span => span.innerHTML = "");
}

function validateForm() {
    clearErrors();

    // Get form values
    let name = document.getElementById('name').value;
    let gender = document.getElementById("gender").value;
    let local_address = document.getElementById("local_address").value;
    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;
    let citizenship = document.getElementById("citizenshipNumber").value;
    let dob = document.getElementById("dateOfBirth").value;
    let regionNo = document.getElementById("regionNo").value;

    // File inputs
    let citizenshipFront = document.getElementById("citizenshipFront").files[0];
    let citizenshipBack = document.getElementById("citizenshipBack").files[0];
    let userPhoto = document.getElementById("userPhoto").files[0];

    // Regular expressions for validation
    let emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    let passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/;

    let isValid = true;

    if (name.trim() === "") {
        showError("nameError", "Full Name is required");
        isValid = false;
    }

    if (gender.trim() === "" || gender.trim() === "default") {
        showError("genderError", "Please select a gender");
        isValid = false;
    }

    if (local_address.trim() === "") {
        showError("addressError", "Local Address is required");
        isValid = false;
    }

    if (email.trim() === "") {
        showError("emailError", "Email is required");
        isValid = false;
    } else if (!emailPattern.test(email)) {
        showError("emailError", "Invalid email format");
        isValid = false;
    }

    if (password.trim() === "") {
        showError("passwordError", "Password is required");
        isValid = false;
    } else if (!passwordPattern.test(password)) {
        showError(
            "passwordError",
            "Password must include 6-20 characters, uppercase, lowercase, digit, and special character"
        );
        isValid = false;
    }

    if (citizenship.trim() === "" || isNaN(citizenship)) {
        showError("citizenshipError", "Please enter Citizenship Number");
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

    if (!validateImage("citizenshipFrontError", citizenshipFront, "Citizenship Front Photo")) {
        isValid = false;
    }
    if (!validateImage("citizenshipBackError", citizenshipBack, "Citizenship Back Photo")) {
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