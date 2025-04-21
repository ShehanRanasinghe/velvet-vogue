function resetPassword()
{
    const email = prompt("Please Enter Your Registered Email Address");

    if (email)
    {
        fetch
        ('reset_link.php',
            {
                method: 'POST',
                headers: 
                {
                    'Content-Type': 'application/x-www-form-urlencoded' //Content type send to the server
                },

                body: 'email=' + encodeURIComponent(email) 
            }
        )

        .then(response => response.text())  //Server message convert into plain text
        .then
            (data =>
                {
                   alert(data); //Display Message From the Server 
                }
            )
        .catch
            (error => 
                {
                    console.error('Error:', error);
                    alert("A failure to work properly while sending the reset link.");
                }
            );
    } 
    else 
    {
         alert("Required the E-Mail address to Reset Password.");
    }
}
