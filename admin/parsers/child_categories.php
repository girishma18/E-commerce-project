<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/svdjm/core/init.php';
$parentid= (int)$_POST['parentid'];
$selected = sanitize($_POST['selected']);
$childquery = $db->query("SELECT * FROM categories WHERE parent='$parentid' ORDER BY category");
ob_start();
?>
<option value=""> </option>
<?php while($child = mysqli_fetch_assoc($childquery)): ?>
<option value="<?=$child['id']?>" <?=(($selected == $child['id'])?'selected':'');?>> <?=$child['category']?> </option>

<?php endwhile; ?>
<?php echo ob_get_clean();?>