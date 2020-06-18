function dateTime_now() {
    return new Date()
}
function transformar_data(data) {
    let nova_data = new Date(data);
    nova_data = nova_data.getDate()+"/"+(nova_data.getMonth()+1)+"/"+nova_data.getFullYear()+", "+nova_data.getHours()+":"+nova_data.getMinutes()+":"+nova_data.getSeconds()
    return nova_data
}
//Começa o tempo
let Now = dateTime_now()
let picker
let clickCnt = 0
let calendar
document.addEventListener('DOMContentLoaded', () => {
    let calendarEl = document.getElementById('calendar');
    calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'pt-br',
        customButtons: {
            datepicker: {
                text: 'Escolher data',
                click: () => {
                    if (picker == null) {
                        picker = new Pikaday({
                            field: document.querySelector('.fc-datepicker-button'),
                            format: 'yy-mm-dd',
                            onSelect: (dateString) => {
                                calendar.gotoDate(dateString)
                                picker = null
                            }
                        })
                    }
                    picker.show()
                }
            }
        },
        lazyFetching: true,
        selectable: true,
        contentHeight: 420,
        aspectRatio: 0.5, 
        defaultView: 'dayGridMonth', 
        defaultDate: Now, 
        minTime: '07:30:00',
        maxTime: '21:30:00',
        slotDuration: '00:30:00',
        slotLabelInterval: 15,
        plugins: ['interaction', 'dayGrid', 'timeGrid', 'bootstrap'],
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'datepicker dayGridMonth,dayGridWeek,timeGridDay'
          },  
        weekNumbers: true,
        themeSystem: 'bootstrap',
        nowIndicator: true,
        navLinks: true,
        editable: true,
        eventLimit: true,
        events: 'calend_list_eventos.php',
        extraParams: () => {
            return {
                cachebuster: new Date().valueOf()
            };
        },
        eventRender: (info,event,element) => {
            //Handles double click e o click
            info.el.addEventListener('click', 
                () => {
                    clickCnt++;     
                    //Clicou 1 vez  
                    if (clickCnt === 1) {
                        oneClickTimer = setTimeout(function() {
                            clickCnt = 0;
                            alert("single");  
                        }, 220);
                    } else if (clickCnt === 2) {
                        clearTimeout(oneClickTimer)
                        clickCnt = 0
                        Now = dateTime_now()
                        alert("double")
                    }   
                }
            )
        },
        select: (info) => {
            if (calendar.view.type === "dayGridMonth") {
                calendar.changeView('timeGridDay',info.startStr)
            } else {
                buscar_atletas('')
                document.getElementById("select_equipa").options.selectedIndex = 0
                $('#cadastrar #start').val(info.start.toLocaleString())
                $('#cadastrar #end').val(info.end.toLocaleString())
                $('#cadastrar').modal('show')
            }
        },
        eventDrop : (info) => {
            let dt_inicio = transformar_data(info.event.start)
            let dt_fim = transformar_data(info.event.end)
            $.post(
                'calend_update.php', 
                {
                    'id': info.event.id,
                    'titulo': info.event.title,
                    'cor': info.event.backgroundColor,
                    'dt_inicio': dt_inicio,
                    'dt_fim':dt_fim
                }, 
                (response) => {
                    $('#warning').html(response)
                    setTimeout(() => { document.getElementById("warning").style.display='none' }, 3000)
                }
            )
        },
        /*eventClick: (info) => {
            $("#apagar_evento").attr("href", "proc_apagar_evento.php?id=" + info.event.id)
            info.jsEvent.preventDefault() // don't let the browser navigate
            console.log(info.event)
            $('#visualizar #id').text(info.event.id)
            $('#visualizar #id').val(info.event.id)
            $('#visualizar #title').text(info.event.title)
            $('#visualizar #title').val(info.event.title)
            $('#visualizar #start').text(info.event.start.toLocaleString())
            $('#visualizar #start').val(info.event.start.toLocaleString())
            $('#visualizar #end').text(info.event.end.toLocaleString())
            $('#visualizar #end').val(info.event.end.toLocaleString())
            $('#visualizar #color').val(info.event.backgroundColor)
            $('#visualizar').modal('show')
        },
        select: (info) => {
            //alert('Início do evento: ' + info.start.toLocaleString())
           
        }*/
    })
    calendar.render()
})

//Mascara para o campo data e hora
function DataHora(evento, objeto) {
    let keypress = (window.event) ? event.keyCode : evento.which
    let campo = eval(objeto)
    if (campo.value === '00/00/0000 00:00:00') {
        campo.value = ""
    }
    let caracteres = '0123456789'
    let separacao1 = '/'
    let separacao2 = ' '
    let separacao3 = ':'
    let conjunto1 = 2
    let conjunto2 = 5
    let conjunto3 = 10
    let conjunto4 = 13
    let conjunto5 = 16
    if ((caracteres.search(String.fromCharCode(keypress)) !== -1) && campo.value.length < (19)) {
        if (campo.value.length === conjunto1)
            campo.value = campo.value + separacao1
        else if (campo.value.length === conjunto2)
            campo.value = campo.value + separacao1
        else if (campo.value.length === conjunto3)
            campo.value = campo.value + separacao2
        else if (campo.value.length === conjunto4)
            campo.value = campo.value + separacao3
        else if (campo.value.length === conjunto5)
            campo.value = campo.value + separacao3
    } else {
        event.returnValue = false
    }
}

$(document).ready(function () {
    $("#addevent").on("submit", (event) => {
        event.preventDefault();
        $.ajax({
            method: "POST",
            url: "calend_insert.php",
            data: new FormData(this),
            contentType: false,
            processData: false,
            success: function (response) {
                $('#warning').html(response);
                $('#cadastrar').modal('hide');
                calendar.refetchEvents();
                setTimeout(() => { document.getElementById("warning").style.display='none' }, 3000)
            }
        })
    });
    
    $('.btn-canc-vis').on("click", () => {
        $('.visevent').slideToggle();
        $('.formedit').slideToggle();
    });
    
    $('.btn-canc-edit').on("click", () => {
        $('.formedit').slideToggle();
        $('.visevent').slideToggle();
    });
    
    $("#editevent").on("submit", (event) => {
        event.preventDefault()
        $.ajax({
            method: "POST",
            url: "edit_event.php",
            data: new FormData(this),
            contentType: false,
            processData: false,
            success: (retorna) => {
                if (retorna['sit']) {
                    //$("#msg-cad").html(retorna['msg'])
                    location.reload()
                } else {
                    $("#msg-edit").html(retorna['msg'])
                }
            }
        })
    })
})