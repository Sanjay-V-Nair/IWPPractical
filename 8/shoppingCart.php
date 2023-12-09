<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Dummy product data (you can replace this with a database)
$products = array(
    '1' => array('name' => 'Product 1', 'price' => 10),
    '2' => array('name' => 'Product 2', 'price' => 20),
    '3' => array('name' => 'Product 3', 'price' => 30),
);

// Handle add to cart action
if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Add the product to the cart
    if (isset($products[$productId])) {
        if (isset($_SESSION['cart'][$productId])) {
            // Product is already in the cart, update quantity
            $_SESSION['cart'][$productId]['quantity'] += $quantity;
        } else {
            // Product is not in the cart, add it
            $_SESSION['cart'][$productId] = array(
                'name' => $products[$productId]['name'],
                'price' => $products[$productId]['price'],
                'quantity' => $quantity,
            );
        }
    }
}

// Handle update cart action
if (isset($_POST['action']) && $_POST['action'] === 'update') {
    foreach ($_POST['cart'] as $productId => $quantity) {
        // Update the quantity for each product in the cart
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] = $quantity;
        }
    }
}

// Handle clear cart action
if (isset($_POST['action']) && $_POST['action'] === 'clear') {
    // Clear the entire cart
    $_SESSION['cart'] = array();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            text-align: center;
            margin: 50px;
        }

        table {
            width: 50%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 15px;
            text-align: left;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }

        #cart-form {
            max-width: 400px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <h1>Shopping Cart</h1>

    <!-- Display products -->
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $productId => $product) : ?>
                <tr>
                    <td><?php echo $product['name']; ?></td>
                    <td>$<?php echo $product['price']; ?></td>
                    <td>
                        <form action="" method="post">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                            <input type="number" name="quantity" value="1" min="1">
                            <button type="submit">Add to Cart</button>
                        </form>
                    </td>
                    <td></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Display shopping cart -->
    <h2>Shopping Cart</h2>
    <form id="cart-form" action="" method="post">
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['cart'] as $productId => $item) : ?>
                    <tr>
                        <td><?php echo $item['name']; ?></td>
                        <td>$<?php echo $item['price']; ?></td>
                        <td>
                            <input type="number" name="cart[<?php echo $productId; ?>]" value="<?php echo $item['quantity']; ?>" min="1">
                        </td>
                        <td>$<?php echo $item['price'] * $item['quantity']; ?></td>
                        <td><button type="button" onclick="removeItem('<?php echo $productId; ?>')">Remove</button></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button type="submit" name="action" value="update">Update Cart</button>
        <button type="submit" name="action" value="clear">Clear Cart</button>
    </form>

    <script>
        function removeItem(productId) {
            if (confirm('Are you sure you want to remove this item from the cart?')) {
                document.getElementById('cart-form').action = '';
                document.getElementById('cart-form').submit();
            }
        }
    </script>
</body>
</html>
