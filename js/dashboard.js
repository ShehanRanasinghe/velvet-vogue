// Open Modal and Fill Data
function openEditModal(data, type) {
  document.getElementById(type + 'Modal').style.display = 'block';
  document.getElementById('overlay').style.display = 'block';
  
  document.getElementById('edit' + type + 'Id').value = data.id;
  document.getElementById('editUsername').value = data.username;
  document.getElementById('editEmail').value = data.email;
  document.getElementById('editRole').value = data.role;
}

// Close Modal
function closeModal(type) {
  document.getElementById(type + 'Modal').style.display = 'none';
  document.getElementById('overlay').style.display = 'none';
}

// Attach Edit Button Event Listeners
document.querySelectorAll('.editBtn').forEach(button => {
  button.addEventListener('click', function() {
      const type = this.dataset.type; // e.g., "User"
      openEditModal(this.dataset, type);
  });
});

// Save Edited User (keep this only if you have one type of form)
document.getElementById('editUserForm').addEventListener('submit', function(e) {
  e.preventDefault();
  let formData = new FormData(this);

  fetch('update_user.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.text())
  .then(data => {
    alert(data);
    closeModal('User');
    location.reload(); // Reload to show updated info
  });
});

// Delete User
document.querySelectorAll('.deleteBtn').forEach(button => {
  button.addEventListener('click', function() {
    if (confirm('Are you sure you want to delete this user?')) {
      fetch('delete_user.php?id=' + this.dataset.id)
      .then(response => response.text())
      .then(data => {
        alert(data);
        location.reload();
      });
    }
  });
});



// Open Modal and Fill Data
document.querySelectorAll('.editItemBtn').forEach(button => {
    button.addEventListener('click', function() {
        const itemData = {
            id: this.dataset.id,
            name: this.dataset.name,
            price: this.dataset.price
        };
        openEditModal(itemData, 'Item');
    });
});

// Function to open the modal and fill data
function openEditModal(data, type) {
    document.getElementById(type + 'Modal').style.display = 'block';
    document.getElementById('overlay').style.display = 'block'; // If you have an overlay

    document.getElementById('edit' + type + 'Id').value = data.id;
    document.getElementById('editItemName').value = data.name;
    document.getElementById('editItemPrice').value = data.price;
}

// Close Modal
function closeModal(type) {
    document.getElementById(type + 'Modal').style.display = 'none';
    document.getElementById('overlay').style.display = 'none'; // If you have an overlay
}

// Save Edited Item
document.getElementById('editItemForm').addEventListener('submit', function(e) {
    e.preventDefault();
    let formData = new FormData(this);

    fetch('update_product.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        closeModal('Item');
        location.reload(); // Reload to show updated info
    });
});

// Delete Item
document.querySelectorAll('.deleteItemBtn').forEach(button => {
    button.addEventListener('click', function() {
        if (confirm('Are you sure you want to delete this item?')) {
            fetch('delete_product.php?id=' + this.dataset.id)
            .then(response => response.text())
            .then(data => {
                alert(data);
                location.reload();
            });
        }
    });
});

