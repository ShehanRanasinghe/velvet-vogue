paypal.Buttons(
    {
        style:  //Change Styles for PayPal Buttons
        {
            layout: 'vertical',
            color:  'gold',
            shape:  'pill',
            label:  'paypal'
        },
        createOrder: function(data, actions) 
        {
            return actions.order.create
            (
                {
                    purchase_units: 
                    [
                        {
                            amount: 
                            {
                                value:TotAmount // Using Global Varible to get TotalAmount
                            }
                        }
                    ],
                    application_context: 
                    {
                        shipping_preference: 'NO_SHIPPING' //Disbale Shipping Option
                    }
                }
            );
        },

        onApprove: function(data, actions) 
        {
            return actions.order.capture().then
            (
                function(details) 
                    {
                        //Display alert to show payer first name to confirm the transcation
                        alert('Transaction completed by ' + details.payer.name.given_name); 

                        //Redirect to invoice.php and pass the OrderID in the URL
                        window.location.href = 'invoice.php?order_id=' + data.orderID; 
                    }                                    //'data.orderID' get the unique order ID returned by PayPal
            );
        }
    }
).render('#paypal-button-container');