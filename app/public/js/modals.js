function openModal(modalName) {
	let modal = document.getElementById(modalName + "-modal");
	let closeButtons = document.getElementsByClassName("close-" + modalName + "-modal");
	modal.style.display = "block";
	modal.classList.remove("fade");
	document.getElementsByTagName("body")[0].classList.add("modal-open");

	for (let i = 0 ; i < closeButtons.length ; i++) {
		closeButtons[i].addEventListener("click", function(){ closeModal(modalName) });
	}

	modal.addEventListener("click", function(e){
		element = e.target
		if (element.classList.contains("modal"))
			closeModal(modalName);
	});
}

function closeModal(modalName) {
	let modal = document.getElementById(modalName + "-modal");
	modal.classList.add("fade");
	document.getElementsByTagName("body")[0].classList.remove("modal-open");
	setTimeout(function(){ modal.style.display = "none"; }, 150 );
}

function addModalContent(modalName, modalContent) {
	let modal = document.getElementById(modalName + "-modal");
	let modalBody = document.querySelectorAll("#" + modalName + "-modal .modal-body")[0];
	modalBody.innerHTML = modalContent;
}