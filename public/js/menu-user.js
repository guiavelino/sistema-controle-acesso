function sidebarUser(){
    let sidebar = document.getElementById('menu-user');
    
    if(sidebar.style.marginLeft == '-100%'){
        sidebar.style.marginLeft = '0';
    }else{
        sidebar.style.marginLeft = '-100%'
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