/* Arquivo Java */

function validar(){
	let nome = form.nome.value
	let email = form.email.value
	let password = form.password.value
	// ...

	if (nome === "") {
		alert("Preencha o nome")
		document.form.nome.focus()
		return false
	}

	if (password === "" || password.length <= 6) {
		alert("A password deve ter no minimo 6 caracteres")
		document.form.password.focus()
		return false
	}

	if (email === "" || email.indexOf('@')=== -1 || email.indexOf('.') === - 1) {
		alert("Digite um email válido")
		document.form.email.focus()
		return false
	}
}

toastr.options = {
	"preventDuplicates": true,
	"hideMethod": "hide"
}

function sonumeros(e) {
        let charCode = e.charCode ? e.charCode : e.keyCode
        // charCode 8 = backspace   
        // charCode 9 = tab
        if (charCode !== 8 && charCode !== 9) {
            // charCode 48 equivale a 0   
            // charCode 57 equivale a 9
            if (charCode < 48 || charCode > 57) {
                return false
            }
        }
    }

function soletras(evt) {
	evt = (evt) ? evt : window.event;
	let charCode = (evt.wich) ? evt.which: evt.keyCode;
	return (charCode === 32) || (charCode >= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 122) || (charCode >= 192 && charCode <= 255)
}


function mudar_imagem() {
	if ((document.getElementById("foto").value === '')) {
		if (document.getElementById('sexo').value === "Masculino") {
			document.getElementById('foto_place').src="imagens/Male_user.png"
		} else {
			document.getElementById('foto_place').src="imagens/Female_user.png"
		}
	}
}

let isactive = false
function emailcheck() {
	if (!(/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/.test(form_atleta.email.value))) {
		if (isactive) {
			toastr.clear()
			isactive = false
		}
		toastr.error('Endereço de email invalido')
	} else {
		if (!isactive) {
			toastr.clear()
			isactive = true
		}
		toastr.success('Endereço de email valido')
	}
}

function passwordcheck(evt){
	//verifica se tem 9 digitos
	if (document.getElementById("password").value.length === 50) {
		toastr.error('A Palavra-passe só pode conter 50 caracteres')
		return false
	}
}

function num_atletacheck(evt) {
	//verifica se tem 11 digitos
	if (document.getElementById("num_atleta").value.length === 11) {
		return false
	}
	
	let confirmar=sonumeros(evt)
	
	if (!confirmar) {
		toastr.error('O numero do atleta só pode conter numeros')
		return false
	}
		return true
}

function nomecheck(evt) {
	//verifica se tem 9 digitos
	if (document.getElementById("nome").value.length === 40) {
		toastr.error('O nome só pode ter 40 caracteres')
		return false
	}
	
	let confirmar = soletras(evt)
	
	if (!confirmar) {
		toastr.error('O nome só pode conter letras')
		return false
	}
		return true
}

function moradacheck(evt){
	//verifica se tem 9 digitos
	if (document.getElementById("morada").value.length === 60) {
		toastr.error('A morada só pode ter 60 caracteres')
		return false
	}

	let confirmar = letras_numeros(evt)
	
	if (!confirmar) {
		toastr.error('A morada só pode conter letras numeros e caracteres como º')
		return false
	} else {
		return true
	}
}

function codigo_postalcheck(evt){
	//verifica se tem 9 digitos
	if (document.getElementById("codigo_postal").value.length === 7) {
		toastr.error('O codigo postal só pode ter 7 caracteres');
		return false
	}

	let confirmar = sonumeros(evt)
	if (!confirmar) {
		toastr.error('O código postal só pode conter numeros')
		return false
	} else {
		return true
	}
}

function telemovelcheck(evt) {
	//verifica se tem 9 digitos
	if (document.getElementById("telemovel").value.length === 9) {
		toastr.error('O número de telemóvel só pode ter 9 caracteres')
		return false
	}
	//verifica se é numero ou não
	let confirmar = sonumeros(evt)
	if (!confirmar) {
		toastr.error('O número de telemóvel só pode conter numeros')
		return false
	}else{
		return true
	}
}

function condicoes_saudecheck(evt) {
	let confirmar = letras_numeros(evt)
	
	if (!confirmar) {
		toastr.error('As condições de saude só pode conter letras e números')
		return false
	}
		return true
}

function confirma() {
    return confirm('Tem a certeza que deseja fazer isto?')
}
/*
function is_admin(){
	if ($_SESSION['permissao'] <> 1) {
		header("Location: home.php")
	}
}
*/
function validadata(){
	   let data = document.getElementById("nascimento").value; // pega o valor do input
	   data = data.replace(/\//g, "-"); // substitui eventuais barras (ex. IE) "/" por hífen "-"
	   let data_array = data.split("-"); // quebra a data em array
	   
	   // para o IE onde será inserido no formato dd/MM/yyyy
	   if(data_array[0].length !== 4){
	      data = data_array[2]+"-"+data_array[1]+"-"+data_array[0]; // remonto a data no formato yyyy/MM/dd
	   }
	   
	   // comparo as datas e calculo a idade
	   let hoje = new Date();
	   let nasc  = new Date(data);
	   let idade = hoje.getFullYear() - nasc.getFullYear();
	   let m = hoje.getMonth() - nasc.getMonth();
	   if (m < 0 || (m === 0 && hoje.getDate() < nasc.getDate())) idade--;
	   
	   if(idade < 5){
	      alert("Pessoas menores de 5 não podem ser inseridos.");
	      return false;
	   }

	   if(idade >= 5 && idade <= 60){
	      alert("Maior de 5 anos, podem ser inseridos.");
	      return true;
	   }
	   
	   // se for maior que 60 não vai acontecer nada!
	   return false;
	}

	$(document).ready(function () { 
        let $campo = $("#cp")
        $campo.mask('00000-000', {reverse: true})
    })

    $("#tele").inputmask({
            mask: "999-999-999",
    });
	$(document).ready(function(){
	    $('body').on('focus', '.phone', function(){
	        var maskBehavior = function (val) {
	            return val.replace(/\D/g, '').length === 11 ? '000-000-000';
	        },
	        options = {
	            onKeyPress: function(val, e, field, options) {
	                field.mask(maskBehavior.apply({}, arguments), options);

	                if(field[0].value.length >= 14){
	                    var val = field[0].value.replace(/\D/g, '');
	                    if(/\d\d(\d)\1{7,8}/.test(val)){
	                        field[0].value = '';
	                        alert('Telefone Invalido');
	                    }
	                }
	            }
	        };
	        $(this).mask(maskBehavior, options);
	    });
	});