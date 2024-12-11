$(document).ready(function() {
    $(document).on('click', 'button.closeModal', function() {
        $('.secondModal').modal('hide');
    });

    document.addEventListener('livewire:initialize', function () {
        Livewire.on('dataSaved', function () {
            // Usando Alpine.js para clicar no elemento
            document.getElementById('closeModal').click();
        });
    });
});
