// Open Pop-up windows and Fill Data
function openEditModal(data, type) 
{
  //Get the correct pop-up windows based on the type parameter (UserModal or ItemModal)
    document.getElementById(type + 'Modal').style.display = 'block';

  // Show the background overlay to make screen look dim or blurred
    document.getElementById('overlay').style.display = 'block';

    // Show & Hide the Correct POP-UP Form When click edit button
    if (type === 'User') 
      {
        document.getElementById('editUserForm').style.display = 'block';  //Show user edit form
        document.getElementById('editItemForm').style.display = 'none';   //Hide item edit form

        document.getElementById('editUserId').value = data.id;   //Fill the textboxes with existing data
        document.getElementById('editUsername').value = data.username;
        document.getElementById('editEmail').value = data.email;
        document.getElementById('editRole').value = data.role;
      } 
      else if (type === 'Item') 
      {
        document.getElementById('editItemForm').style.display = 'block';
        document.getElementById('editUserForm').style.display = 'none';

        document.getElementById('editItemId').value = data.id;
        document.getElementById('editItemName').value = data.name;
        document.getElementById('editItemPrice').value = data.price;
      }
}


// Close the Pop-Up
function closeModal(type) 
{
  document.getElementById(type + 'Modal').style.display = 'none';
  document.getElementById('overlay').style.display = 'none';
}

// Add Edit Button Event Listeners for Users
document.querySelectorAll('.editUserBtn').forEach
(button => 
  {
    button.addEventListener
    ('click', function() 
      {
          const userData = 
          {
              id: this.dataset.id,
              username: this.dataset.username,
              email: this.dataset.email,
              role: this.dataset.role
          };
          openEditModal(userData, 'User');
      }
    );
  }
);

// Add Edit Button Event Listeners for Items
document.querySelectorAll('.editItemBtn').forEach
(button => 
  {
  button.addEventListener
    ('click', function() 
      {
          const itemData = 
          {
              id: this.dataset.id,
              name: this.dataset.name,
              price: this.dataset.price
          };
          openEditModal(itemData, 'Item');
      }
    );
  }
);

// Save the data when edited in user form
document.getElementById('editUserForm').addEventListener
('submit', function(e) 
  {
    e.preventDefault();
    let formData = new FormData(this);
    fetch
    ('user_update.php', 
      {
        method: 'POST',
        body: formData
      }
    )
    .then(response => response.text())
    .then
    (data => 
      {
        alert(data);
        closeModal('User');
        location.reload(); // Reload
      }
    );
  }
);

// Save the data when edited in item form
document.getElementById('editItemForm').addEventListener
('submit', function(e) 
  {
    e.preventDefault();
    let formData = new FormData(this);

    fetch  //Send Form data to the database using Fetch API
    ('product_update.php', 
      {
        method: 'POST',
        body: formData
      }
    )
    .then(response => response.text())
    .then
    (data => 
      {
        alert(data);
        closeModal('Item');
        location.reload(); // Reload
      }
    );
  }
);

// Delete user according to the ID
document.querySelectorAll('.deleteBtn').forEach
(button => 
  {
    button.addEventListener
    ('click', function() 
      {
        if (confirm('Do you want to Delete this user? (can not be undone)')) 
        {
          fetch('user_delete.php?id=' + this.dataset.id)  //Send Delete Request to the Database with user's ID
          .then(response => response.text())
          .then
          (data => 
            {
              alert(data);
              location.reload();
            }
          );
        }
      }
    );
  }
);

// Delete Item according to the Item ID
document.querySelectorAll('.deleteItemBtn').forEach
(button => 
  {
    button.addEventListener
    ('click', function() 
      {
        if (confirm('Do you want to Delete this Item? (can not be undone)')) 
        {
          fetch('product_delete.php?id=' + this.dataset.id)
          .then(response => response.text())
          .then
          (data => 
            {
              alert(data);
              location.reload();
            }
          );
        }
      }
    );
  }
);
