function ajaxRequest(url, method, datas, callbackFunction) {
	httpRequest = new XMLHttpRequest();

	if (!httpRequest) {
		sendNotification("error", "Error during process, please try again in a few minutes");
		return false;
	}

	let encodedDatas = "";
	for (let key in datas) {
		encodedDatas += key + "=" + encodeURIComponent(datas[key]) + "&";
	}

	httpRequest.onreadystatechange = callbackFunction;
	httpRequest.open(method, url);
	httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    httpRequest.send(encodedDatas);
}

function requestCallback(successFunction, errorFunction) {
	if (httpRequest.readyState === XMLHttpRequest.DONE) {
		if (httpRequest.status === 200)
		{
			let message = httpRequest.responseText;
			if (typeof message == "string" && message.indexOf("Error: ") !== -1)
			{
				sendNotification("error", message.split("Error: ")[1]);
				if (errorFunction !== null)
					errorFunction(message);
			}
			else if (successFunction !== null)
				successFunction(message);
		}
		else
		{
			sendNotification("error", "Error during process, please try again in a few minutes");
			if (errorFunction !== null)
				errorFunction(message);
		}
	}
}
