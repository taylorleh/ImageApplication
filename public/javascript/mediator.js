


// window.frames[0].onload = function () {
	


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
		
		var img = new Image();

		// img.ondragstart = function  ( e ) {
		// 	console.log('proto drag', e);
		// 	e.dataTransfer.effectAllowed = 'move';
		// }

		return {

			initWithSource: function ( e ) {
				
				if ( !img.src ) {
					img.src = e.dataTransfer.getData('text/uri-list');
					img.id = 'dropzone';
				}
				// return img;
			},

			returnInstance: function ( ) {
				if ( img.src ) {
					return img;
				}
			},

			boxmodel: function ( ) {
				return {
					height: img.height,
					width: img.width
				};
			},

			updateDimension: function ( dp ) {
				for ( var key in dp )

					switch( key ) {

						case 'height':
							img.parentElement.style.height = dp.height;
							break;

						case 'width':
							img.style.width = dp.width + 'px';
							break;

						default:
							console.log('none found');

					}
			}
		}
	}


	var UserControls = (function ( ) {
		
		// properties of contorls
		function Controls ( ) {

			// width input label
			var _width = document.getElementById('labelWidth');

			// height input label
			var _height = document.getElementById('labelHeight');

			var overlayStatus = false;

			var toggleGrid = document.getElementById('showgrid');

			var oResetBtn = document.getElementById('reset');

			// grid image 
			


			_width.onchange = function ( e ) {
				mediator.notify( e, this, {width: this.value} );
			};
			_height.onchange = function ( e ) {
				mediator.notify( e, this, {height: this.value} );
			};


			toggleGrid.onclick = function ( e ) {
				mediator.notify( e, this );
			};

			oResetBtn.onclick = function  ( ) {
				
				if ( confirm('reset editor?') ) {
					console.log('reset initialized');
					mediator.requestEditorReset();
				} 
			};


			// private methods (i think)
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


	var mediator;
	var _images = document.querySelectorAll('li img');


	var MODULE = ( function  ( ) {




		function initMediator ( ) {

			var _frame = document.getElementById('frame');
			var doc = document.getElementById('frame').contentDocument;
			var concretesubject = doc.getElementById('imgcontainer');
			var bkg = doc.getElementById('ibackground');
			var sOverLay = doc.getElementById('overlay');
			
			var observers = new ObserverList();

			var protoImage = new ProtoImage();

			var userControls = UserControls.getInstance();


			var allow = false;

			function handleDragOver ( e ) {
				// console.log('this is drag over:', this, e );
				// e.dataTransfer.EffectAllowed = 'move';

				switch( e.type ) {

					case 'dragover':
						// console.log('drag over case', e.dataTransfer.getData('text/uri-list'));
						e.preventDefault();
						protoImage.initWithSource( e );
						_frame.style.border = '';
						_frame.id = 'over';
						break;

					case 'drop':
						e.preventDefault();
						this.appendChild( protoImage.returnInstance() );
						concretesubject.removeEventListener('dragover', handleDragOver, false);
						concretesubject.removeEventListener('drop', handleDragOver, false);
						userControls.outputDataPoints( protoImage.boxmodel() );
						sOverLay.style.display = 'none';

						break;
						// bkg.addEventListener('dragover', handleProtoDrag, false);
						// bkg.addEventListener('drop', handleProtoDrop, false);

				}
			}
			var flag = 0;
			function handleProtoDragOver ( e ) {
				if ( flag < 1 ) {
					flag += 1;
					console.log('proto drag over:  ', this, e );
				}
				e.preventDefault();
				e.dataTransfer.effectAllowed = 'move';
				
			}

			function handleProtoDrop ( e , cord) {
				console.log('proto DROP:  ', e , 'this: ', this, arguments);
				e.preventDefault();
				e.dataTransfer.dropEffect = 'move';
				var data = [e.layerX, e.layerY];
				var transferedDataX = e.dataTransfer.mozGetDataAt('text', 1);
				var transferedDataY = e.dataTransfer.mozGetDataAt('text', 0);

				console.log('X:  ', e.layerX, 'Y: ', e.layerY);
				concretesubject.style.top = e.clientY - transferedDataY;
				concretesubject.style.left = e.clientX - transferedDataX;
				flag = 0;
			}

			function handleProtoDragStart ( e ) {
				console.log('proto drag start:  ', e, this);
				e.stopPropagation();
				e.dataTransfer.effectAllowed = 'move';
				var cordY =  e.layerY;
				var cordX = e.layerX;
				e.dataTransfer.mozSetDataAt('text', e.layerY, 0);
				e.dataTransfer.mozSetDataAt('text', e.layerX, 1);
				console.log( 'X: ', cordX, 'Y: ', cordY, 'current calculated top:  ');
				
			}

			concretesubject.addEventListener('dragover', handleDragOver, false);

			concretesubject.addEventListener('drop', handleDragOver, false);


			concretesubject.onmouseup = function  ( e ) {
				
				userControls.outputDataPoints( protoImage.boxmodel() );
				allow = false;
			}

			concretesubject.onmousedown = function  ( e ) {
				allow = true
			}


			concretesubject.onmousemove = function  ( e ) {
				if(!allow) {
					return false;
				}
				// console.log('mouse move', e);
				userControls.outputDataPoints( protoImage.boxmodel() );
			}

			concretesubject.ondragleave = function ( e ) {
				_frame.id = '';
			}

		


			return {

				addObservers: function ( obs ) {
					observers.add( obs );
				},

				notify: function ( e, obs, ctx ) {

					if ( e.type === 'change') {
						
						if( typeof ctx === 'object') {
							for ( var key in ctx ) {

								switch( key ) {

									case 'height':
										concretesubject.style.height = ctx[key];
										break;

									case 'width':
										concretesubject.style.width = ctx[key];
										break;
								}
							}
						}
					}

					if ( e.type === 'click' ) {
						var rule = doc.getElementById('grid');
						var status = rule.style.display;

						if ( status === 'block') {
							return rule.style.display = 'none';
						}
						rule.style.display = 'block';

					}

					if ( e.type === 'dragend' ) {
						console.log('drag end in notify method');
						if ( concretesubject.childElementCount < 1 ) {
							return false;
						}
						_frame.id = '';
						bkg.style.opacity = 1.0;
						bkg.addEventListener('dragover', handleProtoDragOver, false);
						bkg.addEventListener('drop', handleProtoDrop, false);
						concretesubject.addEventListener('dragstart', handleProtoDragStart, false);
					}

					if ( e.type === 'dragstart' ) {
						// bkg.style.opacity = 0.7;
						_frame.style.border = '1px dashed white';
						// _frame.id = 'over';

					}
					
					observers.removeAtIndex( observers.indexOf( obs, 0 ) );

					for (var i = 0; i < observers.count(); i++) {
						observers.get(i).update({
							draggable: false,
							style:({ opacity: 0.5 }),
							contentEditable: false
						});
					}
				},

				requestEditorReset: function ( ) {
					console.log('mediator evaluating reset request', this, 'mediator: ', mediator, initMediator);
					for ( var key in mediator) {
						console.log('key:', key, 'mediator[key]', mediator[key]);

					}
				}
			};

		}

		if ( mediator === undefined ) {
			mediator = new initMediator();
		}
		


	}());



	function Observer ( ) {
		this.update = function () {};
	}



	function addObservers ( obs ) {
		
		extend( new Observer, obs );

		mediator.addObservers( obs );

		obs.ondragstart = function  ( e ) {
			console.log('original image Event:  ', e );

			// e.preventDefault();
			e.dataTransfer.EffectAllowed = "copy";
			e.dataTransfer.setData('text/uri-list', this.src);
			mediator.notify(e, this );

		}

		obs.ondragend = function  ( e ) {
			console.log('the observer has ended drag:  ', this, e );
			mediator.notify(e , this);
		}

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


	for (var i = 0; i < _images.length; i++) {

		addObservers( _images[i] );

	}



// }