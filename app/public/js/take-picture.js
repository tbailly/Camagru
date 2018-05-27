document.addEventListener("DOMContentLoaded", takePictureInit);

function takePictureInit() {
	let filtersWrapper = document.getElementById("filters-wrapper");
	navigator.getMedia = (navigator.getUserMedia ||
						  navigator.webkitGetUserMedia ||
						  navigator.mozGetUserMedia ||
						  navigator.msGetUserMedia);
	let constraints = {
		audio: false,
		video: {
			width: { ideal: 1280},
			height: { ideal: 720},
			facingMode: "user",
			frameRate: {
				ideal: 60,
				max: 60
			}
		}
	};

	document.getElementById("startbutton").addEventListener("click", function(){
		takePicture(
			1280, 720
		);
	});
	document.getElementById("helpbutton").addEventListener("click", showHelp);
	document.getElementById("object-filters").addEventListener("change", function(){ changeFilter("object") });
	document.getElementById("frame-filters").addEventListener("change", function(){ changeFilter("frame") });
	document.getElementById("classic-filters").addEventListener("change", function(){ changeFilter("classic") });
	document.getElementById("upload-picture").addEventListener("change", uploadPicture);
	document.addEventListener('keydown', keyPressed);
	addFilters("all");

	navigator.mediaDevices.getUserMedia(constraints).then(
		(stream) => {
			let video = document.getElementById('video');
			if ("srcObject" in video) {
				video.srcObject = stream;
			} else {
				video.src = window.URL.createObjectURL(stream);
			}
			video.onloadedmetadata = function(e) {
				video.play();
			};
		}
	).catch(
		(error) => {
			sendNotification("error", "An error occured during webcam loading, please refresh the page");
		}
	);
}

function takePicture(width, height) {
	let jsonDatas = {
		main: getMainImageDatas(),
		filters: getFiltersDatas(),
		classicFilter: getClassicFilterType()
	};

	let datas = {
		"callType": "pictureMontage",
		"jsonDatas": JSON.stringify(jsonDatas)
	};
	console.log(datas);
	ajaxRequest(config.CONTROLLERS_D + "/take-picture-controller.php", "POST", datas, function(){
		requestCallback(successTakePictureCallback, null);
	});

	function getMainImageDatas() {
		let video = document.getElementById('video');
		let uploadedPicture = document.getElementById('uploaded-picture');

		// Draw picture in invisible canvas (from video or uploaded picture) and get base64 main image
		let canvas = document.getElementById('canvas');
		let ctx = canvas.getContext('2d');
	    canvas.width = width;
	    canvas.height = height;
	    if (video.style.display != "none")
	    	ctx.drawImage(video, 0, 0, width, height);
	    else
	    	ctx.drawImage(uploadedPicture, 0, 0, width, height);
	    let mainImage = canvas.toDataURL('image/jpeg');
	    
	    // Gather mainImageDatas
		let mainImageDatas = {
			b64datas: mainImage.split("data:image/jpeg;base64,")[1],
		};
	    if (video.style.display != "none")
	    {
			mainImageDatas.width  = parseInt(window.getComputedStyle(video).width.split("px")[0]);
			mainImageDatas.height = parseInt(window.getComputedStyle(video).height.split("px")[0]);
	    }
		else
		{
			mainImageDatas.width  = parseInt(window.getComputedStyle(uploadedPicture).width.split("px")[0]);
			mainImageDatas.height = parseInt(window.getComputedStyle(uploadedPicture).height.split("px")[0]);
		}

		return (mainImageDatas);
	}

	function getFiltersDatas() {
		let filters = [];
		let allFiltersElem = document.getElementById("filters-wrapper");

	    // Get datas for each frame or object filter and push it in filters
		for (let i = 0; i < allFiltersElem.children.length; i++) {
			let filterElem = allFiltersElem.children[i];
	    	let filter = {
	    		path	: filterElem.firstChild.getAttribute("data-path"),
	    		left	: parseInt(window.getComputedStyle(filterElem).left.split("px")[0]),
	    		top		: parseInt(window.getComputedStyle(filterElem).top.split("px")[0]),
	    		width	: parseInt(window.getComputedStyle(filterElem).width.split("px")[0]),
	    		height	: parseInt(window.getComputedStyle(filterElem).height.split("px")[0]),
	    		rotation: getRotation(filterElem)
	    	}
	    	filters.push(filter);
		}

		return (filters);
	}

	function getClassicFilterType() {
		let classicFilterSelect = document.getElementById("classic-filters");
    	let classicFilterType = classicFilterSelect.options[classicFilterSelect.selectedIndex].value;
		return (classicFilterType);
	}

	function successTakePictureCallback(message) {
		let b64Picture = message;
		let pictureTakenWrapper = document.getElementById("pictures-taken-wrapper");
		let newPictureSelector = document.createElement("div");
		newPictureSelector.classList.add("picture-taken", "py-2");

		let newDeletePicture = document.createElement("button");
		newDeletePicture.classList.add("delete-picture", "close");
		newDeletePicture.innerHTML = "<img src='" +  config.IMG_D + "/close.svg'>";
		newDeletePicture.addEventListener("click", function(e){
			newPictureSelector.parentNode.removeChild(newPictureSelector);
		});

		let newSharePicture = document.createElement("button");
		newSharePicture.classList.add("share-picture", "close");
		newSharePicture.innerHTML = "<img src='" +  config.IMG_D + "/upload.svg'>";
		newSharePicture.addEventListener("click", function(e){
			openModal("share-picture");
			createModalContent(b64Picture, newPictureSelector);
		});

		let newPicture = document.createElement("img");
		newPicture.classList.add("picture");
		newPicture.classList.add("img-fluid");
		newPicture.setAttribute("alt", "Picture just taken");
		newPicture.src = "data:image/jpg;base64, " + b64Picture;
		newPictureSelector.appendChild(newPicture);
		newPictureSelector.appendChild(newDeletePicture);
		newPictureSelector.appendChild(newSharePicture);

		pictureTakenWrapper.insertBefore(newPictureSelector, pictureTakenWrapper.firstChild);
	}
}


function createModalContent(imageDatas, pictureSelector) {
	content = "\
	<div>\
		<img class='img-fluid' src='data:image/jpg;base64, " + imageDatas + "'>\
	</div>\
	<div>\
	<textarea placeholder='Describe your picture'></textarea>\
	</div>\
	<button type='submit' id='share-picture' class='btn btn-primary'>Share</button>\
	";
	addModalContent("share-picture", content);
	document.getElementById("share-picture").addEventListener("click", function(){
		sharePicture(pictureSelector);
	});
}

function sharePicture(pictureSelector) {
	let imageToSave = document.querySelectorAll("#share-picture-modal .modal-body img")[0].getAttribute("src");
	let descriptionToSave = document.querySelectorAll("#share-picture-modal .modal-body textarea")[0].value;
	
	let descriptionTrimmed = descriptionToSave.trim();
	if (descriptionTrimmed.length > 0 && descriptionTrimmed.length < 256)
	{
		let datas = {
			"callType": "savePicture",
			"pictureDatas": imageToSave.split("data:image/jpg;base64,")[1],
			"description": descriptionToSave
		};
		ajaxRequest(config.CONTROLLERS_D + "/take-picture-controller.php", "POST", datas, function(){
			requestCallback(sharePictureCallback);
		});
	}

	function sharePictureCallback(message) {
		sendNotification("success", message);
		closeModal("share-picture");
		pictureSelector.parentNode.removeChild(pictureSelector);
	}
}

function changeFilter(type) {
	let select = document.getElementById(type + "-filters");
	let filtersWrapper = document.getElementById("filters-wrapper");
	let video = document.getElementById("video");
	let uploadedPicture = document.getElementById("uploaded-picture");

	if (type == "classic")
	{
		video.style.filter = select.options[select.selectedIndex].value;
		uploadedPicture.style.filter = select.options[select.selectedIndex].value;
		for (let i = 0 ; i < filtersWrapper.children.length ; i++) {
			filtersWrapper.children[i].style.filter = select.options[select.selectedIndex].value;
		}
	}
	else if (type == "frame")
	{
		for (let i = 0 ; i < filtersWrapper.children.length ; i++) {
			if (filtersWrapper.children[i].firstChild.getAttribute("data-type") == type)
				filtersWrapper.children[i].remove();
		}
	}
	if (select.options[select.selectedIndex].value != "" && type != "classic")
	{
		let filterDivToAdd = document.createElement("div");
		filterDivToAdd.classList.add("filter-div");
		filterDivToAdd.setAttribute("data-path", select.options[select.selectedIndex].value);
		filterDivToAdd.style.filter = video.style.filter;

		let filterToAdd = document.createElement("img");
		filterToAdd.src = config.FILTERS_D + "/" + select.options[select.selectedIndex].value + ".png";
		filterToAdd.classList.add("img-fluid");
		filterToAdd.setAttribute("data-path", select.options[select.selectedIndex].value);
		filterToAdd.setAttribute("data-type", type);
		filterToAdd.onload = function() {
			filterDivToAdd.style.maxWidth = filterToAdd.naturalWidth + "px";
		}

		filtersWrapper.appendChild(filterDivToAdd);
		filterDivToAdd.appendChild(filterToAdd);

		filterToAdd.ondragstart = function() { return false; };
		transformFilter(filterDivToAdd);

		if (type == "object")
		{
			select.selectedIndex = 0;
			select.blur();
		}
	}
}

function addFilters(type) {
	let datas = {
		"callType": "getFilters",
		"type": type
	};
	ajaxRequest(config.CONTROLLERS_D + "/take-picture-controller.php", "POST", datas, function(){
		requestCallback(successAddFiltersCallback, null);
	});

	function successAddFiltersCallback(message) {
		let filters = JSON.parse(message);
		let objectFilterSelect = document.getElementById("object-filters");
		let frameFilterSelect = document.getElementById("frame-filters");
		let classicFilterSelect = document.getElementById("classic-filters");
		for(let i = 0 ; i < filters.length ; i++)
		{
			let option = document.createElement("option");
			option.value = filters[i].path;
			option.text = filters[i].full_name;
			if (filters[i].type == "object")
				objectFilterSelect.appendChild(option);
			else if (filters[i].type == "frame")
				frameFilterSelect.appendChild(option);
			else
				classicFilterSelect.appendChild(option);
		}
	}
}


function transformFilter(element) {
	element.setAttribute("data-mode", "move");
	dragElement(element);
	element.ondblclick = changeMode;

	function changeMode() {
		if (element.getAttribute("data-mode") == "rotate")
		{
			element.setAttribute("data-mode", "move");
			dragElement(element);
		}
		else if (element.getAttribute("data-mode") == "resize")
		{
			element.setAttribute("data-mode", "rotate");
			rotateElement(element);
		}
		else if (element.getAttribute("data-mode") == "move")
		{
			element.setAttribute("data-mode", "resize");
			resizeElement(element);
		}
	}

	function resizeElement(element) {
		let pos1 = 0, pos3 = 0, size = element.clientWidth;
		element.onmousedown = resizeMouseDown;

		function resizeMouseDown(e) {
			e = e || window.event;
			pos3 = e.clientX;
			document.onmouseup = closeResizeElement;
			document.onmousemove = elementResize;
		}

		function elementResize(e) {
			e = e || window.event;
			pos1 = pos3 - e.clientX;
			pos3 = e.clientX;
			size -= pos1;
			element.style.width = size + "px";
		}

		function closeResizeElement() {
			document.onmouseup = null;
			document.onmousemove = null;
		}
	}

	function rotateElement(element) {
		let pos2 = 0, pos4 = 0;
		let angle = getRotation(element);
		element.onmousedown = rotateMouseDown;

		function rotateMouseDown(e) {
			e = e || window.event;
			pos4 = e.clientY;
			document.onmouseup = closeRotateElement;
			document.onmousemove = elementRotate;
		}

		function elementRotate(e) {
			e = e || window.event;
			pos2 = pos4 - e.clientY;
			pos4 = e.clientY;
			angle -= pos2;
			element.style.transform = "rotate(" + angle + "deg)";
		}

		function closeRotateElement() {
			document.onmouseup = null;
			document.onmousemove = null;
		}
	}

	function dragElement(element) {
		let pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
		element.onmousedown = dragMouseDown;

		function dragMouseDown(e) {
			e = e || window.event;
			pos3 = e.clientX;
			pos4 = e.clientY;
			document.onmouseup = closeDragElement;
			document.onmousemove = elementDrag;
		}

		function elementDrag(e) {
			e = e || window.event;
			pos1 = pos3 - e.clientX;
			pos2 = pos4 - e.clientY;
			pos3 = e.clientX;
			pos4 = e.clientY;
			element.style.top = (element.offsetTop - pos2) + "px";
			element.style.left = (element.offsetLeft - pos1) + "px";
		}

		function closeDragElement() {
			document.onmouseup = null;
			document.onmousemove = null;
		}
	}
}

function keyPressed(e) {
	if (e.key == "d")
	{
		let frameFilterSelect = document.getElementById("frame-filters");
		let element = getInnermostHovered().parentElement;
		if (element.classList.contains("filter-div"))
		{
			if (element.firstChild.getAttribute("data-type") == "frame")
				frameFilterSelect.selectedIndex = 0;
			if (element.classList.contains("filter-div"))
				element.parentNode.removeChild(element);
		}
	}
}

function getInnermostHovered() {
    let n = document.querySelector(":hover");
    let nn;
    while (n) {
        nn = n;
        n = nn.querySelector(":hover");
    }
    return nn;
}


function getRotation(element) {
	let style = window.getComputedStyle(element, null);
	let rotation = style.getPropertyValue("-webkit-transform") ||
	         style.getPropertyValue("-moz-transform") ||
	         style.getPropertyValue("-ms-transform") ||
	         style.getPropertyValue("-o-transform") ||
	         style.getPropertyValue("transform") ||
	         "FAIL";

	let values = rotation.split('(')[1].split(')')[0].split(',');
	let a = values[0];
	let b = values[1];
	let c = values[2];
	let d = values[3];

	let scale = Math.sqrt(a*a + b*b);
	let sin = b/scale;
	let angle = Math.round(Math.atan2(b, a) * (180/Math.PI));
	return (angle);
}


function uploadPicture() {
	let fileInput = document.getElementById("upload-picture");
	readFile(fileInput).then(
		(b64) => {
			let regex = /^data:image\/(jpeg|png);base64,/;
			if (b64.length > 1330000)
			{
				sendNotification("error", "File must be maximum 1MB");
				return;
			}
			if (!regex.test(b64))
			{
				sendNotification("error", "File must be a jpeg or png image");
				return;
			}

			let newImg = new Image();
			newImg.onload = function() {
				let ratio = newImg.width / newImg.height;
				if (ratio > 1.78 || ratio < 1.77)
				{
					sendNotification("error", "Image aspect ratio must be 16:9");
					return;
				}
				if (newImg.width < 1280 || newImg.height < 720)
				{
					sendNotification("error", "Image size should be at least 1280x720");
					return;
				}
				let newUploadedImage = document.getElementById("uploaded-picture");
				newUploadedImage.src = newImg.src;
				document.getElementById("video").style.display = "none";
				document.getElementById("uploaded-picture").style.display = "block";
			}
			newImg.src = b64;


		}
	);
}

function showHelp() {
	openModal("help");
}