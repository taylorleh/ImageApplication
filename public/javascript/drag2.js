var Oimages = document.querySelectorAll('li img');
var gridButton = document.getElementById('showgrid');


function ObserverList () {
	this.observerList = [];
}



ObserverList.prototype.get = function( index ) {
	if( index > -1 && index < this.observerList.length ) {
		return this.observerList[ index ];
	}
};

ObserverList.prototype.add = function( obj ) {
	return this.observerList.push( obj );
};

ObserverList.prototype.removeAt = function( index ) {
	this.observerList.splice( index, 1 );
};

ObserverList.prototype.indexOf = function( obj, startIndex ) {
	var i = startIndex;

	while( i < this.observerList.length ) {
		if ( this.observerList[i] === obj ) {
			return i;
		}
		i++;
	}
	return -1;
};

ObserverList.prototype.count = function( ) {
	return this.observerList.length;
};


// ClASS
//
// Subject extends ObserverList
// handles indidual observers 
// adds, removes and notify
function Subject ( obj ) {
	this.observers = new ObserverList();
}


Subject.prototype.addObserver = function( observer ) {
	this.observers.add( observer );
};


Subject.prototype.removeObserver = function( observer ) {
	this.observers.removeAt( this.observers.indexOf( observer, 0 ) );
};

Subject.prototype.notify = function( context ) {
	var observercount = this.observers.count();
	for (var i = 0; i < observercount; i++) {
		this.observers.get(i).update( context );
	}
};

Subject.prototype.requestingDrag = function( requester ) {
	console.log(requester ,"is asking subject for drag permission");
	this.observers.removeAt( this.observers.indexOf( requester, 0) );
	editBox.style.border = "2px dashed white";
	this.notify({
		className:'mute',
		draggable:false
		});
	return true;

};



// HELPER
function extend ( extension, obj ) {
	for (var key in extension ) {
		obj[key] = extension[key];
	}
}



// this is a the skeleton of Observer Object
// this method will be updated
// this ensures that these new objects hold
// reference to the update funcation after being removed 
function Observer () {
	this.update = function() {

	};

}




// grab reference to our subject
var editBox = document.getElementById('subject');
// instantiate our editbox as the subject
extend( new Subject(), editBox );

editBox.ondragover = function (e) {
	e.preventDefault();
	editBox.style.border = "2px dashed grey";
	e.dataTransfer.dropEffect="copy";
}

editBox.ondrop = function ( e ) {
	e.preventDefault();
	if (this.childElementCount > 0) {
		return;
	};
	console.log('Drop complete, this is box properties', this);

	var editImageConatainer = document.createElement('div');

	// rule
	var rule = document.createElement('img');
	rule.src = "images/editor/GridRule.png";
	rule.style.height = "396px";
	rule.style.width = "346px";
	rule.style.position = "relative";
	rule.id = "rule";
	rule.style.display = "none";
	console.log(rule);

	// box related
	var newimage = imageEdit( e.dataTransfer.getData('text/uri-list') );
	
	


	this.appendChild(newimage);
	// this.appendChild(editImage);
	this.appendChild(rule);

};


// this is where the magic happens
// editBox.ondragover = function () {
// 	editBox.notify( context );
// };


function addNewObserver ( obj ) {
	
	// extend the observers
	extend( new Observer(), obj );

	editBox.addObserver( obj );

	obj.update = function( value ) {
		
		for(var key in value) {
			
			obj[key] = value[key];
		}
	};

	obj.ondragstart = function ( e ) {
		// console.log('drag stated by:', obj, 'these are the arguments passed in:', arguments, 'and here is this:', this);
		e.dataTransfer.effectAllowed = 'copy';
		e.dataTransfer.setData('text/uri-list', this.src);
		console.log(e);
		(editBox.requestingDrag( obj ));

	};

}

var imageEdit = function (data) {
	var editImage = document.createElement('img');
	var container = document.createElement('div');
	editImage.src = data;
	editImage.style.position = "absolute";
	editImage.style.top = 99;
	editImage.style.left = 86.5;
	editImage.dataset = ({
		border: "1px solid blue",
		padding: "10em"});
	

	container.id = "editcontainer";
	container.contentEditable = true;
	container.draggable = true;
	container.appendChild(editImage);
	return container;
}



// helper funcation*
// addObserver is called indivually by
// each image

function addemupboys (images) {
	
	for (var i = Oimages.length - 1; i >= 0; i--) {
		addNewObserver( images[i] );
	}
	
}



function showGrid() {
	if (rule.style.display == "none") {

		rule.style.display = "block";
	} else {
		rule.style.display = "none";
	}
}




gridButton.onclick = showGrid;

addemupboys(Oimages);
















