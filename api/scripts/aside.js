let menuIsCollapsed = false;
let asideMenuIsEnabled = true;

function addClickListenerToAsideLogo(){
    let btnLogo = document.getElementById('aside-logo');
    btnLogo.addEventListener('click', (event) => {
        if (!menuIsCollapsed){
            collapseMenu();
        }
        else{
            expandMenu();
        }  
    });
}

function collapseMenu(){

    if (!asideMenuIsEnabled){
        return;
    }

    document.documentElement.style.setProperty('--aside-width', '60px');

    let listTextItems = document.getElementsByClassName('aside-content-list-item-text');
    Array.from(listTextItems).forEach((textItem) => {
        textItem.style.display = 'none'
    });
    menuIsCollapsed = true;
}

function disableAside(){
    asideMenuIsEnabled = false;
}

function enableAside(){
    asideMenuIsEnabled = true;
}

function expandMenu(){

    if (!asideMenuIsEnabled){
        return;
    }

    document.documentElement.style.setProperty('--aside-width', '200px');
    showText();
    menuIsCollapsed = false;
}

const delay = ms => new Promise(res => setTimeout(res, ms));

const showText = async () => {
    await delay(500);


    let listTextItems = document.getElementsByClassName('aside-content-list-item-text');
    Array.from(listTextItems).forEach((textItem) => {
        textItem.style.display = 'block'
    });
}