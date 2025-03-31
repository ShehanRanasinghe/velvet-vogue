<!DOCTYPE html>
<html lang="en">
<head>
        <!--CSS Style File-->
        <link rel="stylesheet" href="css/products_list.css">
</head>
<body>
    
<div class="product-grid">
<?php
    $products = 
    [
        ["id" => 1,"name" => "Stylish T-shirt", "price" => 1200, "image" => "assets/t-shirt01.jpg"],
        ["id" => 2,"name" => "Floral Midi Dress", "price" => 2300, "image" => "assets/FloralMidiDress.jpg"],
        ["id" => 3,"name" => "Warm Jacket", "price" => 3400, "image" => "assets/jacket01.jpg"],
        ["id" => 4,"name" => "Crop Top Hoodie", "price" => 1400, "image" => "assets/CropTopHoodie.jpg"],
        ["id" => 5,"name" => "Crew Neck T-Shirt", "price" => 1000, "image" => "assets/t-shirt02.jpg"]
    ];

    function displayProducts($products) 
    {
        echo '<div class="product-grid">';
        foreach ($products as $product) 
        {
            echo '<div class="product-card">';
            echo '<img src="' . $product['image'] . '" alt="' . $product['name'] . '">';
            echo '<h3>' . $product['name'] . '</h3>';
            echo '<p>LKR' . number_format($product['price'], 2) . '</p>';
            echo '<button class="btn3" onclick="addToCart()">Add to Cart</button>';
            echo ' </div>';
        }
    }
?>
</div>

    <!--JavaScript File-->
    <script src="js/app.js" defer></script>
</body>
</html>