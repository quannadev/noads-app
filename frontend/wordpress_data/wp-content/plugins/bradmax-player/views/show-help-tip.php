<?php
if (!defined('ABSPATH')) {
	exit;
}

// Check if file inclided in context of Bradmax_Player_Plugin class.
if (!$plugin_url) {
	exit;
}
?>
<!-- Inlined CSS styles for tips box. -->
<style type="text/css">
#bradmax-customization-tip {
	margin-bottom: 40px;
}

#bradmax-show-tips{
	cursor: pointer;
}

#bradmax-customization-tips {
	display:none;
}

#bradmax-customization-tip .tip {
	width: 300px;
	height: 280px;
	float:left;
	margin: 10px 10px 10px 0;
	padding: 10px;
	border: solid 1px #aaa;
	box-sizing: content-box;
}

#bradmax-customization-tip .tip h4 {
	margin: 0 0 10px 0;
}

#bradmax-customization-tip .tip img {
	border: solid 1px #aaa;
	width: 289px;
	cursor:pointer;
}

#bradmax-customization-tip .tip .description {
	margin: 20px 0 0 0;
	font-size: 12px;
}

#bradmax-customization-tip br{
	clear:both;
}

#bradmax-popup {
	cursor: pointer;
	transition: 0.3s;
	display: none;
	position: absolute;
	z-index: 10;
	padding: 60px 30px;
	left: 0;
	top: 10%;
	width: 90%;
	height: auto;
	overflow: auto;
	background-color: rgb(0,0,0);
	background-color: rgba(0,0,0,0.9);
}

#bradmax-popup .popup-content {
	margin: auto;
	display: block;
	width: 90%;
}

@keyframes zoom {
	from {transform:scale(0)}
	to {transform:scale(1)}
}

#bradmax-popup .close {
	position: absolute;
	top: 15px;
	right: 15px;
	color: #f1f1f1;
	font-size: 40px;
	font-weight: bold;
	transition: 0.3s;
}

@media only screen and (max-width: 700px){
	#bradmax-popup .popup-content {
		width: 100%;
	}
}
</style>

<!-- Inlined simple JS functions. -->
<script type="text/javascript">
<!--
function bradmax_enlarge_image(img) {
	var popup = document.getElementById('bradmax-popup');
	var popupImg = document.getElementById("bradmax-popup-img");
	popup.style.display = "block";
	popupImg.src = img.src;
	popup.onclick = function() {
		popup.style.display = "none";
		popup.onclick = null;
	}
}

function togle_tips() {
	var tips = document.getElementById('bradmax-customization-tips');
	var link = document.getElementById('bradmax-show-tips');
	if(tips.style.display == 'block') {
		tips.style.display = 'none';
		link.innerHTML = 'Show tips';
	} else {
		tips.style.display = 'block';
		link.innerHTML = 'Hide tips';
	}
	return false;
}
//-->
</script>

<div id="bradmax-popup">
	<span class="close">&times;</span>
	<img class="popup-content" id="bradmax-popup-img">
</div>

<div class="update-nag" id="bradmax-customization-tip">
	You are using default bradmax player with version: v<?php echo self::BRADMAX_PLAYER_VERSION; ?>.
	<br />
	<br />
	If you want to customize it (skin and features), then please sign-up on
	<a href="https://bradmax.com/site/en/signup" target="_blank">https://bradmax.com/site/en/signup</a> ,
	generate new player and upload it using form below. It's free.
	<br />
	<br />

	<a href="#" id="bradmax-show-tips" onclick="return togle_tips()">Show tips</a>

	<div id="bradmax-customization-tips">
		<div class="tip">
			<h4>Step 1: Sign up</h4>
			<img alt="Sign up" onclick="bradmax_enlarge_image(this)" src="<?php echo $plugin_url . self::CUSTOM_PLAYER_TIP_SCREEN_01; ?>" />
			<div class="description">
				Create free account using singup form at
				<a href="https://bradmax.com/site/en/signup" target="_blank">https://bradmax.com/site/en/signup</a>
			</div>
		</div>
		<div class="tip">
			<h4>Step 2: Sign in</h4>
			<img alt="Sign in" onclick="bradmax_enlarge_image(this)" src="<?php echo $plugin_url . self::CUSTOM_PLAYER_TIP_SCREEN_02; ?>" />
			<div class="description">
				After email confirmation and password setup login by form
				<a href="https://bradmax.com/site/en/signin" target="_blank">https://bradmax.com/site/en/signin</a>
			</div>
		</div>
		<div class="tip">
			<h4>Step 3: Add new player</h4>
			<img alt="Add new player" onclick="bradmax_enlarge_image(this)" src="<?php echo $plugin_url . self::CUSTOM_PLAYER_TIP_SCREEN_03; ?>" />
			<div class="description">
				Open page "Players List" and click "create new+" button. Enter any name and click button "create".
			</div>
		</div>
		<div class="tip">
			<h4>Step 4: Configure player</h4>
			<img alt="Configure player" onclick="bradmax_enlarge_image(this)" src="<?php echo $plugin_url . self::CUSTOM_PLAYER_TIP_SCREEN_04; ?>" />
			<div class="description">
				Player configuration page will appear. Choose player skin, colors, etc.
			</div>
		</div>
		<div class="tip">
			<h4>Step 5: Generate player</h4>
			<img alt="Generate player" onclick="bradmax_enlarge_image(this)" src="<?php echo $plugin_url . self::CUSTOM_PLAYER_TIP_SCREEN_05; ?>" />
			<div class="description">
				When player fits to your needs, click button "save changes" in right top corner.
				When popup appears click "yes, generate files" button.
			</div>
		</div>
		<div class="tip">
			<h4>Step 6: Download zip</h4>
			<img alt="Download zip" onclick="bradmax_enlarge_image(this)" src="<?php echo $plugin_url . self::CUSTOM_PLAYER_TIP_SCREEN_06; ?>" />
			<div class="description">
				On player versions list click button "downloa zip". You will download zip file, which contain
				generated player in JavaScript file and some example files.
			</div>
		</div>
		<div class="tip">
			<h4>Step 7: Uncompress player file and upload</h4>
			<img alt="Uncompress player" onclick="bradmax_enlarge_image(this)" src="<?php echo $plugin_url . self::CUSTOM_PLAYER_TIP_SCREEN_07; ?>" />
			<div class="description">
				After downloading zip file uncompress it. Then you are ready to upload bradmax_player.js file directly into
				wordpress plugin (use form below) for seeing your customized player on your page.
			</div>
		</div>
		<br />
	</div>
</div>

