function openCloseUserMenu(menu){
    var menuDeroulant = menu.children[1];
    if(menuDeroulant.classList.contains('menuOpen')){
        menuDeroulant.classList.add('menuClose');
        menuDeroulant.classList.remove('menuOpen');
    }else{
        closeAllOtherMenu()
        menuDeroulant.classList.add('menuOpen');
        menuDeroulant.classList.remove('menuClose');
    }
}

function closeAllOtherMenu(){
    const allMenu = document.getElementsByClassName("menuOpen");
    for(const m of allMenu){
        m.classList.remove('menuClose');
    }
}

