function toggleMenu(){
    let menu = document.getElementById('all-users-container');
    if(menu.style.left === '-100vw' || menu.style.left === ''){
        menu.style.left = '0';
    }else{
        menu.style.left = '-100vw'
    }
}