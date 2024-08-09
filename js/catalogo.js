document.addEventListener('DOMContentLoaded', function() {
    let popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));

    popoverTriggerList.forEach(function(popoverTriggerEl) {
        let popover = new bootstrap.Popover(popoverTriggerEl, {
            trigger: 'manual'
        });

        popoverTriggerEl.addEventListener('mouseenter', function() {
            popover.show();
        });

        popoverTriggerEl.addEventListener('mouseleave', function() {
            popover.hide();
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    tippy('#checkboxSemValores', {
            content: 'Deixar a Tabela sem Valores?', 
            placement: 'left', 
            theme: 'light',  
            maxWidth: '200px', 
        });
    });