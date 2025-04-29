// Edit User Modal
const modal = document.getElementById("UserModal");
const form = document.getElementById("editUserForm");
const overlay = document.getElementById('overlay');

// Open modal and fill data
document.querySelectorAll('.editUserDetails').forEach(button => {
  button.addEventListener("click", function () {
    modal.style.display = "block";
    overlay.style.display = 'block';

    // Set form values
    document.getElementById('editUserId').value = this.dataset.user_id;
    document.getElementById('editFullName').value = this.dataset.fullname;
    document.getElementById('editPhone').value = this.dataset.phone;
    document.getElementById('editAddress').value = this.dataset.address;
    document.getElementById('editCity').value = this.dataset.city;
    document.getElementById('editPostal').value = this.dataset.postal;
    document.getElementById('editCountry').value = this.dataset.country;
  });
});

// Close modal and reset
window.closeModal = function () {
  modal.style.display = "none";
  overlay.style.display = 'none';
  form.reset();
  location.reload(); // optional reload
};


// Submit user update form
form.addEventListener("submit", function (e) {
  e.preventDefault();
  const formData = new FormData(form);

  fetch("user_details_update.php", {
    method: "POST",
    body: formData
  })
    .then(response => response.text())
    .then(data => {
      alert(data);
      closeModal();
      location.reload(); // Reload
    })
    .catch(error => {
      console.error("Error:", error);
      alert(data);
    });
});


