

function extend ( extension, obj ) {
	for (var key in extension ) {
		obj[key] = extension[key];
	}
}

var abstractDefaults = {
	id: 'editcontainer',
	draggable: true,
	contentEditable: true
}

function handleDrag ( e ) {
	e.stopPropagation();
	// e.preventDefault();
	if ( subject.protoIsSet() ) {
		e.preventDefault();
	}
	e.dataTransfer.effectAllowed = "copy";
	e.dataTransfer.setData('text/uri-list', this.src);
	subject.notify(this, e );
}


function ObserverList ( ) {
	this.observerlist = [];
}
ObserverList.prototype.get = function( index ) {
	return this.observerlist[ index ];
};
ObserverList.prototype.count = function( ) {
	return this.observerlist.length;
};
ObserverList.prototype.add = function( obs ) {
	this.observerlist.push(obs);
};

ObserverList.prototype.indexOf = function( obs , startIndex ) {
	var i = startIndex;
	while( i < this.observerlist.length ) {
		if (this.observerlist[i] === obs ) {
			return i;
		}
		i++;
	}
	return -1;
};

ObserverList.prototype.removeAtIndex = function( index ) {
	this.observerlist.splice( index, 1 );
};



// *** ABSTARCT IMAGE CLASS
function AbstractImage () {
	this.name = 'Abstract';
	var div = document.createElement('div');
	extend( abstractDefaults , div );
	var img = document.createElement('img');
	return {
		setImgSrc: function ( uri ) {
			if (!img.src) {
				img.src = uri;
				img.className = "editing";
				// return img;
			}
		},
		getProto: function ( ) {
			if ( div.childElementCount === 0 ) {
				div.appendChild(img);
			}
			return div;
		},
		boxModel: function ( ) {
			return {
				height: img.height,
				width: img.width
			};
		},
		updateDimensions: function  ( dp ) {
			for( var key in dp ) {

				switch( key ) {

					case 'height':
						img.style.height = dp.height;
						break;

					case 'width':
						img.style.width = dp.width;
						break;
					default:
						console.log('could not determine dimension object' );
				}
			}

		}
	};

}



// ***  USER CONTROLS
var UserControls = (function ( ) {
	
	// properties of contorls
	function Controls ( ) {

		// width input label
		var _width = document.getElementById('labelWidth');

		// height input label
		var _height = document.getElementById('labelHeight');

		// grid button (toggle)
		var toggleGrid = document.getElementById('showgrid');
		var overlayStatus = false;

		// grid image 
		var imgrule = ( function  ( ) {
			var uri = "images/editor/GridRule.png";
			var gridImage = document.createElement('img');
			gridImage.src = uri;
			gridImage.id  = "grid";
			gridImage.style.visibility = "hidden";
			return gridImage;
			// subject.notify( gridImage );
		}());


		_width.onchange = function ( e ) {
			subject.notify(this, e);
		};
		_height.onchange = function ( e ) {
			subject.notify(this, e );
		};

		toggleGrid.onclick = function ( e ) {
			if ( !overlayStatus ) {
				overlayStatus = true;
				return imgrule.style.visibility = "visible";
				// return subject.notify( imgrule, e );
			} 
			overlayStatus = false;
			imgrule.style.visibility = "hidden";
		}


		// private methods (i think)
		return {

			// toggleGridButton: document.getElementById('showgrid'),

			overlayStatus: function ( ) {
				return overlayStatus;
			},

			outputDataPoints: function  ( dp ) {
				
				_width.value = dp.width;
				_height.value = dp.height;
				console.log(dp);
			},
			updateOverlay: function ( e ) {
				subject.notify( imgrule , e );
				overlayStatus = true;
			},
			getOverlay: function ( ) {
				return imgrule;
			}
		}


	}

	// instance holder
	var instance;

	// emulate static variables
	var _static = {

		getInstance: function ( ) {
			if ( instance === undefined ) {
				instance = new Controls();
			}

			return instance;
		}
	};

	return _static;

})();


// **** MODULE

var Module = (function () {
	
	// instance 
	// var subject;
	// var _sub = document.getElementById('subject');

	function init ( sub ) {
		
		// private variables
		var concretesubject = document.getElementById('subject');

		var observers = new ObserverList();

		var protoimage;

		var usercontrols = UserControls.getInstance();
		

		concretesubject.ondragover = function ( e ) {
			e.preventDefault();
			e.dataTransfer.dropEffect = 'copy';
			if(protoimage === undefined) {
				protoimage = new AbstractImage();
			}
			// e.dataTransfer.effectAllowed = "copy";
			var data = e.dataTransfer.getData('text/uri-list');
			protoimage.setImgSrc(data);
			// console.log(this, data, e);
		};

		concretesubject.ondrop = function ( e ) {
			e.preventDefault();
			// console.log(e, "this from ondrop:", this);
			// var proto = protoimage.getProto();
			// console.log("proto image: ", proto);
			if (protoimage) {
				// concretesubject.appendChild(proto);
				concretesubject.appendChild( protoimage.getProto() );
				var dim = protoimage.boxModel();
				usercontrols.outputDataPoints(dim);
				var overlay = usercontrols.getOverlay();
				concretesubject.appendChild( overlay );
			}

		};

		concretesubject.onmouseup = function ( e ) {
			console.log("onmouse up listned");
			var dim = protoimage.boxModel();
			subject.notify( dim, e );
		};




		return {

			// public methods & variables
			developerApi: function  ( ) {
				return [observers, protoimage, concretesubject, usercontrols];
			},

			addObserver: function  ( obs ) {
				observers.add(obs);
			},

			notify: function ( obs, e ) {
				if (e.type === "change" ) {
					var dim = obs.id;
					switch(dim) {

						case 'labelHeight':
							protoimage.updateDimensions({height: obs.value});
							break;
						case 'labelWidth':
							protoimage.updateDimensions({width: obs.value});
							break;
						default:
							console.log("switch not registers");
					}

				}
				if (e.type === "mouseup") {
					usercontrols.outputDataPoints( obs );
				}
				if (e.type === "click") {
					concretesubject.appendChild( obs );
				};

				observers.removeAtIndex( observers.indexOf( obs , 0 ) )
				for (var i = 0; i < observers.count(); i++) {
					// observers.get(i).removeEventListener('dragstart', handleDrag, false);
					observers.get(i).update({
						draggable: false,
						style: ({opacity: 0.5}),
						contentEditable: false
					});
				}
			},
			protoIsSet: function ( ) {
				console.log(this);
				if ( protoimage !== undefined ) {
					return true;
				};
			}

		}
	};

	return (subject === undefined ? subject = init() : subject );

})


// GLOBALS

var subject = Module();
var images = document.querySelectorAll('li img');


function Observer ( obj ) {
	this.update = function() {};
}


function addObservers ( obs ) {


	subject.addObserver( obs );


	obs.addEventListener('dragstart', handleDrag, false);

	obs.update = function ( context ) {
		for( var key in context ) {
			if( typeof context[key] === 'object') {
				for (var newstyle in context[key]) {
					obs.style[newstyle] = context[key][newstyle];
				}
			} else {
				obs[key] = context[key];
			}
		}
		
	};


}



for (var i = 0; i < images.length; i++) {
	
	extend ( new Observer() ,images[i] );

	addObservers( images[i] );
};

