    <div id="footer">
        Copyright, <?php echo date('Y'); ?>
    </div>

</body>
<script type="text/javascript" src="javascript/usernav.js"></script>
<!-- <script type="text/javascript" src="javascript/mediator.js"></script> -->
<script type="text/javascript" src="javascript/dragObject.js"></script>
<script type="text/javascript" src="javascript/httpcomment.js"></script>
</html>
<?php
	// close connection
	if(isset($database)){$database->close_connection();};
?>