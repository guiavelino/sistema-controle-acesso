document.addEventListener('DOMContentLoaded', function() {
    let calendarEl = document.getElementById('calendar');
    
    let calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'pt-br',
        plugins: ['interaction', 'dayGrid', 'list'],
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,listMonth'
        },
        
        eventTimeFormat: { // Formatação da data do evento
            hour: '2-digit',
            minute: '2-digit',
            meridiem: false
        },
        displayEventEnd: true, //Exibindo hora de término do evento 
        businessHours: true, //Cor personalizada para Fins de semana
        editable: true, 
        height: 1000, //Altura do calendário
        selectLongPressDelay: 0, //Tempo de ação após o toque para dispositivos móveis        
        eventLimit: true, //Limitando visualização da quantidade de eventos por data
        events: '../php/list_events.php', // Eventos
        eventTextColor: "#FFFFFF", //Cor do texto do evento
        extraParams: function () {
            return {
                cachebuster: new Date().valueOf()
            };
        },
    
        // Visualizando detalhes do evento
        eventClick: function (info) {
            location.href = `/view_event?id_evento=${info.event.id}`
        },

        // Início e fim do evento apresentados no formulário de cadastro, definidos baseados na data selecionada
        selectable: true,
        select: function (info) {
            let format = {year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit'}
            $('#cadastrar #inicio_evento').val(info.start.toLocaleString('pt-BR', format));
            $('#cadastrar #fim_evento').val(info.start.toLocaleString('pt-BR', format));
            $('#cadastrar').modal('show');
        }
    });
        calendar.render();
});
        
//Máscara para o campo data e hora
function DataHora(evento, objeto) {
    var keypress = (window.event) ? event.keyCode : evento.which;
    campo = eval(objeto);
    if (campo.value == '00/00/0000 00:00') {
        campo.value = "";
    }

    caracteres = '0123456789';
    separacao1 = '/';
    separacao2 = ' ';
    separacao3 = ':';
    conjunto1 = 2;
    conjunto2 = 5;
    conjunto3 = 10;
    conjunto4 = 13;
    conjunto5 = 16;
    if ((caracteres.search(String.fromCharCode(keypress)) != -1) && campo.value.length <= (16)) {
        if (campo.value.length == conjunto1)
            campo.value = campo.value + separacao1;
        else if (campo.value.length == conjunto2)
            campo.value = campo.value + separacao1;
        else if (campo.value.length == conjunto3)
            campo.value = campo.value + separacao2;
        else if (campo.value.length == conjunto4)
            campo.value = campo.value + separacao3;
    } else {
        event.returnValue = false;
    }
}
