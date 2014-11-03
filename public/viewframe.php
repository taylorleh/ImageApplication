
<style type="text/css">

#ibackground {
	/*background: #383838;
	overflow: auto;*/
	height: 396px;
	width: 346px;
	background-image: url("http://50.129.148.67:8080/images/editor/Gridfull.png");
	background-size: 346px 396px;
	background-repeat: no-repeat;
	resize:none;
	display:block;
	opacity: .4;
}

#imgcontainer {
	height: 198px; width: 173px;
	overflow: auto;
	resize: both;
	padding: 8px;
	position: relative;
	top: 99;
	left: 82px;
}


#dropzone {
	width: inherit;
	height: inherit;
}



body {
	/*text-align: -moz-center;*/
	margin: 0;
	padding: 0;
	background: transparent;
}

#grid {
	width: inherit; height: inherit;
	position: fixed;
	display: none;
	z-index: 2;
}

.wrap {
	text-align: center;
}

h2.overlay {
	z-index: 3;
	position: fixed;
	top: 47%;
	left: 20%;
	margin: 0;
}


</style>


<html>
<head>
</head>
<body>
	<div class="wrap">
		<h2 id='overlay' class="overlay">DRAG IMAGE HERE</h2>

				<div  id='ibackground'>
					<img id="grid" src="images/editor/GridRule.png">
					

				<div draggable='true' id="imgcontainer">
					
				<!-- <img contentEditable='false' id="dropzone" src="" > -->
				</div>
				</div>
		
		
	</div>
</body>

</html>