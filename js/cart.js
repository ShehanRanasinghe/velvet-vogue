function addToCart(product) //product object as an argument & contains data like ID,Name,Price, etc.
{
    fetch('addtocart.php', //Fetch API to send request to the server
    {
        method: 'POST',
        headers: 
        {
            'Content-Type': 'application/x-www-form-urlencoded',
        },

        //Converts the $product (product) object into a URL-encoded query string
        //Ex: id=001&name=Jean&price=1200
        body: new URLSearchParams(product) 
    })
    .then(res => res.json()) //Wait for the response of server and convert into JSON format
    .then(data => {
        alert(data.message);  //Message of the server shows as a pop-up alert
    });
}
