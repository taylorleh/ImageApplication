    <div style="display:none;" id="footer">
        Copyright <?php echo date('Y'); ?>
    </div>

</body>
</html>
<?php
	// close connection
	if(isset($database)){$database->close_connection();};
?>