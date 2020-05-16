function sidebarUser(){
    let sidebar = document.getElementById('menu-user');
    
    if(sidebar.style.marginLeft == '-100%'){
        sidebar.style.marginLeft = '0';
    }else{
        sidebar.style.marginLeft = '-100%';
    }
}

function sidebarAdm(){
    let sidebar = document.getElementById('menu-adm');
    let header = document.getElementById('header');
    let content = document.getElementById('content');

    if(sidebar.style.marginLeft == '0px'){
        sidebar.style.marginLeft = '-20%';
        header.style.width = '100%';
        content.style.width = '100%';
        content.style.marginLeft = '0%';
    }else{
        sidebar.style.marginLeft = '0px';
        header.style.width = '80%';
        content.style.width = '80%';
        content.style.marginLeft = '20%';
    }
}

function sidebarSecretAdm(){
    let sidebar = document.getElementById('menu-secret-adm');

    if(sidebar.style.marginLeft == '-18rem'){
        sidebar.style.marginLeft = '0px';
    }else{
        sidebar.style.marginLeft = '-18rem';
    }
}

function sidebarMyProfile(){
    let sidebar = document.getElementById('responsive-menu-profile');
    
    if(sidebar.style.marginLeft == '-20rem'){
        sidebar.style.marginLeft = '0';
    }else{
        sidebar.style.marginLeft = '-20rem';
    }
}