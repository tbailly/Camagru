document.addEventListener("DOMContentLoaded", resetPasswordInit);

function resetPasswordInit() {
	document.getElementById("reset-password-send").addEventListener("click", sendMail);
	token = document.location.href.split('?token=')[1];
	if (typeof token != "undefined")
		getToken(token);
	else
		document.getElementById("password-send-row").style.display = "flex";
}

function sendMail(e) {
	e.preventDefault();
	
	let mail = document.getElementById("reset-password-mail").value;
	datas = {
		"callType": "sendMail",
		"mail": mail
	}
	ajaxRequest(config.MODELS_D + "/reset-password-model.php", "POST", datas, function(){
		requestCallback(successResetPasswordCallback, null);
	});

	function successResetPasswordCallback(message) {
		sendNotification("success", message);
	}
}

function getToken(token) {
	datas = {
		"callType": "getToken",
		"token": token
	}
	ajaxRequest(config.MODELS_D + "/reset-password-model.php", "POST", datas, function(){
		requestCallback(successGetTokenCallback, errorGetTokenCallback);
	});

	function successGetTokenCallback(message) {
		sendNotification("success", "Valid token, you can now change your password");
		token = JSON.parse(message);
		document.getElementById("password-confirm-row").style.display = "flex";
		document.getElementById("reset-password-confirm").addEventListener("click", function(e){
			resetPassword(e, token);
		});
	}

	function errorGetTokenCallback() {
		document.getElementById("password-send-row").style.display = "flex";
	}
}

function resetPassword(e, token) {
	e.preventDefault();

	let password = document.getElementById("password").value;
	let passwordConfirm = document.getElementById("password-confirm").value;
	if (password === passwordConfirm)
	{
		datas = {
			"callType": "resetPassword",
			"password": password,
			"idUser": token.id_user,
			"token": token.token
		}
		ajaxRequest(config.MODELS_D + "/reset-password-model.php", "POST", datas, function(){
			requestCallback(successGetTokenCallback, null);
		});
	}

	function successGetTokenCallback(message) {
		sendNotification("success", message);
	}
}