<?php
function getRandomProducts($products, $limit = 4) {
    shuffle($products); // Randomize the products
    return array_slice($products, 0, $limit); // Get limited products
}
?>
