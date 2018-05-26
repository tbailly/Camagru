document.addEventListener("DOMContentLoaded", feedInit);

function feedInit() {
	getPictures(0, 6);
}

function getPictures(skip, limit) {
	let datas = {
		"callType": "getPictures",
		"skip": skip,
		"limit": limit
	};
	ajaxRequest(config.MODELS_D + "/feed-model.php", "POST", datas, function(){
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

			let cardBody = document.createElement("div");
			cardBody.classList.add("card-body");
			cardBody.addEventListener("click", function(){
				getCommentsAndLikes(images[i], contentImg.src);
				openModal('picture-details');
			});
			
			card.appendChild(contentImg);
			card.appendChild(cardBody);

			cardBody.innerHTML = images[i].description;

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

function getCommentsAndLikes(imgDatas, imgSrc) {
	let datas = {
		"callType": "getCommentsAndLikes",
		"idImage": imgDatas.id_image
	};
	ajaxRequest(config.MODELS_D + "/feed-model.php", "POST", datas, function(){
		requestCallback(successGetCommentsAndLikesCallback, null);
	});

	function successGetCommentsAndLikesCallback(message) {
		let result = JSON.parse(message);
		let modalBody = document.querySelector("#picture-details-modal .modal-body");
		modalBody.innerHTML = "";
		displayPictureDetails(imgDatas, imgSrc, modalBody);
		displayComments(result.comments, modalBody);
		if (result.userConnected)
		{
			displayCommentsAndLikesInputs(result.likes, modalBody);
			addModalEvents(imgDatas, imgSrc, result.comments, result.likes);
		}
	}

	function displayPictureDetails(imgDatas, imgSrc, modalBody) {
		let pictureDetailsDiv = document.createElement("div");
		pictureDetailsDiv.classList.add("picture-details");
		pictureDetailsDiv.setAttribute("data-user", imgDatas.id_user);
		pictureDetailsDiv.setAttribute("data-image", imgDatas.path);

		let pictureDetailsImg = document.createElement("img");
		pictureDetailsImg.src = imgSrc;
		pictureDetailsDiv.appendChild(pictureDetailsImg);

		let heartDiv = document.createElement("div");
		heartDiv.id = "heart";
		heartDiv.classList.add("hide", "hidden");
		heartDiv.innerHTML = "♥";
		pictureDetailsDiv.appendChild(heartDiv);

		let pictureDetailsUserImg = document.createElement("img");
		pictureDetailsUserImg.classList.add("rounded-circle");
		if (imgDatas.profile_img === '1')
			pictureDetailsUserImg.src = "../pictures/profiles/" + imgDatas.id_user + ".jpg";
		else
			pictureDetailsUserImg.src = "./img/user-placeholder.png";
		pictureDetailsDiv.appendChild(pictureDetailsUserImg);

		let pictureDetailsUsername = document.createElement("p");
		pictureDetailsUsername.classList.add("username");
		pictureDetailsUsername.innerHTML = imgDatas.firstname + " " + imgDatas.lastname;
		pictureDetailsDiv.appendChild(pictureDetailsUsername);

		let pictureDetailsDesc = document.createElement("p");
		pictureDetailsDesc.classList.add("description");
		pictureDetailsDesc.innerHTML = imgDatas.description;
		pictureDetailsDiv.appendChild(pictureDetailsDesc);

		modalBody.appendChild(pictureDetailsDiv);
		modalBody.appendChild(document.createElement("hr"));
	}

	function displayComments(comments, modalBody) {
		let commentsDiv = document.createElement("div");
		commentsDiv.classList.add("comments");

		for (let i = 0 ; i < comments.length ; i++) {
			let commentDiv = document.createElement("div");
			commentDiv.classList.add("comment");

			let commentUserImg = document.createElement("img");
			commentUserImg.classList.add("rounded-circle");
			if (comments[i].profile_img == 1)
				commentUserImg.src = "../pictures/profiles/" + comments[i].id_user + ".jpg";
			else
				commentUserImg.src = "./img/user-placeholder.png";
			commentDiv.appendChild(commentUserImg);

			let commentUserName = document.createElement("p");
			commentUserName.classList.add("username");
			commentUserName.innerHTML = comments[i].firstname + " " + comments[i].lastname;
			commentDiv.appendChild(commentUserName);

			let commentText = document.createElement("p");
			commentText.innerHTML = comments[i].text;
			commentDiv.appendChild(commentText);

			commentsDiv.appendChild(commentDiv);
		}
		modalBody.appendChild(commentsDiv);
	}

	function displayCommentsAndLikesInputs(likes, modalBody) {
		let commentField = document.createElement("textarea");
		commentField.classList.add("comment-field");
		commentField.setAttribute("type", "text");
		commentField.setAttribute("placeholder", "Comment...");

		let commentSend = document.createElement("button");
		commentSend.classList.add("add-comment", "btn", "btn-primary");
		commentSend.setAttribute("rows", "2");
		commentSend.innerHTML = "Comment";

		let likesNbDiv = document.createElement("button");
		likesNbDiv.classList.add("likes-nb", "btn", "btn-blank");
		if (likes.currentUserLike)
			likesNbDiv.classList.add("liked");
		likesNbDiv.innerHTML = likes.likesNb + "  <span>♥</span>";

		modalBody.appendChild(commentField);
		modalBody.appendChild(commentSend);
		modalBody.appendChild(likesNbDiv);
	}

	function addModalEvents(imgDatas, imgSrc, comments, likes) {
		let image = document.querySelector("#picture-details-modal .modal-body img");
		let heart = document.querySelector("#picture-details-modal .modal-body #heart");
		let commentField = document.querySelector("#picture-details-modal .modal-body .comment-field");
		let commentButton = document.querySelector("#picture-details-modal .modal-body .add-comment");
		let likeButton = document.querySelector("#picture-details-modal .modal-body .likes-nb");

		commentField.addEventListener('keypress', function (e) {
		    let key = e.which || e.keyCode;
		    if (key === 13) {
		    	commentButton.click();
		    }
		});

		commentButton.addEventListener("click", function(){
			let commentText = document.querySelector("#picture-details-modal .comment-field").value;
			let commentTextTrimmed = commentText.trim();
			if (commentTextTrimmed.length > 0)
				addComment(imgDatas, commentText);
		});

		image.addEventListener("dblclick", function(){
			toggleLike(imgDatas);
			if (likeButton.classList.contains("liked") === false)
			{
				heart.classList.remove("hidden");
				heart.classList.remove("hide");
				setTimeout(function(){
					heart.classList.add("hide"); setTimeout(function(){ heart.classList.add("hidden"); }, 200);
				}, 800);
			}
		});
		likeButton.addEventListener("click", function(){
			toggleLike(imgDatas);
		});
	}
}

function addComment(imgDatas, comment) {
	let datas = {
		"callType": "addComment",
		"idImage": imgDatas.id_image,
		"comment": comment
	};
	ajaxRequest(config.MODELS_D + "/feed-model.php", "POST", datas, function(){
		requestCallback(successAddCommentCallback, null);
	});

	function successAddCommentCallback(result) {
		let datas = JSON.parse(result);
		let user = datas.user;
		let commentDiv = document.createElement("div");
		commentDiv.classList.add("comment");

		let userImg = document.createElement("img");
		userImg.classList.add("rounded-circle");

		if (user.profile_img)
			userImg.src = "../pictures/profiles/" + user.id_user + ".jpg";
		else
			userImg.src = "./img/user-placeholder.png";
			
		let userName = document.createElement("p");
		userName.classList.add("username");
		userName.innerHTML = user.firstname + " " + user.lastname;

		let userComment = document.createElement("p");
		userComment.innerHTML = datas.comment;

		commentDiv.appendChild(userImg);
		commentDiv.appendChild(userName);
		commentDiv.appendChild(userComment);
		document.querySelector("#picture-details-modal .comments").appendChild(commentDiv);
		document.querySelector("#picture-details-modal .comment-field").value = "";
	}
}

function toggleLike(imgDatas) {
	let datas = {
		"callType": "toggleLike",
		"idImage": imgDatas.id_image
	};
	ajaxRequest(config.MODELS_D + "/feed-model.php", "POST", datas, function(){
		requestCallback(successToggleLikeCallback, null);
	});

	function successToggleLikeCallback(message) {
		let result = JSON.parse(message);
		let likesNbDiv = document.querySelector("#picture-details-modal .modal-body .likes-nb");
		if (result.likes.currentUserLike)
			likesNbDiv.classList.add("liked");
		else
			likesNbDiv.classList.remove("liked");
		likesNbDiv.innerHTML = result.likes.likesNb + "<span>♥</span>";
	}
}