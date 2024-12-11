@extends('layout.newLayout')

@section('content')
		<div class="row mb-3">
			<div class="col-9"></div>
			<div class="col-3 text-right">
				<a href="{{ route('produto.create') }}" class="btn btn-sm btn-success btn-success-number">
					<i class="bi bi-plus"></i>
					Novo
				</a>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<table class="table table-responsive-sm table-head-number table-bordered">
					<thead class="head-grid-data" style="background-color: #e9e9e9">
						<tr>
							<th>Nome</th>
							<th>Preço</th>
							<th>Quantidade</th>
							<th>Descrição</th>
							<th>Ações</th>
						</tr>
					</thead>
					<tbody class="rel-tb-claro">
						@foreach($produtos as $produto)
							<tr class="td-grid-font align-middle">
								<td>{{ $produto->produto }}</td>
								<td>{{ $produto->valor }}</td>
								<td>{{ $produto->quantidade }}</td>
								<td>{{ $produto->codigo_produto }}</td>
								<td>
									<a href="{{ route('produto.edit', $produto->id) }}" class="btn btn-warning">Editar</a>
									<form action="{{ route('produto.destroy', $produto->id) }}" method="post" style="display: inline;">
										@csrf
										@method('DELETE')
										<button type="submit" class="btn btn-danger">Excluir</button>
									</form>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
@endsection
