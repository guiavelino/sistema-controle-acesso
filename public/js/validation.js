function cpf(cpf){

    cpf = cpf.replace(/\D/g, '');
    if(cpf.toString().length != 11 || /^(\d)\1{10}$/.test(cpf)) return false;
    var result = true;
    [9,10].forEach(function(j){
        var soma = 0, r;
        cpf.split(/(?=)/).splice(0,j).forEach(function(e, i){
            soma += parseInt(e) * ((j+2)-(i+1));
        });
        r = soma % 11;
        r = (r <2)?0:11-r;
        if(r != cpf.substring(j, j+1)) result = false;
    });
    return result;
}

// Chamadas de validaçao, com teclas e alteração de input
document.getElementById('cpf').addEventListener('keyup', testeRapido, false);
document.getElementById('cad-cpf').addEventListener('keyup',testeRapido, false );
document.getElementById('cpf').addEventListener('change', testeRapido, false);
document.getElementById('cad-cpf').addEventListener('change',testeRapido, false );

function valida(){
    var inputCpf = document.getElementById('cpf').value;
    var btn = document.querySelector('.btn-register');
    btn.disabled = true;
    
    teste = cpf(inputCpf);
    if(teste){
        document.getElementById('invalid').style ="display: none";
        document.getElementById('cpf').style = "border-bottom: 2px solid #20B2AA!important;";
        btn.disabled = false;
        return true
    }
    else{
        document.getElementById('cpf').style = "border-bottom: 2px solid red!important;";
        document.getElementById('cpf').focus();
        document.getElementById('invalid').style ="display: block";
        btn.disabled = true;
        return false
    }
    
}
function testeRapido(){
    var delay = 1000;

    setTimeout(valida() , delay);
    setTimeout(validaCadastro() , delay);
}
function validaCadastro(){
    var inputCpf = document.getElementById('cad-cpf').value;
    var btn = document.querySelector('#registrar');
    btn.disabled = true;
    
    teste = cpf(inputCpf);
    if(teste){
        document.getElementById('cad-invalid').style ="display: none";
        document.getElementById('cad-cpf').style = "border-bottom: 2px solid #20B2AA!important;";
        btn.disabled = false;
        return true;
    }
    else{
        document.getElementById('cad-cpf').style = "border-bottom: 2px solid red!important;";
        document.getElementById('cad-cpf').focus();
        document.getElementById('cad-invalid').style ="display: block";
        btn.disabled = true;
        return false;
    }
}

document.getElementById('select-documento-cadastro').addEventListener('change',
    function apagaInput() {
        var btn = document.querySelector('#registrar');
        document.getElementById('cad-cpf').value = "";
        btn.disabled = false;
    }
);
document.getElementById('select-documento-registro').addEventListener('change',
    function apagaInput() {
        var btn = document.querySelector('.btn-register');
        btn.disabled = false;
        document.getElementById('cpf').value = "";
    }
);
document.getElementById('select-uf-registro').addEventListener('change',
    function apagaInput() {
        document.getElementById('rg').value = "";
    }
);
document.getElementById('select-uf-cadastro').addEventListener('change',
    function apagaInput() {
        document.getElementById('cad-rg').value = "";
    }
);

