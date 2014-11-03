
var images = document.getElementsByTagName('img');

function contractImage(image) {
	image.style.width = "";
}


function expandImage (image) {
	image.onmouseover = (function(){
		image.style.width = 173 + "px";
	});




	image.onmouseout = (function(){
		image.style.width = "";
	});
}


function addEvents () {
	images = document.getElementsByTagName('img');
	for (var i = 0; i < images.length; i++) {
		expandImage(images[i]);
	}
}





// setTimeout(addEvents,1000);



