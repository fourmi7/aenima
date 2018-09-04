<?php
include('../core/db.php');
include('../core/function.php');
if(isset($_POST["operation"]))
{
	if($_POST["operation"] == "Agregar")
	{
		$image = '';
		if($_FILES["product_image"]["name"] != '')
		{
			$image = upload_image();
		}
		$statement = $connection->prepare("
			INSERT INTO products (product, description, price, image) 
			VALUES (:product, :description, :price, :image)
		");
		$result = $statement->execute(
			array(
				':product'	=>	$_POST["product"],
				':description'	=>	$_POST["description"],
				':price'	=>	$_POST["price"],
				':image'		=>	$image
			)
		);
		if(!empty($result))
		{
			echo 'Producto Inserted';
		}
	}
	if($_POST["operation"] == "Editar")
	{
		$image = '';
		if($_FILES["product_image"]["name"] != '')
		{
			$image = upload_image();
		}
		else
		{
			$image = $_POST["hidden_product_image"];
		}
		$statement = $connection->prepare(
			"UPDATE products 
			SET product = :product, description = :description, price = :price, image = :image  
			WHERE id = :id
			"
		);
		$result = $statement->execute(
			array(
				':product'	=>	$_POST["product"],
				':description'	=>	$_POST["description"],
				':price'	=>	$_POST["price"],
				':image'		=>	$image,
				':id'			=>	$_POST["p_id"]
			)
		);
		if(!empty($result))
		{
			echo 'Producto Actualizado';
		}
	}
}

?>