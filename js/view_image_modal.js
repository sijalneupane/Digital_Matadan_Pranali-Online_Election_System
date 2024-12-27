//image modal
        function openModal(id, title) {
            const modal = document.getElementById('modal-' + id);
            const modalImg = document.getElementById('modal-img-' + id);
            const modalTitle = document.getElementById('modal-title-' + id);

            // Determine the image to display
            const imageSrc = event.target.src;

            // Set modal content
            modalImg.src = imageSrc;
            modalTitle.innerText = title;

            // Show modal
            modal.style.display = 'flex';
        }

        function closeModal(id) {
            const modal = document.getElementById('modal-' + id);
            modal.style.display = 'none';

            // Clear the modal content
            const modalImg = document.getElementById('modal-img-' + id);
            modalImg.src = '';
        }