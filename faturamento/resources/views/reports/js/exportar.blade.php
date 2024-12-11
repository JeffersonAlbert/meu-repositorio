<script>
    $(document).ready(function(){
        $("#exportarPdf").on('click', function(){
            console.log('exportar pdf');
            var content = document.getElementById('exportar').innerHTML;
            console.log(content);
            var url = '{{ route('pdf-dre') }}';
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Certifique-se de incluir o token CSRF
                },
                body: JSON.stringify({ content: content })
            })
                .then(response => response.blob())
                .then(blob => {
                    // Cria um link para download do PDF gerado
                    var url = window.URL.createObjectURL(blob);
                    var a = document.createElement('a');
                    a.href = url;
                    a.download = 'relatorio.pdf';
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                });
        });
    });
</script>
