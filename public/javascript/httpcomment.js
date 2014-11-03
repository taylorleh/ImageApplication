
( function  ( ) {
	

	var btnSubmitComment = document.getElementById('submitcomment');

	

	

	function buildResuest (  ) {
			
			if ( request.readyState === 4 ) {
				console.log('request state change 4.');
				console.log('success');
			};

	}


	function initHeader ( ) {

		var uri = new Array();

		var base = 'http://intranet.dev:8080/createcomment.php?';
		// var _comment = mediator.getProtoModel();
		var _comment = ( function  ( ) {
			var dp = mediator.getProtoModel();
			var string = 'Width: '   + dp.width + 'px'  + ' (' +  (dp.width / 173).toFixed(2)   +  '%)'  + ' ';
			string    += 'Height: '  + dp.height + 'px' + ' (' +  (dp.height / 198 ).toFixed(2) + '%)'  ;
			return string;
		}());

		uri = document.URL.split('&' , 3 );


		uri.shift();

		uri.push( 'family=' +  mediator.getProtoRef().previousElementSibling.innerHTML ); 
		uri.push('other=1');
		uri.push('submit=Request');
		uri.push( 'image=' + mediator.getProtoRef().previousElementSibling.innerHTML + '_1' );
		uri.push('comment=' + _comment );
		// uri.push('shadow=0');

		var params = uri.join('&');

		var fullrequest = base + params;

		var request = new XMLHttpRequest();
		
		console.log('request object:', request);

		// request.setRequestHeader('content-type', 'application/x-www-form-urlendcode');
		request.open('GET', fullrequest , false);

		request.onreadystatechange = function  ( ) {
			console.log('submited ');
		}

		request.send();
	}


	btnSubmitComment.addEventListener('click', initHeader, false );


}())

