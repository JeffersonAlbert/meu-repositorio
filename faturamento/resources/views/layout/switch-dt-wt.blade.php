<script>
    function initializeDarkModeToggle() {
        const switchInput = document.getElementById('customSwitch1');
        const dayNightImage = document.querySelectorAll('.col-5 img')[0];
        const modeImage = document.querySelectorAll('.col-5 img')[1];
        const linkElement = document.querySelector('link[href*="sb-admin-2"]');

        if (!switchInput || !dayNightImage || !modeImage || !linkElement) return;

        // Função para trocar o estilo do link e as imagens
        function toggleMode(isDarkMode) {
            if (isDarkMode) {
                linkElement.href = linkElement.href.replace('sb-admin-2.css', 'sb-admin-2-dt.css');
                dayNightImage.src = "{{ asset('img/Moon.svg') }}";
                modeImage.src = "{{ asset('img/Dark Mode.svg') }}";
            } else {
                linkElement.href = linkElement.href.replace('sb-admin-2-dt.css', 'sb-admin-2.css');
                dayNightImage.src = "{{ asset('img/sun.svg') }}";
                modeImage.src = "{{ asset('img/White Mode.svg') }}";
            }

            // Armazenar o estado do modo no armazenamento local
            localStorage.setItem('darkMode', isDarkMode);
        }

        // Verificar se há um estado de modo armazenado e aplicar o estilo correspondente ao carregar a página
        const darkModeState = localStorage.getItem('darkMode');
        if (darkModeState !== null) {
            toggleMode(JSON.parse(darkModeState));
            // Atualiza o estado do switchInput com base no que está armazenado no Local Storage
            switchInput.checked = JSON.parse(darkModeState);
        }

        // Adiciona um ouvinte de evento para detectar mudanças no botão de alternância
        switchInput.addEventListener('change', function () {
            toggleMode(switchInput.checked); // Troca o estilo e as imagens com base no estado do botão
        });
    }

    // Executa a função ao carregar o DOM
    document.addEventListener('DOMContentLoaded', initializeDarkModeToggle);

    // Executa a função após qualquer atualização Livewire
    document.addEventListener('livewire:load', initializeDarkModeToggle);
    document.addEventListener('livewire:update', initializeDarkModeToggle);
</script>
