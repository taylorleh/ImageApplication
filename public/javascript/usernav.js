


// var groupNav = document.getElementById('view-submit');
// variables to switch into edit mode
var groupNav = document.querySelectorAll('#view-submit input');
var viewForm = document.getElementById('view-submit');



for (var i = groupNav.length - 1; i >= 0; i--) {
	groupNav[i].addEventListener('click', initEvent);
}

function initEvent () {
	viewForm.submit();
}






// TEXT AREA COMMENT BOX



var optionOther = document.getElementsByName('other');




function FormOther () {
	this.otheroption = [];
}

FormOther.prototype.addText = function() {
	var textbox = document.CreateElement('textarea');
};




function addOtherEvent(obj) {

	obj.onchange = function (e) {
		if(this.checked == false) {
			return;
		}
		var textbox = document.createElement('textarea');
		var parentForm = this.parentElement;
		textbox.name = "comment";
		textbox.display = "block";
		parentForm.appendChild(textbox);
	}

}


for (var i = optionOther.length - 1; i >= 0; i--) {
	addOtherEvent(optionOther[i]);
}



( function  (  ) {
	
	var tableCollection = document.querySelectorAll('.dashboard tr');
	var approvedCollection = document.querySelectorAll('.approved tr');
	// var tableHead = document.querySelectorAll('.dashboard tr th');
	


	function redirectToPage ( obj ) {

		if ( !this.title ) {
			return false;
		};
		
		window.location = "http://" + this.title;
	}



	for (var i = tableCollection.length - 1; i >= 0; i--) {
		tableCollection[i].addEventListener('click', redirectToPage, false);
	}

	for (var i = approvedCollection.length - 1; i >= 0; i--) {
		approvedCollection[i].addEventListener('click', redirectToPage, false );
	}


}());


