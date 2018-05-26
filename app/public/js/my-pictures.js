document.addEventListener("DOMContentLoaded", feedInit);

function feedInit() {
	getPictures(0, 6);
}

function getPictures(skip, limit) {
	let datas = {
		"callType": "getPicturesOfUser",
		"skip": skip,
		"limit": limit
	};
	ajaxRequest(config.MODELS_D + "/my-pictures-model.php", "POST", datas, function(){
		requestCallback(successGetPictureCallback, null);
	});

	function successGetPictureCallback(message) {
		let images = JSON.parse(message);
		let feed = document.getElementById("feed");
		for (let i = 0 ; i < images.length ; i++) {

			let cardCol = document.createElement("div");
			cardCol.classList.add("col-lg-4", "col-md-6", "col-sm-12");

			let card = document.createElement("div");
			card.classList.add("card");
			
			let contentImg = document.createElement("img");
			contentImg.classList.add("card-img-top");
			contentImg.src = "../pictures/" + images[i].id_user + "/" + images[i].path + ".jpg";

			let row = document.createElement("div");
			row.classList.add("row", "justify-content-center", "align-items-center");

			let cardBody = document.createElement("div");
			cardBody.classList.add("card-body");

			// Buttons to delete picture
			let col1 = document.createElement("div");
			col1.classList.add("col-12");
			let col2 = document.createElement("div");
			col2.classList.add("col-12");

			let btn1 = document.createElement("button");
			btn1.classList.add("btn", "btn-primary", "delete-picture");
			btn1.innerHTML = "Delete";
			let btn2 = document.createElement("button");
			btn2.classList.add("btn", "btn-primary", "confirm-delete-picture");
			btn2.setAttribute("disabled", "true");
			btn2.innerHTML = "Confirm";
			
			col1.appendChild(btn1);
			col2.appendChild(btn2);

			btn1.addEventListener("click", function(){ btn2.removeAttribute("disabled"); });
			btn2.addEventListener("click", function(){ deletePicture(images[i], contentImg.src, cardCol); });
			
			let col3 = document.createElement("div");
			col3.classList.add("col-12");
			let btn3 = document.createElement("button");
			btn3.classList.add("btn", "btn-primary", "set-as-profile-picture");
			btn3.innerHTML = "Set as profile picture";
			col3.appendChild(btn3);

			btn3.addEventListener("click", function(){ setAsProfilePicture(contentImg); });

			row.appendChild(col1);
			row.appendChild(col2);
			row.appendChild(col3);
			cardBody.appendChild(row);

			card.appendChild(contentImg);
			card.appendChild(cardBody);

			if (i == images.length - 1)
			{
				let handler = onVisible(cardCol, function(){
						getPictures(skip + 6, 6);
						contentImg.removeEventListener('load', handler);
						document.removeEventListener('scroll', handler);
						window.removeEventListener('resize', handler);
				});
				contentImg.addEventListener('load', handler);
				document.addEventListener('scroll', handler);
				window.addEventListener('resize', handler);
			}
			cardCol.appendChild(card);
			feed.appendChild(cardCol);
		}
	}
}

function deletePicture(imgDatas, imgSrc, imgWrapper) {
	let datas = {
		"callType": "deletePicture",
		"idImage": imgDatas.id_image
	};
	ajaxRequest(config.MODELS_D + "/my-pictures-model.php", "POST", datas, function(){
		requestCallback(successDeletePictureCallback, null);
	});

	function successDeletePictureCallback(message) {
		sendNotification("success", message);
		imgWrapper.parentNode.removeChild(imgWrapper);
	}
}

function setAsProfilePicture(imgSelector) {
	let canvas = document.getElementById('canvas');
	let ctx = canvas.getContext('2d');
    canvas.width = 256;
    canvas.height = 256;
	ctx.drawImage(imgSelector, 280, 0, 720, 720, 0, 0, 256, 256);
	
	let mainImage = canvas.toDataURL('image/jpeg');

	let datas = {
		"callType": "setAsProfilePicture",
		"b64datas": mainImage.split("data:image/jpeg;base64,")[1]
	};
	ajaxRequest(config.MODELS_D + "/my-pictures-model.php", "POST", datas, function(){
		requestCallback(successSetAsProfilePictureCallback, null);
	});

	function successSetAsProfilePictureCallback(message) {
		location.reload(true);
	}
}
