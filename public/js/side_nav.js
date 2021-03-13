var sideNavOpen = false;
var sideNav = document.getElementById('side-nav');
var main = document.getElementById('main');

function openSideNav() {
    sideNav.style.opacity = 1;
    sideNav.style.width = '250px';
    sideNav.style.zIndex = 2;
    sideNavOpen = true;
}

function closeSideNav() {
    sideNav.style.opacity = 0;
    sideNav.style.width = '0';	
    sideNav.style.zIndex = -1;
    sideNavOpen = false;	
}

main.addEventListener('click', (ev) => {

    if ((sideNavOpen == true) && (ev.target.id !== 'open-btn')) {
        closeSideNav();
    }

});