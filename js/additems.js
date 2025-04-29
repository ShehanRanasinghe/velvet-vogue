  const modal = document.getElementById("addItemModal");
  const addBtn = document.getElementById("additemsbtn");
  const form = document.getElementById("addItemForm");

// Open Pop-up window
  addBtn.addEventListener
  ("click", () => 
    {
      modal.style.display = "block";
      // Show the background overlay to make screen look dim or blurred
      document.getElementById('overlay').style.display = 'block';
    }
  );

// Close the Pop-Up
  window.closeAddItem = function() 
  {
    modal.style.display = "none";

    // Show the background overlay to make screen look dim or blurred
    document.getElementById('overlay').style.display = 'none';
    location.reload(); // Reload
    form.reset();
  }

// Save the data into the products table
  form.addEventListener
  ("submit", function(e) 
    {
      e.preventDefault();

      const formData = new FormData(form);

      fetch
      ("product_add.php", 
        {
          method: "POST",
          body: formData
        }
      )

      .then(response => response.text())

      .then
      (data => 
        {
          alert("Item Added successfully!");
          closeAddItem();
          location.reload(); // Reload
        }
      )
      .catch
      (error => 
        {
          console.error("Error:", error);
          alert("There was an error adding the item.");
          location.reload(); // Reload
        }
      );
    }
  );



