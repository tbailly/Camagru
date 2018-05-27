document.addEventListener("DOMContentLoaded", profileInit);

function profileInit() {
	document.getElementById("update-user").addEventListener("click", updateUser);
}

function updateUser(e) {
	e.preventDefault();

	let datas = {
		"mail"				: document.getElementById("mail").value,
		"username"			: document.getElementById("username").value,
		"firstname"			: document.getElementById("firstname").value,
		"lastname"			: document.getElementById("lastname").value,
		"mailPreference"	: document.getElementById("mail-preference").checked
	};

	let password = document.getElementById("password").value;
	let passwordConfirm = document.getElementById("password-confirm").value;
	if (password == passwordConfirm)
		datas.password = password;
	else
		datas.password = '';
	ajaxRequest(config.CONTROLLERS_D + "/profile-controller.php", "POST", datas, function(){
		requestCallback(successGetPictureCallback, null);
	});

	function successGetPictureCallback(message) {
		sendNotification("success", message);
	}
}

function goToMyPictures() {
	document.location.href = "./my-pictures.php";
}
