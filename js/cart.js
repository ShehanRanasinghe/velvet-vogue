function PopUpCheckOut() //The PopUpCheckOut() using in checkout button on cart.php
{
    fetch('cart_user_details_check.php') //Fetch API to send request to the server
        .then(response => response.text())
        .then
        (result => 
            {
                if (result === 'COMPLETED') 
                    {
                        window.open('checkout.php', 'popupWindow', 'width=600,height=600,scrollbars=yes'); //pop-up the checkout.php
                    } 
                    else if (result === 'NOT_FOUND') 
                    {
                        alert('Please, First Complete your details in your account for Placed Order.');
                        window.location.href = 'user_dashboard.php';
                    }
            }
        )
    .catch(error => console.error('Error:', error));
}

