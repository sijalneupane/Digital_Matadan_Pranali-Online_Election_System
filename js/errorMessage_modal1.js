
// Function to show the modal with error messages
function showErrorModal(errorMessage,isSuccessMsg=false) {
  if (errorMessage) {
    const modal = document.getElementById('modal1');
    const modalMessage = document.getElementById('modalMessage1');
    if(isSuccessMsg){
      modalMessage.style.color = 'green';
    }
    modalMessage.textContent = errorMessage;
    
    modal.style.display = 'flex';
  }
}

// Function to close the modal
function closeModal1() {
  document.getElementById('modal1').style.display = 'none';
}