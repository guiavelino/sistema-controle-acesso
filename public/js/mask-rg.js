 // Máscaras de RG de acordo com o estado selecionados nos formulários de cadastro e registro

// Modal de cadastro
$("#select-uf-cadastro").change(function() {
    if(this.value == "AC" || this.value == "AM" || this.value == "RO" || this.value == "RR" || this.value == "TO"){
        // UF: AC - AM - RO - RR - TO
        // Máscara: 99.999.9
        $("#cad-rg").mask("99.999.9");
        
    }
    else if(this.value == "AL" || this.value == "DF" || this.value == "ES" || this.value == "GO" || this.value == "MS" || this.value == "PB" || this.value == "SE" || this.value == "PI" || this.value == "RN"){
        // UF: AL - DF - ES - GO - MS - PB - SE - PI - RN 
        // Máscara: 99.999.99
        $("#cad-rg").mask("99.999.99");
        
    }
    else if(this.value == "PE"){
        // UF: PE
        // Máscara: 99.999.999
        $("#cad-rg").mask("99.999.999");
        
    }
    else if(this.value == "MT" || this.value == "PR" || this.value == "SC"){
        // UF: MT - PR - SC
        // Máscara: 99.999.99-9
        $("#cad-rg").mask("99.999.99-9");
        
    }
    else if(this.value == "MA" || this.value == "RJ" || this.value == "SP"){
        // UF: MA - RJ - SP
        // Máscara: 99.999.999-9
        $("#cad-rg").mask("99.999.999-9");
        
    }
    else if(this.value == "PA"){
        // UF: PA
        // Máscara: 99.999.99-99
        $("#cad-rg").mask("99.999.99-99");
        
    }
    else if(this.value == "BA"){
        // UF: BA
        // Máscara: 99.999.999-99
        $("#cad-rg").mask("99.999.999-99");
        
    }
    else if(this.value == "RS"){
        // UF: RS
        // Máscara: 99-99.999.99-9
        $("#cad-rg").mask("99-99.999.99-9");
        
    }
});



// Máscaras restantes

// Ámapa - AP

// UF:MG
// Máscara: 99.999.999 (alfanumérico) 

// UF:CE
// Máscara 1980<: 99-999.999.999
// (dois primeiros digitos referentes ao ano de emissão)
// Máscara 1980>: 99.999.9-99
// (dois ultimos digitos referentes ao ano de emissão)



// Modal de registro
$("#select-uf-registro").change(function() {
    if(this.value == "AC" || this.value == "AM" || this.value == "RO" || this.value == "RR" || this.value == "TO"){
        // UF: AC - AM - RO - RR - TO
        // Máscara: 99.999.9
        $("#rg").mask("99.999.9");
        
    }
    else if(this.value == "AL" || this.value == "DF" || this.value == "ES" || this.value == "GO" || this.value == "MS" || this.value == "PB" || this.value == "SE" || this.value == "PI" || this.value == "RN"){
        // UF: AL - DF - ES - GO - MS - PB - SE - PI - RN 
        // Máscara: 99.999.99
        $("#rg").mask("99.999.99");
        
    }
    else if(this.value == "PE"){
        // UF: PE
        // Máscara: 99.999.999
        $("#rg").mask("99.999.999");
        
    }
    else if(this.value == "MT" || this.value == "PR" || this.value == "SC"){
        // UF: MT - PR - SC
        // Máscara: 99.999.99-9
        $("#rg").mask("99.999.99-9");
        
    }
    else if(this.value == "MA" || this.value == "RJ" || this.value == "SP"){
        // UF: MA - RJ - SP
        // Máscara: 99.999.999-9
        $("#rg").mask("99.999.999-9");
        
    }
    else if(this.value == "PA"){
        // UF: PA
        // Máscara: 99.999.99-99
        $("#rg").mask("99.999.99-99");
        
    }
    else if(this.value == "BA"){
        // UF: BA
        // Máscara: 99.999.999-99
        $("#rg").mask("99.999.999-99");
        
    }
    else if(this.value == "RS"){
        // UF: RS
        // Máscara: 99-99.999.99-9
        $("#rg").mask("99-99.999.99-9");
        
    }
});
