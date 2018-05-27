document.addEventListener("DOMContentLoaded", headerInit);
let httpRequest;

function headerInit() {
	if (document.getElementById("header-my-pictures"))
		document.getElementById("header-my-pictures").addEventListener("click", goToMyPictures);

	if (document.getElementById("header-home"))
		document.getElementById("header-home").addEventListener("click", goToHome);

	if (document.getElementById("header-take-picture"))
		document.getElementById("header-take-picture").addEventListener("click", goToTakePicture);

	if (document.getElementById("header-signup"))
	{
		document.getElementById("header-signup").addEventListener("click", function(){ openModal('signup'); });
		document.getElementById("signup-send").addEventListener("click", signup);
	}

	if (document.getElementById("header-login"))
	{
		document.getElementById("header-login").addEventListener("click", function(){ openModal("login"); });
		document.getElementById("login-send").addEventListener("click", login);
	}

	if (document.getElementById("header-logout"))
	{
		document.getElementById("header-logout").addEventListener("click", logout);
		document.getElementById("go-to-profile-page").addEventListener("click", goToProfile);
	}
}

function signup(e) {
	e.preventDefault();

	let datas = {
		"firstname"	: document.getElementById("signup-firstname").value,
		"lastname"	: document.getElementById("signup-lastname").value,
		"username"	: document.getElementById("signup-username").value,
		"mail"		: document.getElementById("signup-mail").value,
		"password"	: document.getElementById("signup-password").value,
	};
	ajaxRequest(config.CONTROLLERS_D + "/signup-controller.php", "POST", datas, function(){
		requestCallback(successSignupCallback, errorSignupCallback);
	});

	function successSignupCallback(message) {
		sendNotification("success", message);
		closeModal('signup');
		document.getElementById("signup-firstname").value = "";
		document.getElementById("signup-lastname").value = "";
		document.getElementById("signup-username").value = "";
		document.getElementById("signup-mail").value = "";
		document.getElementById("signup-password").value = "";
	}
	function errorSignupCallback(message) {
		document.getElementById("signup-mail").value = "";
		document.getElementById("signup-password").value = "";
	}
}

function login(e) {
	e.preventDefault();

	let datas = {
		"usernameOrMail": document.getElementById("login-username-mail").value,
		"password"		: document.getElementById("login-password").value,
	};
	ajaxRequest(config.CONTROLLERS_D + "/login-controller.php", "POST", datas, function(){
		requestCallback(successLoginCallback, errorLoginCallback);
	});
	
	function successLoginCallback(message) {
		goToHome();
	}

	function errorLoginCallback(message) {
		document.getElementById("login-password").value = "";
	}
}

function logout(e) {
	e.preventDefault();

	ajaxRequest(config.CONTROLLERS_D + "/logout-controller.php", "POST", null, function(){
		requestCallback(successLogoutCallback, null);
	});

	function successLogoutCallback(message) {
		goToHome();
	}
}

function refreshHeader() {
	let datas = {
		"toRefresh": "header",
	};

	ajaxRequest(config.CONTROLLERS_D + "/refresher.php", "POST", datas, function(){
		requestCallback(successRefreshHeaderCallback, null);
	});

	function successRefreshHeaderCallback(message) {
		message = message.split('\n');
		message.pop();
		message.pop();
		message.shift();
		message = message.join('\n');
		document.getElementById("header").innerHTML = message;
		headerInit();
	}
}

function goToProfile() {
	document.location.href = "./profile.php";
}

function goToMyPictures() {
	document.location.href = "./my-pictures.php";
}
function goToHome() {
	document.location.href = "./index.php";
}
function goToTakePicture() {
	document.location.href = "./take-picture.php";
}