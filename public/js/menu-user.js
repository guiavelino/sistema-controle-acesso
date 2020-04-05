function sidebarUser(){

    let sidebar = document.getElementById('menu-user');
    
    if(sidebar.style.marginLeft == '-100%'){
        sidebar.style.marginLeft = '0';
    }else{
        sidebar.style.marginLeft = '-100%'
    }

}