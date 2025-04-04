//contact.php validation
document.querySelector('form').addEventListener('submit', function(event) {
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const message = document.getElementById('message').value;

    if (!name || !email || !message) {
        alert('All Fields are Required!');
        event.preventDefault(); // If validation fails prevent form submission
    }


    // Email format validation
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) 
            {
                alert("Please Enter a valid E-Mail Address");
                return;
            }
});