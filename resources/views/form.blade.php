<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
	      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta name="author" content="Cali Castle (Xiaonan Guo)">

	<title>Coalition First Round Page</title>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">

	<script>
        document.documentElement.className = 'js';
	</script>

	<style>
		body, html {
			height: 100%;
		}

		body {
			display: flex;
			justify-content: center;
			align-items: center;
		}
	</style>
</head>
<body class="no-js">
<div class="container">
	<section>
		<div class="col-md-10 col-md-offset-1">
			<form action="{{ route('form') }}" class="form" method="POST">

				{{ csrf_field() }}
				<div class="form-group">
					<label for="product_name">Product name</label>
					<input type="text" class="form-control" id="product_name" name="product_name" required>
				</div>

				<div class="form-group">
					<label for="quantity">Quantity in stock</label>
					<input type="number" class="form-control" id="quantity" name="quantity" required>
				</div>

				<div class="form-group">
					<label for="price">Price per item</label>
					<input type="number" class="form-control" id="price" name="price" required>
				</div>

				<div class="form-group">
					<button type="submit" class="btn btn-block btn-primary">Submit</button>
				</div>
			</form>
		</div>
	</section>
	<section>
		<div class="col-md-10 col-md-offset-1">
			<table class="table table-striped">
				<thead>
				<tr>
					<th>
						#Product name
					</th>
					<th>
						Quantity
					</th>
					<th>
						Price per item
					</th>
					<th>
						Updated time
					</th>
					<th>
						Total value number
					</th>
				</tr>
				</thead>
				<tbody>
				@foreach(getSavedData() as $data)
					<tr>
						<th>{{ $data->product_name }}</th>
						<th>{{ $data->quantity }}</th>
						<th>{{ $data->price }}</th>
						<th>
							<time datetime="{{ $data->date_time }}">{{ \Carbon\Carbon::parse($data->date_time)->diffForHumans() }}</time>
						</th>
						<th>{{ $data->total_value_number }}</th>
						@php($total_value += (int) $data->total_value_number)
					</tr>
				@endforeach
				@if($total_value != 0)
				<tfoot>
				<tr class="success">
					<th>Total value number</th>
					<th></th>
					<th></th>
					<th></th>
					<th>{{ $total_value }}</th>
				</tr>
				</tfoot>
				@endif
				</tbody>
			</table>
		</div>
	</section>
</div>


	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script>
		$(function() {
			$("form").on('submit', (ev) => {
			    ev.preventDefault();
			    let form = ev.target;

				$.ajax({
					url: form.action,
					type: form.method,
					data: $(form).serialize(),
					success(data) {
						if (data.status == 'success') {
						    $('table').append(data.html);
						}
					},
					error() {
					    alert("Error when sending request.");
					}
				});
            });
		});
	</script>
</div>
</body>
</html>