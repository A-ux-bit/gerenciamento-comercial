 //Funções gerais podem ser adicionadas aqui
document.addEventListener('DOMContentLoaded', function() {
    // Ativar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
     //Mostrar mensagens de sessão
    if (document.querySelector('.alert')) {
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                var fade = new bootstrap.Alert(alert);
                fade.close();
            });
        }, 5000);
    }
});