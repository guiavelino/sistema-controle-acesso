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
// chama a função de validação e altera as cores
document.getElementById('cpf').addEventListener('change',

function valida(){
    var inputCpf = document.getElementById('cpf').value;
    var btn = document.querySelector('.btn-register');
    btn.disabled = true;
    
    teste = cpf(inputCpf);
    if(teste){
        document.getElementById('invalid').style ="display: none";
        document.getElementById('cpf').style = "border-bottom: 2px solid #20B2AA!important;";
        btn.disabled = false;
    }
    else{
        document.getElementById('cpf').style = "border-bottom: 2px solid red!important;";
        document.getElementById('invalid').style ="display: block";
        btn.disabled = true;

    }
    
});

document.getElementById('cad-cpf').addEventListener('change',

function valida(){
    var inputCpf = document.getElementById('cad-cpf').value;
    var btn = document.querySelector('#registrar');
    btn.disabled = true;
    
    teste = cpf(inputCpf);
    if(teste){
        document.getElementById('cad-invalid').style ="display: none";
        document.getElementById('cad-cpf').style = "border-bottom: 2px solid #20B2AA!important;";
        btn.disabled = false;
    }
    else{
        document.getElementById('cad-cpf').style = "border-bottom: 2px solid red!important;";
        document.getElementById('cad-invalid').style ="display: block";
        btn.disabled = true;
    }
});

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
// teste de navegador para remoção de efeitos não suportados
function identific_nav(){
    var nav = navigator.userAgent.toLowerCase();
    if(nav.indexOf("edge") != -1){
        document.getElementsByTagName('label')[0].style = "display:none";
        document.getElementsByTagName('label')[1].style = "display:none";
        document.getElementsByTagName('label')[2].style = "display:none";
        document.getElementsByTagName('label')[3].style = "display:none";
        document.getElementsByTagName('label')[4].style = "display:none";
        document.getElementsByTagName('label')[5].style = "display:none";
        document.getElementsByTagName('label')[6].style = "display:none";
    }
}

// verdadeiros
// alert(cpf(cpf = '825.566.405-02'), cpf);
// console.log('825.566.405-02', cpf('825.566.405-02'));
// console.log('875.189.681-85', cpf('875.189.681-85'));
// console.log('640.422.216-70', cpf('640.422.216-70'));