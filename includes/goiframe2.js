//############################################################################################
//####  Name:             goiframe2.js                                                    ####
//####  Version:          2.0                                                             ####
//####  Copyright:        GOAutoDial Inc. - Januarius Manipol <januarius@goautodial.com>  ####
//####  License:          AGPLv2                                                          ####
//############################################################################################

function goToURL(page,name) {
	var goiframe = document.getElementById('goiframe');
	if (page == "support") {
		goiframe.src = "http://goautodial.com/support-ticket";
	} else {
		goiframe.src = "../"+page;
	}
	document.getElementById("bannertext").innerHTML = "<p>"+document.getElementById(name).title+"</p>";
}

function reSize(x)
{
	setInterval("fixIframeSize()",x);
}

function fixIframeSize() {
	var goIframe = document.getElementById('goiframe');
	
//	var newHeight = goIframe.contentDocument.body.clientHeight;
//	if (goIframe.contentDocument.body.offsetHeight > newHeight) {
		newHeight = goIframe.contentDocument.body.offsetHeight + 5;
//	}
	
//	alert(goIframe.contentDocument.body.scrollHeight+" > "+goIframe.contentDocument.body.clientHeight+" ... "+goIframe.contentDocument.body.offsetHeight+" ... "+goIframe.contentDocument.documentElement.scrollHeight);
	
	goIframe.height = newHeight + "px";
}
