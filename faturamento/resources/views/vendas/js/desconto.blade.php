<script>
	$(document).ready(function(){
		$(document).on('click', ".btn-currency", function(e){
			e.preventDefault();
			$(".btn-currency").removeClass('btn-light');
			$(".btn-currency").addClass('btn-success');
			$(".btn-currency").addClass('ativo');
			$(".btn-percent").removeClass('btn-success');
			$(".btn-percent").addClass('btn-light');
			$(".btn-percent").removeClass('ativo');
			$(".input-group-text.desconto").text('R$');
            $("#tipo_desconto").val('currency');
		});

		$(document).on('click', ".btn-percent", function(e){
			e.preventDefault();
			$(".btn-percent").removeClass('btn-light');
			$(".btn-percent").addClass('btn-success');
			$(".btn-percent").addClass('ativo');
			$(".btn-currency").removeClass('btn-success');
			$(".btn-currency").addClass('btn-light');
			$(".btn-currency").removeClass('ativo');
			$(".input-group-text.desconto").text('%');
            $("#tipo_desconto").val('percent');
		});
	})
</script>
