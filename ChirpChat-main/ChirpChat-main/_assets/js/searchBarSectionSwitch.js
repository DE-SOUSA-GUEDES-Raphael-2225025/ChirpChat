function showPostSection(){
    let postSection = document.getElementById('post-commentaire-list');
    let categorySection = document.getElementById('category-list');

    postSection.style.display = 'flex';
    categorySection.style.display = 'none';
}

function showCategorySection(){
    let postSection = document.getElementById('post-commentaire-list');
    let categorySection = document.getElementById('category-list');

    postSection.style.display = 'none';
    categorySection.style.display = 'flex';
}