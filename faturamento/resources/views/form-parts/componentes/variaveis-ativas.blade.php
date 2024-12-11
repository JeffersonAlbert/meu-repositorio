@php
    // Lista de variáveis possíveis
    /*$variables = collect(['contasReceber', 'contasAPagar', 'vendas']);
	foreach ($variables as $var) {
        if (isset($$var) && !is_null($$var)) {
            $activeVariable = $var;
            break;
        }
    }
	$variable = $$activeVariable ?? null;*/
	
    // Verifica se a variável está definida após o componente
    $variable = $contasReceber ?? $contasAPagar ?? $vendas ?? null;

@endphp