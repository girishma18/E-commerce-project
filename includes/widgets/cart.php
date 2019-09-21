<h5 class="text-center">Shopping Cart</h5>
<div>
    <?php if (empty($cart_id)) : ?>
        <h5>Your shopping cart is empty.</h5>
    <?php else : 
        $cartQ = $db->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
        $results = mysqli_fetch_assoc($cartQ);
        $items = json_decode($results['items'], true);
        $sub_total = 0;
    ?>
        <table class="table table-condensed table-responsive" id="cart_widget">
           <thead>
               <tr>
                   <td><h5>Q</h5></td>
                   <td ><h5>Name</h5></td>
                   <td><h5>Price</h5></td>
               </tr>
           </thead>
            <tbody>
                <?php foreach ($items as $item) : 
                    $productQ = $db->query("SELECT * FROM products WHERE id = '{$item['id']}'");
                    $product = mysqli_fetch_assoc($productQ);
                ?>
                <tr>
                    <td><h5><?= $item['quantity']; ?></h5></td>
                    <td><h6><?= substr($product['title'],0,12); ?></h6></td>
                    <td><h6><?= $item['quantity'] * $product['price']; ?> Rs</h6></td>
                </tr>
                <?php 
                    $sub_total += ($item['quantity'] * $product['price']);
                endforeach; ?>
                <tr>
                    <td></td>
                    <td><h6>Sub Total</h6></td>
                    <td><h6><?= $sub_total; ?> Rs</h6></td>
                </tr>
            </tbody>
        </table>
        <a href="cart.php" class="btn btn-sm btn-success pull-right">View Cart</a>
        <div class="clearfix"></div>
    <?php endif; ?>
</div>