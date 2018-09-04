<html>
	<head>
		<title>Producto</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
		<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
		<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>		
		<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
		<style>
			body
			{
				margin:0;
				padding:0;
				background-color:#f1f1f1;
			}
			.box
			{
				width:1270px;
				padding:20px;
				background-color:#fff;
				border:1px solid #ccc;
				border-radius:5px;
				margin-top:25px;
			}
		</style>
	</head>
	<body>
		<div class="container box">
			<h1 align="center">Lista de Producto</h1>
			<br />
			<div class="table-responsive">
				<br />
				<div align="right">
					<button type="button" id="add_button" data-toggle="modal" data-target="#productModal" class="btn btn-primary btn-lg">Agregar mas producto</button>
				</div>
				<br /><br />
				<table id="product_data" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th width="15%">Imagen</th>
							<th width="15%">Producto</th>
							<th width="35%">Descripcion</th>
							<th width="10%">Precio</th>
							<th width="10%">ver Producto</th>
							<th width="10%">Editar</th>
							<th width="10%">Borrar</th>
						</tr>
					</thead>
				</table>
				
			</div>
		</div>
	</body>
</html>

<div id="productModal" class="modal fade">
	<div class="modal-dialog">
		<form method="post" id="product_form" enctype="multipart/form-data">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Agregar Producto</h4>
				</div>
				<div class="modal-body">
					<label>Ingresar Nombre del Producto</label>
					<input type="text" name="product" id="product" class="form-control" />
					<br />
					<label>Ingresar Descripcion</label>
					<input type="text" name="description" id="description" class="form-control" />
					<br />
					<label>Ingresar Precio</label>
					<input type="text" name="price" id="price" class="form-control" />
					<br />
					<label>Seleccionar Imagen del Producto</label>
					<input type="file" name="product_image" id="product_image" />
					<span id="product_uploaded_image"></span>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="p_id" id="p_id" />
					<input type="hidden" name="operation" id="operation" />
					<input type="submit" name="action" id="action" class="btn btn-success" value="Agregar" />
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</form>
	</div>
</div>
<div id="viewProductModal" class="modal fade">
	<div class="modal-dialog">
		<form method="post" id="product_form" enctype="multipart/form-data">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" >Producto</h4>
				</div>
				<div class="modal-body">
					<span id="product_uploaded_image1" width="300" height="300"></span><br>
					<h3>Nombre de Producto : <span id="product1" style="color: green"></span></h3>
					<h4>Descripcion : <span id="description1" style="color: blue"></span> </h4>
					<h3 >Precio del Producto: $ <span id="price1" style="color: red"></span></h3>
				</div>
				<div class="modal-footer">
					<button type="button"  class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</form>
	</div>
</div>

<script type="text/javascript" language="javascript" >
$(document).ready(function(){
	$('#add_button').click(function(){
		$('#product_form')[0].reset();
		$('.modal-title').text("Agregar Producto");
		$('#action').val("Agregar");
		$('#operation').val("Agregar");
		$('#product_uploaded_image').html('');
	});
	
	var dataTable = $('#product_data').DataTable({
		"processing":true,
		"serverSide":true,
		"order":[],
		"ajax":{
			url:"api/fetch.php",
			type:"POST"
		},
		"columnDefs":[
			{
				"targets":[0, 3, 4],
				"orderable":false,
			},
		],

	});

	$(document).on('submit', '#product_form', function(event){
		event.preventDefault();
		var productName = $('#product').val();
		var productDesc = $('#description').val();
		var productPrice = $('#price').val();
		var extension = $('#product_image').val().split('.').pop().toLowerCase();
		if(extension != '')
		{
			if(jQuery.inArray(extension, ['gif','png','jpg','jpeg']) == -1)
			{
				alert("Imagen Invalido");
				$('#product_image').val('');
				return false;
			}
		}	
		if(productName != '' && productDesc != '' && productPrice != '')
		{
			$.ajax({
				url:"api/insert.php",
				method:'POST',
				data:new FormData(this),
				contentType:false,
				processData:false,
				success:function(data)
				{
					alert(data);
					$('#product_form')[0].reset();
					$('#productModal').modal('hide');
					dataTable.ajax.reload();
				}
			});
		}
		else
		{
			alert("Todos los campos son obligatorios");
		}
	});
	
	$(document).on('click', '.update', function(){
		var p_id = $(this).attr("id");
		$.ajax({
			url:"api/fetch_single.php",
			method:"POST",
			data:{p_id:p_id},
			dataType:"json",
			success:function(data)
			{
				$('#productModal').modal('show');
				$('#product').val(data.product);
				$('#description').val(data.description);
				$('#price').val(data.price);
				$('.modal-title').text("Editar Producto");
				$('#p_id').val(p_id);
				$('#product_uploaded_image').html(data.product_image);
				$('#action').val("Editar");
				$('#operation').val("Editar");
			}
		})
	});
	/*show One Product in Modal popup*/
	$(document).on('click', '.view', function(){
		var p_id = $(this).attr("id");
		$.ajax({
			url:"api/fetch_single.php",
			method:"POST",
			data:{p_id:p_id},
			dataType:"json",
			success:function(data)
			{
				$('#viewProductModal').modal('show');
				$('#product1').html(data.product);
				$('#description1').html(data.description);
				$('#price1').html(data.price);
				$('.modal-title').text("Editar Producto");
				$('#p_id').text(p_id);
				$('#product_uploaded_image1').html(data.product_image);
			}
		})
	});
	
	$(document).on('click', '.delete', function(){
		var p_id = $(this).attr("id");
		if(confirm("¿Seguro quieres Borrar este Producto?"))
		{
			$.ajax({
				url:"api/delete.php",
				method:"POST",
				data:{p_id:p_id},
				success:function(data)
				{
					alert(data);
					dataTable.ajax.reload();
				}
			});
		}
		else
		{
			return false;	
		}
	});
	
	
});
</script>