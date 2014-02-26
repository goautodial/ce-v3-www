//############################################################################################
//####  Name:             goiframe.js                                                     ####
//####  Version:          2.0                                                             ####
//####  Copyright:        GOAutoDial Inc. - Januarius Manipol <januarius@goautodial.com>  ####
//####  License:          AGPLv2                                                          ####
//############################################################################################

function goToURL(page, name) {
	goiframe = document.getElementById('goiframe');
	if (page == "support") {
		goiframe.src = "http://goautodial.com/support-ticket";
	} else {
		goiframe.src = "../"+page;	
	}
	document.getElementById("bannertext").innerHTML = "<p>"+document.getElementById(name).title+"</p>";
}

//-->
