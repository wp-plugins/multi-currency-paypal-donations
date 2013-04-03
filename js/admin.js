function restoreDefaults(){
	document.getElementById('backgroundColor').color.fromString('E0FFD8');
	document.getElementById('topBackgroundColor').color.fromString('BEEF77');
	document.getElementById('topGradient').color.fromString('89C403');
	document.getElementById('bottomGradient').color.fromString('77A809');
	document.getElementById('border').color.fromString('74B807');
	document.getElementById('textColor').color.fromString('FFFFFF');
}

function setPallet(topBack, mainBack, text, topGrad, botGrad, border, buttonText){
	document.getElementById('topBackgroundColor').color.fromString(topBack);
	document.getElementById('nationality').style.backgroundColor = topBack;

	document.getElementById('backgroundColor').color.fromString(mainBack);
	document.getElementById('mcpdleftcol').style.backgroundColor = mainBack;

	document.getElementById('text').color.fromString(text);
	document.getElementById('mcpdcontent').style.color = text;

	document.getElementById('topGradient').color.fromString(topGrad);
	document.getElementById('bottomGradient').color.fromString(botGrad);
	document.getElementById('border').color.fromString(border);
	document.getElementById('buttonText').color.fromString(buttonText);
	
	var buttonStyles = "";
	buttonStyles = ".mcpddonatenow { \
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, " + topGrad + "), color-stop(1, " + botGrad + ") ); \
	background:-moz-linear-gradient( center top, " + topGrad + " 5%, " + botGrad + " 100% ); \
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='" + topGrad + "', endColorstr='" + botGrad + "'); \
	background-color:" + topGrad + "; \
	-moz-border-radius:6px; \
	-webkit-border-radius:6px; \
	border-radius:6px; \
	border:1px solid " + border + "; \
	display:inline-block; \
	color:" + buttonText + " !important; \
	font-family:arial; \
	font-size:21px; \
	font-weight:bold; \
	padding:19px 24px; \
	margin-left: 10px; \
	text-decoration:none; \
}.mcpddonatenow:hover { \
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, " + botGrad + "), color-stop(1, " + topGrad + ") ); \
	background:-moz-linear-gradient( center top, " + botGrad + " 5%, " + topGrad + " 100% ); \
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='" + botGrad + "', endColorstr='" + topGrad + "'); \
	background-color:" + botGrad + "; \
}.mcpddonatenow:active { \
	position:relative; \
	top:1px; \
}";
  jQuery(document).ready(function($) {
	  $('style#donateButton').remove();
	  $('head').append('<style id="donateButton" type="text/css">' + buttonStyles + '</style>');
  });
}

function updatePallet(){
	c1 = document.getElementById('topBackgroundColor').value;
	c2 = document.getElementById('backgroundColor').value;
	c3 = document.getElementById('text').value;
	c4 = document.getElementById('topGradient').value;
	c5 = document.getElementById('bottomGradient').value;
	c6 = document.getElementById('border').value;
	c7 = document.getElementById('buttonText').value;

	setPallet(c1, c2, c3, c4, c5 ,c6, c7);
}