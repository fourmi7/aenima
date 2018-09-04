<?php

include('../core/db.php');
include("../core/function.php");

if(isset($_POST["p_id"]))
{
	$image = get_image_name($_POST["p_id"]);
	if($image != '')
	{
		unlink("../upload/" . $image);
	}
	$statement = $connection->prepare(
		"DELETE FROM products WHERE id = :id"
	);
	$result = $statement->execute(
		array(
			':id'	=>	$_POST["p_id"]
		)
	);
	
	if(!empty($result))
	{
		echo 'Producto Borrado';
	}
}



?>