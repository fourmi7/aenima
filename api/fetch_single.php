<?php
include('../core/db.php');
include('../core/function.php');
if(isset($_POST["p_id"]))
{
	$output = array();
	$statement = $connection->prepare(
		"SELECT * FROM products 
		WHERE id = '".$_POST["p_id"]."' 
		LIMIT 1"
	);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		$output["product"] = $row["product"];
		$output["description"] = $row["description"];
		$output["price"] = $row["price"];
		if($row["image"] != '')
		{
			$output['product_image'] = '<img src="upload/'.$row["image"].'" class="img-thumbnail" width="250" height="235" /><input type="hidden" name="hidden_product_image" value="'.$row["image"].'" />';
		}
		else
		{
			$output['product_image'] = '<input type="hidden" name="hidden_product_image" value="" />';
		}
	}
	echo json_encode($output);
}
?>