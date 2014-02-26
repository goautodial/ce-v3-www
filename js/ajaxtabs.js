//############################################################################################
//####  Name:             ajaxtabs.js                                                     ####
//####  Version:          2.0                                                             ####
//####  Copyright:        GOAutoDial Inc. - Januarius Manipol <januarius@goautodial.com>  ####
//####  License:          AGPLv2                                                          ####
//############################################################################################

var ddajaxtabssettings={}
ddajaxtabssettings.bustcachevar=1  
ddajaxtabssettings.loadstatustext="<img src='../ajaxlib/preloader.gif' /> Requesting content..." 

function ddajaxtabs(tabinterfaceid, contentdivid){
	this.tabinterfaceid=tabinterfaceid 
	this.tabs=document.getElementById(tabinterfaceid).getElementsByTagName("a") 
	this.enabletabpersistence=true
	this.hottabspositions=[]
	this.currentTabIndex=0
	this.contentdivid=contentdivid
	this.defaultHTML=""
	this.defaultIframe='<iframe src="about:blank" marginwidth="0" marginheight="0" frameborder="0" vspace="0" hspace="0" class="tabcontentiframe" style="width:100%; height:auto; min-height: 440px"></iframe>'
	this.defaultIframe=this.defaultIframe.replace(/<iframe/i, '<iframe name="'+"_ddajaxtabsiframe-"+contentdivid+'" ')
	this.revcontentids=[]
	this.selectedClassTarget="link"
}

ddajaxtabs.connect=function(pageurl, tabinstance){
	var page_request = false
	var bustcacheparameter=""
	if (window.ActiveXObject){
		try {
		page_request = new ActiveXObject("Msxml2.XMLHTTP")
		} 
		catch (e){
			try{
			page_request = new ActiveXObject("Microsoft.XMLHTTP")
			}
			catch (e){}
		}
	}
	else if (window.XMLHttpRequest)
		page_request = new XMLHttpRequest()
	else
		return false
	var ajaxfriendlyurl=pageurl.replace(/^http:\/\/[^\/]+\//i, "http://"+window.location.hostname+"/") 
	page_request.onreadystatechange=function(){ddajaxtabs.loadpage(page_request, pageurl, tabinstance)}
	if (ddajaxtabssettings.bustcachevar)
		bustcacheparameter=(ajaxfriendlyurl.indexOf("?")!=-1)? "&"+new Date().getTime() : "?"+new Date().getTime()
	page_request.open('GET', ajaxfriendlyurl+bustcacheparameter, true)
	page_request.send(null)
}

ddajaxtabs.loadpage=function(page_request, pageurl, tabinstance){
	var divId=tabinstance.contentdivid
	document.getElementById(divId).innerHTML=ddajaxtabssettings.loadstatustext
	if (page_request.readyState == 4 && (page_request.status==200 || window.location.href.indexOf("http")==-1)){
		document.getElementById(divId).innerHTML=page_request.responseText
		ddajaxtabs.ajaxpageloadaction(pageurl, tabinstance)
	}
}

ddajaxtabs.ajaxpageloadaction=function(pageurl, tabinstance){
	tabinstance.onajaxpageload(pageurl)
}

ddajaxtabs.getCookie=function(Name){ 
	var re=new RegExp(Name+"=[^;]+", "i");
	if (document.cookie.match(re))
		return document.cookie.match(re)[0].split("=")[1]
	return ""
}

ddajaxtabs.setCookie=function(name, value){
	document.cookie = name+"="+value+";path=/" 
}

ddajaxtabs.prototype={

	expandit:function(tabid_or_position){ 
		this.cancelautorun() 
		var tabref=""
		try{
			if (typeof tabid_or_position=="string" && document.getElementById(tabid_or_position).getAttribute("rel")) 
				tabref=document.getElementById(tabid_or_position)
			else if (parseInt(tabid_or_position)!=NaN && this.tabs[tabid_or_position].getAttribute("rel")) 
				tabref=this.tabs[tabid_or_position]
		}
		catch(err){alert("Invalid Tab ID or position entered!")}
		if (tabref!="")
			this.expandtab(tabref)
	},

	cycleit:function(dir, autorun){
		if (dir=="next"){
			var currentTabIndex=(this.currentTabIndex<this.hottabspositions.length-1)? this.currentTabIndex+1 : 0
		}
		else if (dir=="prev"){
			var currentTabIndex=(this.currentTabIndex>0)? this.currentTabIndex-1 : this.hottabspositions.length-1
		}
		if (typeof autorun=="undefined")
			this.cancelautorun()
		this.expandtab(this.tabs[this.hottabspositions[currentTabIndex]])
	},

	setpersist:function(bool){ 
			this.enabletabpersistence=bool
	},

	loadajaxpage:function(pageurl){
		ddajaxtabs.connect(pageurl, this)
	},

	loadiframepage:function(pageurl){
		this.iframedisplay(pageurl, this.contentdivid)
	},

	setselectedClassTarget:function(objstr){
		this.selectedClassTarget=objstr || "link"
	},

	getselectedClassTarget:function(tabref){ 
		return (this.selectedClassTarget==("linkparent".toLowerCase()))? tabref.parentNode : tabref
	},

	urlparamselect:function(tabinterfaceid){
		var result=window.location.search.match(new RegExp(tabinterfaceid+"=(\\d+)", "i")) 
		return (result==null)? null : parseInt(RegExp.$1) 
	},

	onajaxpageload:function(pageurl){ 
		
	},

	expandtab:function(tabref){
		var relattrvalue=tabref.getAttribute("rel")
		var associatedrevids=(tabref.getAttribute("rev"))? ","+tabref.getAttribute("rev").replace(/\s+/, "")+"," : ""
		if (relattrvalue=="#default")
			document.getElementById(this.contentdivid).innerHTML=this.defaultHTML
		else if (relattrvalue=="#iframe")
			this.iframedisplay(tabref.getAttribute("href"), this.contentdivid)
		else
			ddajaxtabs.connect(tabref.getAttribute("href"), this)
		this.expandrevcontent(associatedrevids)
		for (var i=0; i<this.tabs.length; i++){ 
			this.getselectedClassTarget(this.tabs[i]).className=(this.tabs[i].getAttribute("href")==tabref.getAttribute("href"))? "selected" : ""
		}
		if (this.enabletabpersistence) 
			ddajaxtabs.setCookie(this.tabinterfaceid, tabref.tabposition)
		this.setcurrenttabindex(tabref.tabposition) 
	},

	iframedisplay:function(pageurl, contentdivid){
		if (typeof window.frames["_ddajaxtabsiframe-"+contentdivid]!="undefined"){
			try{delete window.frames["_ddajaxtabsiframe-"+contentdivid]} 
			catch(err){}
		}
		document.getElementById(contentdivid).innerHTML=this.defaultIframe
		window.frames["_ddajaxtabsiframe-"+contentdivid].location.replace(pageurl) 
	},


	expandrevcontent:function(associatedrevids){
		var allrevids=this.revcontentids
		for (var i=0; i<allrevids.length; i++){ 
			document.getElementById(allrevids[i]).style.display=(associatedrevids.indexOf(","+allrevids[i]+",")!=-1)? "block" : "none"
		}
	},

	setcurrenttabindex:function(tabposition){ 
		for (var i=0; i<this.hottabspositions.length; i++){
			if (tabposition==this.hottabspositions[i]){
				this.currentTabIndex=i
				break
			}
		}
	},

	autorun:function(){ 
		this.cycleit('next', true)
	},

	cancelautorun:function(){
		if (typeof this.autoruntimer!="undefined")
			clearInterval(this.autoruntimer)
	},

	init:function(automodeperiod){
		var persistedtab=ddajaxtabs.getCookie(this.tabinterfaceid) 
		var selectedtab=-1 
		var selectedtabfromurl=this.urlparamselect(this.tabinterfaceid)
		this.automodeperiod=automodeperiod || 0
		this.defaultHTML=document.getElementById(this.contentdivid).innerHTML
		for (var i=0; i<this.tabs.length; i++){
			this.tabs[i].tabposition=i 
			if (this.tabs[i].getAttribute("rel")){
				var tabinstance=this
				this.hottabspositions[this.hottabspositions.length]=i 
				this.tabs[i].onclick=function(){
					tabinstance.expandtab(this)
					tabinstance.cancelautorun() 
					return false
				}
				if (this.tabs[i].getAttribute("rev")){ 
					this.revcontentids=this.revcontentids.concat(this.tabs[i].getAttribute("rev").split(/\s*,\s*/))
				}
				if (selectedtabfromurl==i || this.enabletabpersistence && selectedtab==-1 && parseInt(persistedtab)==i || !this.enabletabpersistence && selectedtab==-1 && this.getselectedClassTarget(this.tabs[i]).className=="selected"){
					selectedtab=i 
				}
			}
		} 
		if (selectedtab!=-1) 
			this.expandtab(this.tabs[selectedtab]) 
		else
			this.expandtab(this.tabs[this.hottabspositions[0]])
		if (parseInt(this.automodeperiod)>500 && this.hottabspositions.length>1){
			this.autoruntimer=setInterval(function(){tabinstance.autorun()}, this.automodeperiod)
		}
	} 

}