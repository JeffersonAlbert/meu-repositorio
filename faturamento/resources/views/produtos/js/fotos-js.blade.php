<script>
	let count = 1;
	let fileRemove = null;
	let row = null;
	
	$(document).on('change', '.row .file-upload', function(){
		var fileName = $(this).val().split('\\').pop();
		var fileSize = this.files[0].size;
		var fileSizeFormatted = formatBytes(fileSize);
		$(this).closest('.row').find('.file-name').text(fileName + ' (' + fileSizeFormatted + ')');
	});

	$(document).on('click', '#add-upload',function(e){
		e.preventDefault();
		console.log('clickou para adicionar upload');
		let newUpload =`<div class="row mt-1">
								<div class="form-group col-4">
									<label for="file-upload${count}" class="label-number">Arquivo</label>
									<label for="file-upload${count}" class="btn input-login btn btn-transparent col">
										<span class="file-name">Escolha o arquivo</span>
										<i class="bi bi-paperclip"></i>
									</label>
									<input id="file-upload${count}" type="file" name="files[]" class="d-none file-upload">
								</div>
								<div class="col-7">
									<label for="descricao_arquivo" class="label-number">Descrição</label>
									<input name="descricao_arquivo[]" id="descricao_arquivo" class="input-login form-control">
								</div>
								<div class="col-1">
								<label for="rm-upload" class="label-number">Remove</label>
									<button id="rm-upload" class="btn btn-md btn-success d-block">
										- 
									</button>
								</div>
							</div>`;
		$('#upload-adicionais').append(newUpload);
		count = count+1;
	});

	$(document).on('click', '.row #rm-upload', function(e){
		e.preventDefault();
		$(this).closest('.row').remove();
	});
</script>