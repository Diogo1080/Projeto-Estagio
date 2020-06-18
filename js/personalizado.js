
function dateTime_now() {
    return new Date()
}
function transformar_data(data) {
    let nova_data = new Date(data);
    nova_data = nova_data.getDate()+"/"+(nova_data.getMonth()+1)+"/"+nova_data.getFullYear()+", "+nova_data.getHours()+":"+nova_data.getMinutes()+":"+nova_data.getSeconds()
    return nova_data
}
//Começa o tempo
let data_final
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
                                calendar.changeView('timeGridDay',dateString)
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
        slotDuration: '00:15:00',
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
                            
                            $("#nav_treinos, #nav_jogos").removeClass("active disabled");

                            if (info.event.extendedProps.tipo=="Treino") {
                                $("#nav_treinos").addClass("active")
                                $("#nav_jogos").addClass("disabled")
                            }else{
                                $("#nav_treinos").addClass("disabled")
                                $("#nav_jogos").addClass("active")
                            }

                            $('#treino_update').attr({disabled: false, style: 'display:block'})
                            $('#treino_insert').attr({disabled: true, style: 'display:none'})

                            $('#treino_id').val(info.event.id)
                            $('#treino_titulo').val(info.event.title)
                            $('#treino_input_color').val(info.event.color)
                            $('#treino_select_equipa').val(info.event.extendedProps.id_equipa)
                            $('#treino_dt_inicio').val(info.event.start.toLocaleString())
                            $('#treino_dt_fim').val(info.event.end.toLocaleString())

                            buscar_atletas_treino(info.event.extendedProps.id_equipa,info.event.end,info.event.id)
                            
                            $('#modal_calendario').modal('show')
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
                
                $("#nav_treinos, #nav_jogos").removeClass("active disabled")

                $("#nav_treinos").addClass("active")
                
                $('#jogo_update').attr({disabled: true, style: 'display:none'})
                $('#jogo_insert').attr({disabled: false, style: 'display:block'})
                $('#treino_update').attr({disabled: true, style: 'display:none'})
                $('#treino_insert').attr({disabled: false, style: 'display:block'})

                $('#jogo_id').val('')
                $('#jogo_dt_fim').val(info.end.toLocaleString())
                $('#jogo_dt_inicio').val(info.start.toLocaleString())
                $('#treino_id').val('')
                $('#treino_dt_fim').val(info.end.toLocaleString())
                $('#treino_dt_inicio').val(info.start.toLocaleString())
                $('#modal_calendario').modal('show')

                document.getElementById("treino_select_equipa").options.selectedIndex = 0
                data_final=info.end
                buscar_atletas_treino('','','')

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

    $("#treino").on("submit", (event) => {
        event.preventDefault();
        
        var form = new FormData($("#treino")[0])

        if (event.originalEvent.submitter.id=="treino_insert") {
            $.ajax({
                method: "POST",
                url: "calend_insert.php",
                data: form,   
                contentType: false,
                processData: false,
                success: (response) => {
                    $('#modal_calendario').modal('hide')
                    document.getElementById("treino_titulo").value=""
                    document.getElementById("treino_color").style.color=""
                    document.getElementById("warning").style.display='block'
                    $('#warning').html(response)
                    calendar.refetchEvents()
                    setTimeout(() => { document.getElementById("warning").style.display='none' }, 3000)
                }
            })
        }else if(event.originalEvent.submitter.id=="treino_update"){
            $.ajax({
                method: "POST",
                url: "calend_update.php",
                data: form,
                contentType: false,
                processData: false,
                success: (response) => {
                    $('#modal_calendario').modal('hide')
                    document.getElementById("treino_titulo").value=""
                    document.getElementById("treino_color").style.color=""
                    document.getElementById("warning").style.display='block'
                    $('#warning').html(response)
                    calendar.refetchEvents()
                    setTimeout(() => { document.getElementById("warning").style.display='none' }, 3000)
                }
            })
        }
    })
    
    $('.btn-canc-vis').on("click", () => {
        $('.visevent').slideToggle();
        $('.formedit').slideToggle();
    });
    
    $('.btn-canc-edit').on("click", () => {
        $('.formedit').slideToggle();
        $('.visevent').slideToggle();
    });
    
})