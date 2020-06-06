// Teste de navegador para remoção de efeitos não suportados


function identifyBrowser() {
    var nav = navigator.userAgent.toLowerCase();
    if (nav.indexOf("edge") != -1) { 

        $('.label-float input:last-of-type').addClass('my-3'); 
        $('.label-float label:last-of-type').remove(); // remove a última
        // $(conteudo_mover).insertBefore('.label-float input:first-of-type'); // insere antes da primeira
            
         $(".label-float").removeClass();
    }
}