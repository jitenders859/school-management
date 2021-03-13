	
	var main = document.getElementById('main');
	var sideNav = document.getElementById('side-nav');
	var sideNavOpen = false;

	function openSideNav() {
		sideNavOpen = true;
		main.classList.add('rotate');
		sideNav.style.transition = 'opacity 1.5s';
		sideNav.style.opacity = 1;
		sideNav.style.zIndex = 2;
	}

	function attemptCloseSideNav() {

		if (sideNavOpen == true) {
			sideNavOpen = false;
			main.classList.remove('rotate');
			sideNav.style.transition = 'opacity 0.5s';
			sideNav.style.opacity = 0;	
			sideNav.style.zIndex = -1;		
		}

	}

	main.addEventListener('click', (ev) => {
		if ((sideNavOpen == true) && (ev.target.id !== 'open-btn')) {
			attemptCloseSideNav();
		}
	});

	main.addEventListener('wheel', (ev) => {
		if (sideNavOpen == true) {
			attemptCloseSideNav();
		}
	});

	window.addEventListener('scroll', (ev) => {
		if (sideNavOpen == true) {
			attemptCloseSideNav();
		}
	});