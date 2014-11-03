


function extend ( extension, obj ) {
	for (var key in extension ) {
		obj[key] = extension[key];
	}
}




function ObserverList ( ) {
	this.observerlist = [];

}

ObserverList.prototype = {

	get: function( index ) {
		return this.observerlist[ index ];
	},

	add: function( obs ) {
		this.observerlist.push(obs);
	},

	indexOf: function( obs , startIndex ) {
		var i = startIndex;
		while( i < this.observerlist.length ) {
			if (this.observerlist[i] === obs ) {
				return i;
			}
			i++;
		}
		return -1;
	},

	removeAtIndex: function( index ) {
		this.observerlist.splice( index, 1 );
	},

	count: function  ( ) {
		return this.observerlist.length;
	}

};

function ProtoImage ( ) {

	this.Oimage = new Image();

	this.wrap;

	this.Oimage.addEventListener('dragstart', function ( event ) {
		

		mediator.notify( event, this, 'proto');
		console.log('proto image dragstart', event);
	},false);
	
	
}



ProtoImage.prototype = {

	constructor: ProtoImage,

	initWithContents : function( sContent ) {
	
		if ( this.Oimage.src === '') {
			this.Oimage.src = sContent;
			this.Oimage.id = 'dropzone';
		}

		return this.Oimage.src;
	},

	returnInstance : function( ) {
		
		if ( this.Oimage.src !== '' ) {
			return this.Oimage;
		}
		return -1;
	},

	getBoxModel : function( ) {
		
		// returns box model of prototype image
		return ({ height: this.Oimage.height,  width: this.Oimage.width });

	},

	addWrapAfterLoad : function  ( cont ) {
		this.wrap = cont;
	},

	updateImageModel : function ( dp ) {
		if ( dp.width ) {

			this.wrap.style.width = dp.width;
		}
		if ( dp.height ) {

			this.wrap.style.height = dp.height;
		}
	}

}


//*** user controls

function UserControls ( ) {
	
	// private properties
	var _width = document.getElementById('labelWidth');

	var _height = document.getElementById('labelHeight');

	var _widthpercent = document.getElementById('widthmod');

	var _heightpercent = document.getElementById('heightmod');


	document.getElementById('showgrid').onclick = function ( event ) {
		mediator.notify( event, this, 'proto');
	}


	_width.onclick = function  ( event ) {
		event.preventDefault();
		event.stopPropagation();
		// return false;
	}

	_height.onclick = function  ( event ) {
		event.preventDefault();
		event.stopPropagation();
	}


	_width.addEventListener('change', function  ( event ) {
		event.preventDefault();
		event.stopPropagation();
		mediator.notify( event, this, 'proto' );
		return false;
	}, false);

	_height.onchange = function  ( event ) {
		
		mediator.notify( event, this , 'proto' );
	}

	return {

		// public methods
		outPutDataPoints: function ( dp ) {
			
			if ( dp.height ) {
				_height.value = dp.height;
			}

			if ( dp.width ) {
				_width.value = dp.width;
			}

		},

		updatePercentages: function ( dp) {
			// get current x
			var _x = dp.width;
			var _y = dp.height;

			
			_widthpercent.value = ( _x / 173 ).toFixed(2);

			_heightpercent.value = ( _y / 198 ).toFixed(2);
			
		}
	}
}



var MODULE = ( function  ( ) {
	

	// var mediator;




	//*** MEDIATOR CONSTRUCTOR
	function Mediator (  ) {
		
		//private properties
		var observers = new ObserverList();

		var controls = new UserControls();

		var proto = new ProtoImage();

		var protoReference = {};

		var concreteSubject;

		var allow = false;


		// private methods

		function removeObservers ( obs ) {

			protoReference = obs;
			
			observers.removeAtIndex( observers.indexOf(obs, 0) );
		}

		var getDragOverCycle = ( function  ( ) {
			// returns a string to be sent as 3rd arg to notify

			if ( concreteSubject.getElementById('imgcontainer').childElementCount < 1 ) {
				return 'observer';
			}
			return 'proto';
		})

		function focusIframe ( stage ) {
			
			switch( stage.type ) {

				case 'dragover':
					document.getElementById('frame').style.border = '1px dashed black';
					break;

				case 'drop':
					var background = concreteSubject.getElementById('ibackground');
					background.style.opacity = 1.00;
					background.addEventListener('mousedown', function ( event ) {
						allow = true;
					}, false);

					background.addEventListener('mouseup', function ( event ) {
						allow = false;
						mediator.notify( event , this, 'proto');
					}, false);

					background.addEventListener('mousemove', function ( event ) {
						if ( allow === false ) {
							return false;
						};
						mediator.notify( event, this, 'observer');
					}, false);


					document.getElementById('frame').style.border = '';
					var imageID = document.getElementsByClassName('image-id');


					for (var i = imageID.length - 1; i >= 0; i--) {
						imageID[i].style.display = 'none';
					}

					break;

				case 'dragstart':
					var sOver = concreteSubject.getElementById('overlay');
					sOver.style.display = 'none';
					break;
			}
		}



		return {

			// public methods
			addObservers: function ( obs ) {
				observers.add(obs);
			},

			dev: function ( ) {
				var props = [];
				props[0] = observers;
				props[1] = controls;
				props[2] = proto;
				props[3] = concreteSubject;
				props[4] = protoReference;
				return props;
			},

			addConcreteIframeAfterLoad: function ( obj ) {
				concreteSubject = obj.contentDocument;

				proto.addWrapAfterLoad( concreteSubject.getElementById('imgcontainer') );

				// duplicate *** FIX
				concreteSubject.getElementById('ibackground').ondragover = function ( event ) {
					mediator.notify( event, this , getDragOverCycle() );
				};

				// concreteSubject.getElementById('ibackground').addEventListener('drop', mediator.notify, false);

				concreteSubject.getElementById('ibackground').ondrop = function  ( event ) {
					mediator.notify( event, this, getDragOverCycle() );
				};
				
				
			},

			notify: function ( event, obj, context ) {
				if ( context === 'observer' ) {

					switch ( event.type) {

						case 'dragstart':
							removeObservers( obj );
							obj.parentElement.id = 'tay';
							obj.style.height = '198px';
							obj.style.width = '178px';
							event.dataTransfer.EffectAllowed = 'copy';
							event.dataTransfer.setData('text/uri-list', obj.src);
							for( var i=0; i < observers.count(); i++) {
								observers.get(i).update({ 
									draggable: false, 
									style: ({ opacity: 0.5 }), 
									contentEditable: false 
								});
							}

							focusIframe( event );
							break;

						case 'drop':
							event.preventDefault();
							focusIframe( event );
							concreteSubject.getElementById('imgcontainer').appendChild( proto.returnInstance() );
							var ul = document.querySelector('.summary ul').style.maxHeight = 198 + 'px';
							break;

						case 'dragover':
							event.preventDefault();
							var src = proto.initWithContents( event.dataTransfer.getData('text/uri-list') );
							focusIframe( event );
							break;

						case 'dragend':

							if ( concreteSubject.getElementById('imgcontainer').childElementCount < 1 ) {
								return false;
							}
							controls.outPutDataPoints( proto.getBoxModel() );
							controls.updatePercentages( proto.getBoxModel() );
							break;

						case 'mousemove':
							
							if( !allow ) return false;
							var dp = proto.getBoxModel();
							protoReference.update( dp );
							controls.outPutDataPoints( dp );
							controls.updatePercentages( dp );
							break;

						default:
							console.log('mediator could not determind notification type');
							break;
					}
					
				} 

				if ( context === 'proto' ) {
					// 2nd phase DnD
					switch ( event.type ) {

						case 'dragover':

							event.preventDefault();
							event.dataTransfer.effectAllowed = 'copy';
							break;

						case 'mouseup':
							controls.outPutDataPoints( proto.getBoxModel() );
							controls.updatePercentages( proto.getBoxModel() );
							break;

						case 'dragstart':

							event.dataTransfer.effectAllowed = 'copy';
							var cord = {y: event.layerY, x: event.layerX };
							event.dataTransfer.mozSetDataAt('Files', cord, 0);
							break;

						case 'drop':

							event.preventDefault();
							var data = event.dataTransfer.mozGetDataAt('Files', 0 );														
							concreteSubject.getElementById('imgcontainer').style.top = event.clientY - data.y;
							concreteSubject.getElementById('imgcontainer').style.left = event.clientX - data.x;
							var protoref = protoref;
							protoReference.style.top = event.clientY - data.y - 99 + 'px';
							protoReference.style.left = event.clientX - data.x - 82 + 'px';
							break;

						case 'click':
							var ref = concreteSubject.getElementById('grid');
							var status = ref.style.display;

							if ( status === 'block' ) {
								return ref.style.display = 'none';
							}
							ref.style.display = 'block';
							break;

						case 'change':
							console.log('meditor onchange notify');
							var dp = ({ width: (obj.id === 'labelWidth' ? obj.value : null ), height: (obj.id === 'labelHeight' ? obj.value : null ) });
							proto.updateImageModel( dp  ); 
							break;

						default:
							console.log('cannot determine phase-2 event type');
					}
				}
				
			},

			getProtoRef: function  ( ) {
				if ( protoReference ) {
					return protoReference;
				}
			},

			getProtoModel: function  ( ) {
				return proto.getBoxModel();
			}
		}

	}

	var _static = {

		// static properties
		init:(function ( ) {

			if ( mediator === undefined ) {

				mediator = new Mediator();
				return mediator;

			}
		})
			
	};

	this.mediator = new Mediator();
	return mediator;


}());





function addObserversToMediator ( ) {
	var iLength =  document.querySelectorAll('li img');
	var aImages = [];
	// aImages.length = iLength;


	for (var i = iLength.length - 1; i >= 0; i--) {
		aImages.push( iLength[i] );
	}

	aImages.forEach( function ( element, index ) {

		mediator.addObservers( element );

		element.update = function ( context ) {
			if ( this.parentElement.id === 'tay') {
				// return console.log('ref only');
				var that = this;
				return (function ( pro ) {
					// console.log('inner');
					var origHeight =  that.style.height;
					origHeight = context.height + 'px';
					that.style.width = context.width + 'px';
					that.style.height = origHeight;
				}( this ))
			};

			for( var key in context ) {
				if( typeof context[key] === 'object') {
					for (var newstyle in context[key]) {
						element.style[newstyle] = context[key][newstyle];
					}
				} else {
					element[key] = context[key];
				}
			}
		}

		element.ondragstart = function  ( event ) {
			mediator.notify( event, this , 'observer');
		}

		element.ondragend = function  ( event ) {
			mediator.notify( event, this, 'observer');
		}


	})

	

}

addObserversToMediator();

window.frames[0].onload = function ( ) {
	var _iframe = document.getElementById('frame');
	mediator.addConcreteIframeAfterLoad(_iframe);
	
}









