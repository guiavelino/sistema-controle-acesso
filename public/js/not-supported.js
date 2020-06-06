// Teste de navegador para remoção de efeitos não suportados
function identifyBrowser(){
    var nav = navigator.userAgent.toLowerCase();
    if(nav.indexOf("edge") != -1){
        for (let i = 0; i < 30; i++) {
            document.getElementsByTagName('label')[i].style = "color: transparent";
        }
    }
}