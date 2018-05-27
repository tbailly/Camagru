document.addEventListener("DOMContentLoaded", confirmAccountInit);

function confirmAccountInit() {
	confirmAccount();
}

function confirmAccount() {
	let token = document.location.href.split('?token=')[1];
	let datas = {
		"token": token
	};
	ajaxRequest(config.CONTROLLERS_D + "/confirm-account-controller.php", "POST", datas, function(){
		requestCallback(successConfirmAccountCallback, errorConfirmAccountCallback);
	});

	function successConfirmAccountCallback(message) {
		appendMessage("success", message);
	}

	function errorConfirmAccountCallback(message) {
		appendMessage("error", message);
	}

	function appendMessage(type, message) {
		let messageWrapper = document.getElementById("confirm-account-div");
		let messageP = document.createElement("p");
		if (type === "success")
			messageP.classList.add("success-message");
		else if (type === "error")
			messageP.classList.add("error-message");
		messageP.innerHTML = message;
		messageWrapper.appendChild(messageP);
	}
}