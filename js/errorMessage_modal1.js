// Function to show the modal with error messages
function showErrorModal(errorMessage) {
  if (errorMessage) {
    const modal = document.getElementById('modal1');
    const modalMessage = document.getElementById('modalMessage1');
    modalMessage.textContent = errorMessage;
    modal.style.display = 'flex';
  }
}

// Function to close the modal
function closeModal1() {
  document.getElementById('modal1').style.display = 'none';
}