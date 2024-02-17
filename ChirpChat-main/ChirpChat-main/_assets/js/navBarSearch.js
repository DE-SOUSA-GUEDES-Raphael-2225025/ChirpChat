function openNavBarSearch(){
    let bottomBar = document.getElementById('mobileViewBottomBar');
    let searchBar = document.getElementById('searchBar');

    searchBar.style.display = 'flex'
    bottomBar.style.display = 'none'

}

function closeNavBarSearch(){
    let bottomBar = document.getElementById('mobileViewBottomBar');
    let searchBar = document.getElementById('searchBar');

    searchBar.style.display = 'none'
    bottomBar.style.display = 'flex'
}