function sendNotification(status, message) {
	let notifSelector = document.getElementById("notifications");

	let newNotif = document.createElement("div");
	newNotif.classList.add("fade");

	switch (status) {
		case 'success':
			newNotif.style.backgroundColor = "#2ecc71";
			break;
		case 'warning':
			newNotif.style.backgroundColor = "#f1c40f";
			break;
		case 'error':
			newNotif.style.backgroundColor = "#e74c3c";
			break;
	}

	notifSelector.insertBefore(newNotif, notifSelector.firstChild);
	newNotif.classList.remove("fade");
	newNotif.innerHTML = message;
	setTimeout(function(){
		newNotif.classList.add("fade");
		setTimeout(function(){ newNotif.parentNode.removeChild(newNotif); }, 150 );
	}, 2000);
}

function onVisible(el, callback) {
    let old_visible;
    return function () {
        let visible = isElementInViewport(el);
        if (visible != old_visible) {
            old_visible = visible;
            if (typeof callback == 'function' && visible == true) {
                callback();
            }
        }
    }
	
	function isElementInViewport (el) {
	    let rect = el.getBoundingClientRect();

	    return (
	        rect.top >= 0 &&
	        rect.left >= 0 &&
	        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
	        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
	    );
	}
}


function readFile(input) {
	return new Promise(resolve => {
		let reader = new FileReader();

		reader.onload = function (e) {
			let csvDatas = e.target.result;
			resolve(csvDatas);
		};

		reader.readAsDataURL(input.files[0]);
	});
}
