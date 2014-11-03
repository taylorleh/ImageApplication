

function extend ( extension, obj ) {
	for (var key in extension ) {
		obj[key] = extension[key];
	}
}





(function ( ) {
	

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


	function Observer ( ) {
		this.update = function () {};
	}


	// *** ABSTRACT IMAGE ****

	function AbstractImage () {
		this.name = 'Abstract';
		var div = document.createElement('div');
		extend( abstractDefaults , div );
		var img = document.createElement('img');

		div.onmousemove = function  ( e ) {
			if ( subject.isMouseDown() ) {
			
				// console.log('div mouse move registered', this, e , 'prior:  ', div.height);

				subject.notify( this, e );


			}
		};


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

	// function ConcreteSubject ( ) {
	// 	this.ondragover = function ( e ) {
	// 		console.log('dragover this: ', this);
	// 	}
	// }



	// ** Helper Functions
	var abstractDefaults = {
		id: 'editcontainer',
		draggable: true,
		contentEditable: true
	};



	// DOM API
	var _concretesub = document.getElementById('subject');


	//****** INSTANCE
	var instance;



	// ** INITIALIZE CONSTRUCTOR

	function InitSubject (  sub ) {
		
		// $private properties
		var concretesubject = sub;
		var _isMouseDown = false;

		var observers = new ObserverList();

		var abstractimg;



		
		// User controls
		var usercontrols =(function ( ) {

			// instance
			var _controls;

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
					// subject.notify(this, e);
					abstractimg.updateDimensions({ width: this.value });
				};
				_height.onchange = function ( e ) {
					// subject.notify(this, e );
					abstractimg.updateDimensions({ height: this.value });
				};

				toggleGrid.onclick = function ( e ) {
					if ( !overlayStatus ) {
						overlayStatus = true;
						return imgrule.style.visibility = "visible";
						// return subject.notify( imgrule, e );
					} 
					overlayStatus = false;
					imgrule.style.visibility = "hidden";
				};
				return {

					// toggleGridButton: document.getElementById('showgrid'),

					overlayStatus: function ( ) {
						return overlayStatus;
					},

					outputDataPoints: function  ( dp ) {
						
						_width.value = dp.width;
						_height.value = dp.height;
						// console.log(dp);
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

			if ( _controls === undefined ) {
				_controls = new Controls();
			}
			return _controls;

		})();

		// Event Handlers
		// concretesubject.ondragover = function ( e ) {
		// 	e.preventDefault();
		// 	e.dataTransfer.effectAllowed = 'copy';
		// 	if ( abstractimg === undefined ) {
		// 		// instantiate editor proto
		// 		abstractimg = new AbstractImage();
		// 		abstractimg.setImgSrc( e.dataTransfer.getData('text/uri-list') );
		// 	} 
		// 	return;
		// };

		// concretesubject.ondrop = function ( e ) {

		// 	e.preventDefault();
		// 	if ( abstractimg ) {
		// 		concretesubject.appendChild( abstractimg.getProto() );
		// 		usercontrols.outputDataPoints( abstractimg.boxModel() );
		// 		// abstractimg.prototype = {};
		// 	}
				
		// };

		// concretesubject.onmousedown = function ( e ) {
		// 	_isMouseDown = true;
		// }

		// concretesubject.onmouseup = function ( e ) {
		// 	_isMouseDown = false;
		// }

		


		// Private Methods
		function handleDrag ( e ) {
			e.stopPropagation();
			if ( e.target.draggable === false ) {
				return e.preventDefault();
			}
			e.dataTransfer.effectAllowed = "copy";
			e.dataTransfer.setData( 'text/uri-list', e.target.src );
			console.log("Transfer data set and is: ", e.dataTransfer.getData('text/uri-list'), this );
			observers.removeAtIndex( observers.indexOf( this, 0 ) );
			for (var i=0; i < observers.count() ; i++ ) {
				observers.get(i).update({
					draggable:false,
					contentEditable: false,
					style: { opacity: 0.5 }
				});
			}

		}

		function HandleProto ( ) {
			console.log('worked');
			usercontrols.outputDataPoints( abstractimg.boxModel() );

			
		}

		
		// Static Properties & Methods
		subject = {

			//private methods
			addObserver: function ( obs ) {
				if ( !obs.isPrototypeOf( Observer ) ) {

					extend( new Observer, obs );

					// event listener
					obs.addEventListener( 'dragstart', handleDrag, false );

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
					}

				}
				return observers.add( obs );
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
				if ( e.type === 'mousemove' ) {
					usercontrols.outputDataPoints( abstractimg.boxModel() );
				}
			},
			isMouseDown: function  ( ) {
				return _isMouseDown;
			}


		}
		return subject;
	}



	if ( instance === undefined ) {
		instance = new InitSubject( _concretesub );
	}
	return instance;


}());




function addObserversToSubject ( ) {

	var images = document.querySelectorAll('li img');

	for (var i = 0; i < images.length; i++) {
		// add images to observer watch list
		subject.addObserver( images[i] );

	}

}


addObserversToSubject();




























