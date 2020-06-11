 // Máscaras de RG de acordo com o estado selecionados nos formulários de cadastro e registro

// Modal de cadastro
$("#select-uf-cadastro").change(function() {
    if(this.value == "RJ" || this.value == "SP"){
        $("#cad-rg").mask("99.999.999-9");
    }
    else if(this.value == "BA"){
        $("#cad-rg").mask("99.999.999-99");
    }
    else {
        $("#cad-rg").unmask();
    }
});

// Modal de registro
$("#select-uf-registro").change(function() {
    if(this.value == "RJ" || this.value == "SP"){
        $("#rg").mask("99.999.999-9");
    }
    else if(this.value == "BA"){
        $("#rg").mask("99.999.999-99"); 
    }
    else {
        $("#rg").unmask();
    }
});

// Tela de edição
$("#select-uf-registro").change(function() {
    if(this.value == "RJ" || this.value == "SP"){
        $("#rg-edit").mask("99.999.999-9");
    }
    else if(this.value == "BA"){
        $("#rg-edit").mask("99.999.999-99");
    }
    else {
        $("#rg-edit").unmask();
    }
});



// Máscara restante

// UF:CE
// Máscara 1980<: 99-999.999.999
// (dois primeiros digitos referentes ao ano de emissão)
// Máscara 1980>: 99.999.9-99
// (dois ultimos digitos referentes ao ano de emissão)

// Mascras não confirmadas

// if(this.value == "AP"){
//     // AP
//     // 999.999
//     $("#cad-rg").mask("999.999");
// }
// else if(this.value == "AC" || this.value == "AM" || this.value == "RO" || this.value == "RR" || this.value == "TO"){
//     // UF: AC - AM - RO - RR - TO
//     // Máscara: 99.999.9
//     $("#cad-rg").mask("99.999.9");
// }
// else if(this.value == "AL" || this.value == "DF" || this.value == "ES" || this.value == "GO" || this.value == "MS" || this.value == "PB" || this.value == "SE" || this.value == "PI" || this.value == "RN"){
//     // UF: AL - DF - ES - GO - MS - PB - SE - PI - RN 
//     // Máscara: 99.999.99
//     $("#cad-rg").mask("99.999.99");
// }
// else if(this.value == "PE"){
//     // UF: PE
//     // Máscara: 99.999.999
//     $("#cad-rg").mask("99.999.999");
// }
// else if(this.value == "MT" || this.value == "PR" || this.value == "SC"){
//     // UF: MT - PR - SC
//     // Máscara: 99.999.99-9
//     $("#cad-rg").mask("99.999.99-9");
// }
// else if(this.value == "MA" || this.value == "RJ" || this.value == "SP"){
//     // UF: MA - RJ - SP
//     // Máscara: 99.999.999-9
//     $("#cad-rg").mask("99.999.999-9");
// }
// else if(this.value == "PA"){
//     // UF: PA
//     // Máscara: 99.999.99-99
//     $("#cad-rg").mask("99.999.99-99");
// }
// else if(this.value == "BA"){
//     // UF: BA
//     // Máscara: 99.999.999-99
//     $("#cad-rg").mask("99.999.999-99");
// }
// else if(this.value == "RS"){
//     // UF: RS
//     // Máscara: 99-99.999.99-9
//     $("#cad-rg").mask("99-99.999.99-9");
// }
// else if(this.value == "MG"){
//     // UF: MG
//     // Máscara: MG-99.999.999 (alfanumérico) 
//     $("#cad-rg").mask("aa-99.999.999");
// }
// else if(this.value == "CE"){
//     $("#cad-rg").mask("**************");
// }