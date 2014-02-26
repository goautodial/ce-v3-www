(function(a){a.fn.hoverIntent=function(l,j){var m={sensitivity:7,interval:100,timeout:0};m=a.extend(m,j?{over:l,out:j}:l);var o,n,h,d;var e=function(f){o=f.pageX;n=f.pageY};var c=function(g,f){f.hoverIntent_t=clearTimeout(f.hoverIntent_t);if((Math.abs(h-o)+Math.abs(d-n))<m.sensitivity){a(f).unbind("mousemove",e);f.hoverIntent_s=1;return m.over.apply(f,[g])}else{h=o;d=n;f.hoverIntent_t=setTimeout(function(){c(g,f)},m.interval)}};var i=function(g,f){f.hoverIntent_t=clearTimeout(f.hoverIntent_t);f.hoverIntent_s=0;return m.out.apply(f,[g])};var b=function(q){var f=this;var g=(q.type=="mouseover"?q.fromElement:q.toElement)||q.relatedTarget;while(g&&g!=this){try{g=g.parentNode}catch(q){g=this}}if(g==this){if(a.browser.mozilla){if(q.type=="mouseout"){f.mtout=setTimeout(function(){k(q,f)},30)}else{if(f.mtout){f.mtout=clearTimeout(f.mtout)}}}return}else{if(f.mtout){f.mtout=clearTimeout(f.mtout)}k(q,f)}};var k=function(p,f){var g=jQuery.extend({},p);if(f.hoverIntent_t){f.hoverIntent_t=clearTimeout(f.hoverIntent_t)}if(p.type=="mouseover"){h=g.pageX;d=g.pageY;a(f).bind("mousemove",e);if(f.hoverIntent_s!=1){f.hoverIntent_t=setTimeout(function(){c(g,f)},m.interval)}}else{a(f).unbind("mousemove",e);if(f.hoverIntent_s==1){f.hoverIntent_t=setTimeout(function(){i(g,f)},m.timeout)}}};return this.mouseover(b).mouseout(b)}})(jQuery);
var showNotice,adminMenu,columns,validateForm;(function(a){adminMenu={init:function(){var b=a("#adminmenu");a(".wp-menu-toggle",b).each(function(){var c=a(this),d=c.siblings(".wp-submenu");if(d.length){c.click(function(){adminMenu.toggle(d)})}else{c.hide()}});this.favorites();a(".separator",b).click(function(){if(a("body").hasClass("folded")){adminMenu.fold(1);deleteUserSetting("mfold")}else{adminMenu.fold();setUserSetting("mfold","f")}return false});if(a("body").hasClass("folded")){this.fold()}this.restoreMenuState()},restoreMenuState:function(){a("li.wp-has-submenu","#adminmenu").each(function(c,d){var b=getUserSetting("m"+c);if(a(d).hasClass("wp-has-current-submenu")){return true}if("o"==b){a(d).addClass("wp-menu-open")}else{if("c"==b){a(d).removeClass("wp-menu-open")}}})},toggle:function(b){var c=b.slideToggle(150,function(){b.css("display","")}).parent().toggleClass("wp-menu-open").attr("id");if(c){a("li.wp-has-submenu","#adminmenu").each(function(f,g){if(c==g.id){var d=a(g).hasClass("wp-menu-open")?"o":"c";setUserSetting("m"+f,d)}})}return false},fold:function(b){if(b){a("body").removeClass("folded");a("#adminmenu li.wp-has-submenu").unbind()}else{a("body").addClass("folded");a("#adminmenu li.wp-has-submenu").hoverIntent({over:function(j){var d,c,g,k,i;d=a(this).find(".wp-submenu");c=a(this).offset().top+d.height()+1;g=a("#wpwrap").height();k=60+c-g;i=a(window).height()+a(window).scrollTop()-15;if(i<(c-k)){k=c-i}if(k>1){d.css({marginTop:"-"+k+"px"})}else{if(d.css("marginTop")){d.css({marginTop:""})}}d.addClass("sub-open")},out:function(){a(this).find(".wp-submenu").removeClass("sub-open").css({marginTop:""})},timeout:220,sensitivity:8,interval:100})}},favorites:function(){a("#favorite-inside").width(a("#favorite-actions").width()-4);a("#favorite-toggle, #favorite-inside").bind("mouseenter",function(){a("#favorite-inside").removeClass("slideUp").addClass("slideDown");setTimeout(function(){if(a("#favorite-inside").hasClass("slideDown")){a("#favorite-inside").slideDown(100);a("#favorite-first").addClass("slide-down")}},200)}).bind("mouseleave",function(){a("#favorite-inside").removeClass("slideDown").addClass("slideUp");setTimeout(function(){if(a("#favorite-inside").hasClass("slideUp")){a("#favorite-inside").slideUp(100,function(){a("#favorite-first").removeClass("slide-down")})}},300)})}};a(document).ready(function(){adminMenu.init()});columns={init:function(){var b=this;a(".hide-column-tog","#adv-settings").click(function(){var d=a(this),c=d.val();if(d.attr("checked")){b.checked(c)}else{b.unchecked(c)}columns.saveManageColumnsState()})},saveManageColumnsState:function(){var b=this.hidden();a.post(ajaxurl,{action:"hidden-columns",hidden:b,screenoptionnonce:a("#screenoptionnonce").val(),page:pagenow})},checked:function(b){a(".column-"+b).show()},unchecked:function(b){a(".column-"+b).hide()},hidden:function(){return a(".manage-column").filter(":hidden").map(function(){return this.id}).get().join(",")},useCheckboxesForHidden:function(){this.hidden=function(){return a(".hide-column-tog").not(":checked").map(function(){var b=this.id;return b.substring(b,b.length-5)}).get().join(",")}}};a(document).ready(function(){columns.init()});validateForm=function(b){return !a(b).find(".form-required").filter(function(){return a("input:visible",this).val()==""}).addClass("form-invalid").find("input:visible").change(function(){a(this).closest(".form-invalid").removeClass("form-invalid")}).size()}})(jQuery);showNotice={warn:function(){var a=commonL10n.warnDelete||"";if(confirm(a)){return true}return false},note:function(a){alert(a)}};jQuery(document).ready(function(d){var f=false,a,e,c,b;d("div.wrap h2:first").nextAll("div.updated, div.error").addClass("below-h2");d("div.updated, div.error").not(".below-h2, .inline").insertAfter(d("div.wrap h2:first"));

d("#outbody").click(function() {
       
		if(d("#spotlight-open-wrap").hasClass("spotlight-open-open"))
		{	      
                     d("#spotlight-open-wrap").slideToggle("fast",function(){
			
                        if(d(this).hasClass("spotlight-open-open"))
				{                                   
					d("#spotlight-open-link").css({backgroundImage:'url("img/spotlight.png")'});
					d(this).removeClass("spotlight-open-open")
					$("#inputString").blur();	
					$("#suggestions").hide();
					$("#autoSuggestionsList").empty();
				}
				else
				{
					d("#spotlight-open-link").css({backgroundImage:'url("img/spotlight-hover.png")'});
					d(this).addClass("spotlight-open-open")
					$("#inputString").focus();	
					$("#suggestions").show();				
					$("#autoSuggestionsList").empty();
                            }});
        	}
                
               if(d("#screen-options-wrap").hasClass("screen-options-open"))
		{
                     d("#screen-options-wrap").slideToggle("fast",function(){
			if(d(this).hasClass("screen-options-open"))
				{
                                        d("#screen-options-link-wrap a").css({backgroundImage:'url("img/settings.png")'});
					d(this).removeClass("screen-options-open")
					$("#inputString").blur();	
					$("#suggestions").hide();				
					$("#autoSuggestionsList").empty();
				}
				else
				{
                                        d("#screen-options-link-wrap a").css({backgroundImage:'url("img/settings-hover.png")'});
					d(this).addClass("screen-options-open")
					$("#inputString").blur();
					$("#suggestions").hide();
					$("#autoSuggestionsList").empty();
                            }});
                }   
});


d("#show-settings-link").click
	(function()
	 {
            
              if(d("#spotlight-open-wrap").hasClass("spotlight-open-open"))
		{
                     d("#spotlight-open-link").css({backgroundImage:'url("img/spotlight.png")'});
		     d(this).removeClass("spotlight-open-open")
	
                     d("#spotlight-open-wrap").slideToggle("fast",function(){
			if(d(this).hasClass("spotlight-open-open"))
				{                                   
					d("#spotlight-open-link").css({backgroundImage:'url("img/spotlight.png")'});
					d(this).removeClass("spotlight-open-open");
					$("#inputString").blur();      
					$("#suggestions").hide();
					$("#autoSuggestionsList").empty();
				}
				else
				{
					d("#spotlight-open-link").css({backgroundImage:'url("img/spotlight-hover.png")'});
					d(this).addClass("spotlight-open-open");
					$("#inputString").focus();
					$("#suggestions").show();
					$("#autoSuggestionsList").empty();
                            }});
        	}
        
              
		if(!d("#screen-options-wrap").hasClass("screen-options-open"))
		{
		}
		d("#screen-options-wrap").slideToggle("fast",function(){
			if(d(this).hasClass("screen-options-open"))
				{
                                        d("#screen-options-link-wrap a").css({backgroundImage:'url("img/settings.png")'});
					d(this).removeClass("screen-options-open");
					$("#inputString").blur();	
					$("#suggestions").hide();			
					$("#autoSuggestionsList").empty();
				}
				else
				{
                                        d("#screen-options-link-wrap a").css({backgroundImage:'url("img/settings-hover.png")'});
					d(this).addClass("screen-options-open");
					$("#inputString").blur();
					$("#suggestions").hide();
					$("#autoSuggestionsList").empty();
                            }});return false
        });   

d("#spotlight-open-link").click
	(function()
	 {
	      
              
              if(d("#screen-options-wrap").hasClass("screen-options-open"))
		{
              
		d("#screen-options-wrap").slideToggle("fast",function(){
			if(d(this).hasClass("screen-options-open"))
				{
                                        d("#screen-options-link-wrap a").css({backgroundImage:'url("img/settings.png")'});
					d(this).removeClass("screen-options-open");
					$("#inputString").blur();
					$("#suggestions").hide();
					$("#autoSuggestionsList").empty();
				}
				else
				{
                                        d("#screen-options-link-wrap a").css({backgroundImage:'url("img/settings-hover.png")'});
					d(this).addClass("screen-options-open");
					$("#inputString").blur();
					$("#suggestions").show();
					$("#autoSuggestionsList").empty();
                            }});
                }
              
	      if(!d("#spotlight-open-wrap").hasClass("spotlight-open-open"))
		{
		}
                
		d("#spotlight-open-wrap").slideToggle("fast",function(){
			if(d(this).hasClass("spotlight-open-open"))
				{                                   
					d("#spotlight-open-link").css({backgroundImage:'url("img/spotlight.png")'});
					d(this).removeClass("spotlight-open-open");
					$("#inputString").blur();
					$("#suggestions").hide();
					$("#autoSuggestionsList").empty();
				}
				else
				{
					d("#spotlight-open-link").css({backgroundImage:'url("img/spotlight-hover.png")'});
					d(this).addClass("spotlight-open-open");
					$("#inputString").focus();
					$("#suggestions").show();
					$("#autoSuggestionsList").empty();
                            }});return false
		
	});
	
	
    
d("#screen-options-link-wrap a").hover(

  function () {
    if(!d("#screen-options-wrap").hasClass("screen-options-open"))
	{  
	   d("#screen-options-link-wrap a").css({backgroundImage:'url("img/settings-hover.png")'});
	}
    },
    
  function () {
    if(!d("#screen-options-wrap").hasClass("screen-options-open"))
	{  
	    d("#screen-options-link-wrap a").css({backgroundImage:'url("img/settings.png")'});
	}
  }
  
);   
    
    
d("#spotlight-open-link-wrap a").hover(

  function () {
    if(!d("#spotlight-open-wrap").hasClass("spotlight-open-open"))
	{  
	    d("#spotlight-open-link-wrap a").css({backgroundImage:'url("img/spotlight-hover.png")'});
	}
    },
    
  function () {
    if(!d("#spotlight-open-wrap").hasClass("spotlight-open-open"))
	{  
	    d("#spotlight-open-link-wrap a").css({backgroundImage:'url("img/spotlight.png")'});
	}
  }
  
);  


d("#notification-open-link-wrap a").hover(

  function () {
    if(!d("#notification-open-wrap").hasClass("notification-open-open"))
        {
            d("#notification-open-link-wrap a").css({backgroundImage:'url("img/notification-hover.png")'});
        }
    },

  function () {
    if(!d("#notification-open-wrap").hasClass("notification-open-open"))
        {
            d("#notification-open-link-wrap a").css({backgroundImage:'url("img/notification.png")'});
        }
  }

);
 
    

d("tbody").children().children(".check-column").find(":checkbox").click(function(g){if("undefined"==g.shiftKey){return true}if(g.shiftKey){if(!f){return true}a=d(f).closest("form").find(":checkbox");e=a.index(f);c=a.index(this);b=d(this).attr("checked");if(0<e&&0<c&&e!=c){a.slice(e,c).attr("checked",function(){if(d(this).closest("tr").is(":visible")){return b?"checked":""}return""})}}f=this;return true});d("thead, tfoot").find(":checkbox").click(function(i){var j=d(this).attr("checked"),h="undefined"==typeof toggleWithKeyboard?false:toggleWithKeyboard,g=i.shiftKey||h;d(this).closest("table").children("tbody").filter(":visible").children().children(".check-column").find(":checkbox").attr("checked",function(){if(d(this).closest("tr").is(":hidden")){return""}if(g){return d(this).attr("checked")?"":"checked"}else{if(j){return"checked"}}return""});d(this).closest("table").children("thead,  tfoot").filter(":visible").children().children(".check-column").find(":checkbox").attr("checked",function(){if(g){return""}else{if(j){return"checked"}}return""})});d("#default-password-nag-no").click(function(){setUserSetting("default_password_nag","hide");d("div.default-password-nag").hide();return false});d("#newcontent").keydown(function(l){if(l.keyCode!=9){return true}var i=l.target,n=i.selectionStart,h=i.selectionEnd,m=i.value,g,k;try{this.lastKey=9}catch(j){}if(document.selection){i.focus();k=document.selection.createRange();k.text="\t"}else{if(n>=0){g=this.scrollTop;i.value=m.substring(0,n).concat("\t",m.substring(h));i.selectionStart=i.selectionEnd=n+1;this.scrollTop=g}}if(l.stopPropagation){l.stopPropagation()}if(l.preventDefault){l.preventDefault()}});d("#newcontent").blur(function(g){if(this.lastKey&&9==this.lastKey){this.focus()}})});
(function(d){d.each(["backgroundColor","borderBottomColor","borderLeftColor","borderRightColor","borderTopColor","color","outlineColor"],function(f,e){d.fx.step[e]=function(g){if(g.state==0){g.start=c(g.elem,e);g.end=b(g.end)}g.elem.style[e]="rgb("+[Math.max(Math.min(parseInt((g.pos*(g.end[0]-g.start[0]))+g.start[0]),255),0),Math.max(Math.min(parseInt((g.pos*(g.end[1]-g.start[1]))+g.start[1]),255),0),Math.max(Math.min(parseInt((g.pos*(g.end[2]-g.start[2]))+g.start[2]),255),0)].join(",")+")"}});function b(f){var e;if(f&&f.constructor==Array&&f.length==3){return f}if(e=/rgb\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*\)/.exec(f)){return[parseInt(e[1]),parseInt(e[2]),parseInt(e[3])]}if(e=/rgb\(\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*\)/.exec(f)){return[parseFloat(e[1])*2.55,parseFloat(e[2])*2.55,parseFloat(e[3])*2.55]}if(e=/#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})/.exec(f)){return[parseInt(e[1],16),parseInt(e[2],16),parseInt(e[3],16)]}if(e=/#([a-fA-F0-9])([a-fA-F0-9])([a-fA-F0-9])/.exec(f)){return[parseInt(e[1]+e[1],16),parseInt(e[2]+e[2],16),parseInt(e[3]+e[3],16)]}if(e=/rgba\(0, 0, 0, 0\)/.exec(f)){return a.transparent}return a[d.trim(f).toLowerCase()]}function c(g,e){var f;do{f=d.curCSS(g,e);if(f!=""&&f!="transparent"||d.nodeName(g,"body")){break}e="backgroundColor"}while(g=g.parentNode);return b(f)}var a={aqua:[0,255,255],azure:[240,255,255],beige:[245,245,220],black:[0,0,0],blue:[0,0,255],brown:[165,42,42],cyan:[0,255,255],darkblue:[0,0,139],darkcyan:[0,139,139],darkgrey:[169,169,169],darkgreen:[0,100,0],darkkhaki:[189,183,107],darkmagenta:[139,0,139],darkolivegreen:[85,107,47],darkorange:[255,140,0],darkorchid:[153,50,204],darkred:[139,0,0],darksalmon:[233,150,122],darkviolet:[148,0,211],fuchsia:[255,0,255],gold:[255,215,0],green:[0,128,0],indigo:[75,0,130],khaki:[240,230,140],lightblue:[173,216,230],lightcyan:[224,255,255],lightgreen:[144,238,144],lightgrey:[211,211,211],lightpink:[255,182,193],lightyellow:[255,255,224],lime:[0,255,0],magenta:[255,0,255],maroon:[128,0,0],navy:[0,0,128],olive:[128,128,0],orange:[255,165,0],pink:[255,192,203],purple:[128,0,128],violet:[128,0,128],red:[255,0,0],silver:[192,192,192],white:[255,255,255],yellow:[255,255,0],transparent:[255,255,255]}})(jQuery);
var wpAjax=jQuery.extend({unserialize:function(c){var d={},e,a,b,f;if(!c){return d}e=c.split("?");if(e[1]){c=e[1]}a=c.split("&");for(b in a){if(jQuery.isFunction(a.hasOwnProperty)&&!a.hasOwnProperty(b)){continue}f=a[b].split("=");d[f[0]]=f[1]}return d},parseAjaxResponse:function(a,f,g){var b={},c=jQuery("#"+f).html(""),d="";if(a&&typeof a=="object"&&a.getElementsByTagName("wp_ajax")){b.responses=[];b.errors=false;jQuery("response",a).each(function(){var h=jQuery(this),i=jQuery(this.firstChild),e;e={action:h.attr("action"),what:i.get(0).nodeName,id:i.attr("id"),oldId:i.attr("old_id"),position:i.attr("position")};e.data=jQuery("response_data",i).text();e.supplemental={};if(!jQuery("supplemental",i).children().each(function(){e.supplemental[this.nodeName]=jQuery(this).text()}).size()){e.supplemental=false}e.errors=[];if(!jQuery("wp_error",i).each(function(){var j=jQuery(this).attr("code"),m,l,k;m={code:j,message:this.firstChild.nodeValue,data:false};l=jQuery('wp_error_data[code="'+j+'"]',a);if(l){m.data=l.get()}k=jQuery("form-field",l).text();if(k){j=k}if(g){wpAjax.invalidateForm(jQuery("#"+g+' :input[name="'+j+'"]').parents(".form-field:first"))}d+="<p>"+m.message+"</p>";e.errors.push(m);b.errors=true}).size()){e.errors=false}b.responses.push(e)});if(d.length){c.html('<div class="error">'+d+"</div>")}return b}if(isNaN(a)){return !c.html('<div class="error"><p>'+a+"</p></div>")}a=parseInt(a,10);if(-1==a){return !c.html('<div class="error"><p>'+wpAjax.noPerm+"</p></div>")}else{if(0===a){return !c.html('<div class="error"><p>'+wpAjax.broken+"</p></div>")}}return true},invalidateForm:function(a){return jQuery(a).addClass("form-invalid").find("input:visible").change(function(){jQuery(this).closest(".form-invalid").removeClass("form-invalid")})},validateForm:function(a){a=jQuery(a);return !wpAjax.invalidateForm(a.find(".form-required").filter(function(){return jQuery("input:visible",this).val()==""})).size()}},wpAjax||{noPerm:"You do not have permission to do that.",broken:"An unidentified error has occurred."});jQuery(document).ready(function(a){a("form.validate").submit(function(){return wpAjax.validateForm(a(this))})});
(function(b){var a={add:"ajaxAdd",del:"ajaxDel",dim:"ajaxDim",process:"process",recolor:"recolor"},c;c={settings:{url:ajaxurl,type:"POST",response:"ajax-response",what:"",alt:"alternate",altOffset:0,addColor:null,delColor:null,dimAddColor:null,dimDelColor:null,confirm:null,addBefore:null,addAfter:null,delBefore:null,delAfter:null,dimBefore:null,dimAfter:null},nonce:function(g,f){var d=wpAjax.unserialize(g.attr("href"));return f.nonce||d._ajax_nonce||b("#"+f.element+" input[name=_ajax_nonce]").val()||d._wpnonce||b("#"+f.element+" input[name=_wpnonce]").val()||0},parseClass:function(h,f){var i=[],d;try{d=b(h).attr("class")||"";d=d.match(new RegExp(f+":[\\S]+"));if(d){i=d[0].split(":")}}catch(g){}return i},pre:function(i,g,d){var f,h;g=b.extend({},this.wpList.settings,{element:null,nonce:0,target:i.get(0)},g||{});if(b.isFunction(g.confirm)){if("add"!=d){f=b("#"+g.element).css("backgroundColor");b("#"+g.element).css("backgroundColor","#FF9966")}h=g.confirm.call(this,i,g,d,f);if("add"!=d){b("#"+g.element).css("backgroundColor",f)}if(!h){return false}}return g},ajaxAdd:function(j,f){j=b(j);f=f||{};var h=this,d=c.parseClass(j,"add"),k,g,i;f=c.pre.call(h,j,f,"add");f.element=d[2]||j.attr("id")||f.element||null;if(d[3]){f.addColor="#"+d[3]}else{f.addColor=f.addColor||"#FFFF33"}if(!f){return false}if(!j.is("[class^=add:"+h.id+":]")){return !c.add.call(h,j,f)}if(!f.element){return true}f.action="add-"+f.what;f.nonce=c.nonce(j,f);k=b("#"+f.element+" :input").not("[name=_ajax_nonce], [name=_wpnonce], [name=action]");g=wpAjax.validateForm("#"+f.element);if(!g){return false}f.data=b.param(b.extend({_ajax_nonce:f.nonce,action:f.action},wpAjax.unserialize(d[4]||"")));i=b.isFunction(k.fieldSerialize)?k.fieldSerialize():k.serialize();if(i){f.data+="&"+i}if(b.isFunction(f.addBefore)){f=f.addBefore(f);if(!f){return true}}if(!f.data.match(/_ajax_nonce=[a-f0-9]+/)){return true}f.success=function(l){var e=wpAjax.parseAjaxResponse(l,f.response,f.element),m;if(!e||e.errors){return false}if(true===e){return true}jQuery.each(e.responses,function(){c.add.call(h,this.data,b.extend({},f,{pos:this.position||0,id:this.id||0,oldId:this.oldId||null}))});if(b.isFunction(f.addAfter)){m=this.complete;this.complete=function(n,o){var p=b.extend({xml:n,status:o,parsed:e},f);f.addAfter(l,p);if(b.isFunction(m)){m(n,o)}}}h.wpList.recolor();b(h).trigger("wpListAddEnd",[f,h.wpList]);c.clear.call(h,"#"+f.element)};b.ajax(f);return false},ajaxDel:function(i,g){i=b(i);g=g||{};var h=this,d=c.parseClass(i,"delete"),f;g=c.pre.call(h,i,g,"delete");g.element=d[2]||g.element||null;if(d[3]){g.delColor="#"+d[3]}else{g.delColor=g.delColor||"#faa"}if(!g||!g.element){return false}g.action="delete-"+g.what;g.nonce=c.nonce(i,g);g.data=b.extend({action:g.action,id:g.element.split("-").pop(),_ajax_nonce:g.nonce},wpAjax.unserialize(d[4]||""));if(b.isFunction(g.delBefore)){g=g.delBefore(g,h);if(!g){return true}}if(!g.data._ajax_nonce){return true}f=b("#"+g.element);if("none"!=g.delColor){f.css("backgroundColor",g.delColor).fadeOut(350,function(){h.wpList.recolor();b(h).trigger("wpListDelEnd",[g,h.wpList])})}else{h.wpList.recolor();b(h).trigger("wpListDelEnd",[g,h.wpList])}g.success=function(j){var e=wpAjax.parseAjaxResponse(j,g.response,g.element),k;if(!e||e.errors){f.stop().stop().css("backgroundColor","#faa").show().queue(function(){h.wpList.recolor();b(this).dequeue()});return false}if(b.isFunction(g.delAfter)){k=this.complete;this.complete=function(l,m){f.queue(function(){var n=b.extend({xml:l,status:m,parsed:e},g);g.delAfter(j,n);if(b.isFunction(k)){k(l,m)}}).dequeue()}}};b.ajax(g);return false},ajaxDim:function(k,h){if(b(k).parent().css("display")=="none"){return false}k=b(k);h=h||{};var j=this,d=c.parseClass(k,"dim"),g,l,f,i;h=c.pre.call(j,k,h,"dim");h.element=d[2]||h.element||null;h.dimClass=d[3]||h.dimClass||null;if(d[4]){h.dimAddColor="#"+d[4]}else{h.dimAddColor=h.dimAddColor||"#FFFF33"}if(d[5]){h.dimDelColor="#"+d[5]}else{h.dimDelColor=h.dimDelColor||"#FF3333"}if(!h||!h.element||!h.dimClass){return true}h.action="dim-"+h.what;h.nonce=c.nonce(k,h);h.data=b.extend({action:h.action,id:h.element.split("-").pop(),dimClass:h.dimClass,_ajax_nonce:h.nonce},wpAjax.unserialize(d[6]||""));if(b.isFunction(h.dimBefore)){h=h.dimBefore(h);if(!h){return true}}g=b("#"+h.element);l=g.toggleClass(h.dimClass).is("."+h.dimClass);f=c.getColor(g);g.toggleClass(h.dimClass);i=l?h.dimAddColor:h.dimDelColor;if("none"!=i){g.animate({backgroundColor:i},"fast").queue(function(){g.toggleClass(h.dimClass);b(this).dequeue()}).animate({backgroundColor:f},{complete:function(){b(this).css("backgroundColor","");b(j).trigger("wpListDimEnd",[h,j.wpList])}})}else{b(j).trigger("wpListDimEnd",[h,j.wpList])}if(!h.data._ajax_nonce){return true}h.success=function(m){var e=wpAjax.parseAjaxResponse(m,h.response,h.element),n;if(!e||e.errors){g.stop().stop().css("backgroundColor","#FF3333")[l?"removeClass":"addClass"](h.dimClass).show().queue(function(){j.wpList.recolor();b(this).dequeue()});return false}if(b.isFunction(h.dimAfter)){n=this.complete;this.complete=function(o,p){g.queue(function(){var q=b.extend({xml:o,status:p,parsed:e},h);h.dimAfter(m,q);if(b.isFunction(n)){n(o,p)}}).dequeue()}}};b.ajax(h);return false},getColor:function(e){if(e.constructor==Object){e=e.get(0)}var f=e,d,g=new RegExp("rgba\\(\\s*0,\\s*0,\\s*0,\\s*0\\s*\\)","i");do{d=jQuery.curCSS(f,"backgroundColor");if(d!=""&&d!="transparent"&&!d.match(g)||jQuery.nodeName(f,"body")){break}}while(f=f.parentNode);return d||"#ffffff"},add:function(k,g){k=b(k);var i=b(this),d=false,j={pos:0,id:0,oldId:null},l,h,f;if("string"==typeof g){g={what:g}}g=b.extend(j,this.wpList.settings,g);if(!k.size()||!g.what){return false}if(g.oldId){d=b("#"+g.what+"-"+g.oldId)}if(g.id&&(g.id!=g.oldId||!d||!d.size())){b("#"+g.what+"-"+g.id).remove()}if(d&&d.size()){d.before(k);d.remove()}else{if(isNaN(g.pos)){l="after";if("-"==g.pos.substr(0,1)){g.pos=g.pos.substr(1);l="before"}h=i.find("#"+g.pos);if(1===h.size()){h[l](k)}else{i.append(k)}}else{if(g.pos<0){i.prepend(k)}else{i.append(k)}}}if(g.alt){if((i.children(":visible").index(k[0])+g.altOffset)%2){k.removeClass(g.alt)}else{k.addClass(g.alt)}}if("none"!=g.addColor){f=c.getColor(k);k.css("backgroundColor",g.addColor).animate({backgroundColor:f},{complete:function(){b(this).css("backgroundColor","")}})}i.each(function(){this.wpList.process(k)});return k},clear:function(h){var g=this,f,d;h=b(h);if(g.wpList&&h.parents("#"+g.id).size()){return}h.find(":input").each(function(){if(b(this).parents(".form-no-clear").size()){return}f=this.type.toLowerCase();d=this.tagName.toLowerCase();if("text"==f||"password"==f||"textarea"==d){this.value=""}else{if("checkbox"==f||"radio"==f){this.checked=false}else{if("select"==d){this.selectedIndex=null}}}})},process:function(d){var e=this;b("[class^=add:"+e.id+":]",d||null).filter("form").submit(function(){return e.wpList.add(this)}).end().not("form").click(function(){return e.wpList.add(this)});b("[class^=delete:"+e.id+":]",d||null).click(function(){return e.wpList.del(this)});b("[class^=dim:"+e.id+":]",d||null).click(function(){return e.wpList.dim(this)})},recolor:function(){var f=this,e,d;if(!f.wpList.settings.alt){return}e=b(".list-item:visible",f);if(!e.size()){e=b(f).children(":visible")}d=[":even",":odd"];if(f.wpList.settings.altOffset%2){d.reverse()}e.filter(d[0]).addClass(f.wpList.settings.alt).end().filter(d[1]).removeClass(f.wpList.settings.alt)},init:function(){var d=this;d.wpList.process=function(e){d.each(function(){this.wpList.process(e)})};d.wpList.recolor=function(){d.each(function(){this.wpList.recolor()})}}};b.fn.wpList=function(d){this.each(function(){var e=this;this.wpList={settings:b.extend({},c.settings,{what:c.parseClass(this,"list")[1]||""},d)};b.each(a,function(g,h){e.wpList[g]=function(i,f){return c[h].call(e,i,f)}})});c.init.call(this);this.wpList.process();return this}})(jQuery);
/*
 * jQuery UI 1.7.3
 *
 * Copyright (c) 2009 AUTHORS.txt (http://jqueryui.com/about)
 * Dual licensed under the MIT (MIT-LICENSE.txt)
 * and GPL (GPL-LICENSE.txt) licenses.
 *
 * http://docs.jquery.com/UI
 */
jQuery.ui||(function(c){var i=c.fn.remove,d=c.browser.mozilla&&(parseFloat(c.browser.version)<1.9);c.ui={version:"1.7.3",plugin:{add:function(k,l,n){var m=c.ui[k].prototype;for(var j in n){m.plugins[j]=m.plugins[j]||[];m.plugins[j].push([l,n[j]])}},call:function(j,l,k){var n=j.plugins[l];if(!n||!j.element[0].parentNode){return}for(var m=0;m<n.length;m++){if(j.options[n[m][0]]){n[m][1].apply(j.element,k)}}}},contains:function(k,j){return document.compareDocumentPosition?k.compareDocumentPosition(j)&16:k!==j&&k.contains(j)},hasScroll:function(m,k){if(c(m).css("overflow")=="hidden"){return false}var j=(k&&k=="left")?"scrollLeft":"scrollTop",l=false;if(m[j]>0){return true}m[j]=1;l=(m[j]>0);m[j]=0;return l},isOverAxis:function(k,j,l){return(k>j)&&(k<(j+l))},isOver:function(o,k,n,m,j,l){return c.ui.isOverAxis(o,n,j)&&c.ui.isOverAxis(k,m,l)},keyCode:{BACKSPACE:8,CAPS_LOCK:20,COMMA:188,CONTROL:17,DELETE:46,DOWN:40,END:35,ENTER:13,ESCAPE:27,HOME:36,INSERT:45,LEFT:37,NUMPAD_ADD:107,NUMPAD_DECIMAL:110,NUMPAD_DIVIDE:111,NUMPAD_ENTER:108,NUMPAD_MULTIPLY:106,NUMPAD_SUBTRACT:109,PAGE_DOWN:34,PAGE_UP:33,PERIOD:190,RIGHT:39,SHIFT:16,SPACE:32,TAB:9,UP:38}};if(d){var f=c.attr,e=c.fn.removeAttr,h="http://www.w3.org/2005/07/aaa",a=/^aria-/,b=/^wairole:/;c.attr=function(k,j,l){var m=l!==undefined;return(j=="role"?(m?f.call(this,k,j,"wairole:"+l):(f.apply(this,arguments)||"").replace(b,"")):(a.test(j)?(m?k.setAttributeNS(h,j.replace(a,"aaa:"),l):f.call(this,k,j.replace(a,"aaa:"))):f.apply(this,arguments)))};c.fn.removeAttr=function(j){return(a.test(j)?this.each(function(){this.removeAttributeNS(h,j.replace(a,""))}):e.call(this,j))}}c.fn.extend({remove:function(j,k){return this.each(function(){if(!k){if(!j||c.filter(j,[this]).length){c("*",this).add(this).each(function(){c(this).triggerHandler("remove")})}}return i.call(c(this),j,k)})},enableSelection:function(){return this.attr("unselectable","off").css("MozUserSelect","").unbind("selectstart.ui")},disableSelection:function(){return this.attr("unselectable","on").css("MozUserSelect","none").bind("selectstart.ui",function(){return false})},scrollParent:function(){var j;if((c.browser.msie&&(/(static|relative)/).test(this.css("position")))||(/absolute/).test(this.css("position"))){j=this.parents().filter(function(){return(/(relative|absolute|fixed)/).test(c.curCSS(this,"position",1))&&(/(auto|scroll)/).test(c.curCSS(this,"overflow",1)+c.curCSS(this,"overflow-y",1)+c.curCSS(this,"overflow-x",1))}).eq(0)}else{j=this.parents().filter(function(){return(/(auto|scroll)/).test(c.curCSS(this,"overflow",1)+c.curCSS(this,"overflow-y",1)+c.curCSS(this,"overflow-x",1))}).eq(0)}return(/fixed/).test(this.css("position"))||!j.length?c(document):j}});c.extend(c.expr[":"],{data:function(l,k,j){return !!c.data(l,j[3])},focusable:function(k){var l=k.nodeName.toLowerCase(),j=c.attr(k,"tabindex");return(/input|select|textarea|button|object/.test(l)?!k.disabled:"a"==l||"area"==l?k.href||!isNaN(j):!isNaN(j))&&!c(k)["area"==l?"parents":"closest"](":hidden").length},tabbable:function(k){var j=c.attr(k,"tabindex");return(isNaN(j)||j>=0)&&c(k).is(":focusable")}});function g(m,n,o,l){function k(q){var p=c[m][n][q]||[];return(typeof p=="string"?p.split(/,?\s+/):p)}var j=k("getter");if(l.length==1&&typeof l[0]=="string"){j=j.concat(k("getterSetter"))}return(c.inArray(o,j)!=-1)}c.widget=function(k,j){var l=k.split(".")[0];k=k.split(".")[1];c.fn[k]=function(p){var n=(typeof p=="string"),o=Array.prototype.slice.call(arguments,1);if(n&&p.substring(0,1)=="_"){return this}if(n&&g(l,k,p,o)){var m=c.data(this[0],k);return(m?m[p].apply(m,o):undefined)}return this.each(function(){var q=c.data(this,k);(!q&&!n&&c.data(this,k,new c[l][k](this,p))._init());(q&&n&&c.isFunction(q[p])&&q[p].apply(q,o))})};c[l]=c[l]||{};c[l][k]=function(o,n){var m=this;this.namespace=l;this.widgetName=k;this.widgetEventPrefix=c[l][k].eventPrefix||k;this.widgetBaseClass=l+"-"+k;this.options=c.extend({},c.widget.defaults,c[l][k].defaults,c.metadata&&c.metadata.get(o)[k],n);this.element=c(o).bind("setData."+k,function(q,p,r){if(q.target==o){return m._setData(p,r)}}).bind("getData."+k,function(q,p){if(q.target==o){return m._getData(p)}}).bind("remove",function(){return m.destroy()})};c[l][k].prototype=c.extend({},c.widget.prototype,j);c[l][k].getterSetter="option"};c.widget.prototype={_init:function(){},destroy:function(){this.element.removeData(this.widgetName).removeClass(this.widgetBaseClass+"-disabled "+this.namespace+"-state-disabled").removeAttr("aria-disabled")},option:function(l,m){var k=l,j=this;if(typeof l=="string"){if(m===undefined){return this._getData(l)}k={};k[l]=m}c.each(k,function(n,o){j._setData(n,o)})},_getData:function(j){return this.options[j]},_setData:function(j,k){this.options[j]=k;if(j=="disabled"){this.element[k?"addClass":"removeClass"](this.widgetBaseClass+"-disabled "+this.namespace+"-state-disabled").attr("aria-disabled",k)}},enable:function(){this._setData("disabled",false)},disable:function(){this._setData("disabled",true)},_trigger:function(l,m,n){var p=this.options[l],j=(l==this.widgetEventPrefix?l:this.widgetEventPrefix+l);m=c.Event(m);m.type=j;if(m.originalEvent){for(var k=c.event.props.length,o;k;){o=c.event.props[--k];m[o]=m.originalEvent[o]}}this.element.trigger(m,n);return !(c.isFunction(p)&&p.call(this.element[0],m,n)===false||m.isDefaultPrevented())}};c.widget.defaults={disabled:false};c.ui.mouse={_mouseInit:function(){var j=this;this.element.bind("mousedown."+this.widgetName,function(k){return j._mouseDown(k)}).bind("click."+this.widgetName,function(k){if(j._preventClickEvent){j._preventClickEvent=false;k.stopImmediatePropagation();return false}});if(c.browser.msie){this._mouseUnselectable=this.element.attr("unselectable");this.element.attr("unselectable","on")}this.started=false},_mouseDestroy:function(){this.element.unbind("."+this.widgetName);(c.browser.msie&&this.element.attr("unselectable",this._mouseUnselectable))},_mouseDown:function(l){l.originalEvent=l.originalEvent||{};if(l.originalEvent.mouseHandled){return}(this._mouseStarted&&this._mouseUp(l));this._mouseDownEvent=l;var k=this,m=(l.which==1),j=(typeof this.options.cancel=="string"?c(l.target).parents().add(l.target).filter(this.options.cancel).length:false);if(!m||j||!this._mouseCapture(l)){return true}this.mouseDelayMet=!this.options.delay;if(!this.mouseDelayMet){this._mouseDelayTimer=setTimeout(function(){k.mouseDelayMet=true},this.options.delay)}if(this._mouseDistanceMet(l)&&this._mouseDelayMet(l)){this._mouseStarted=(this._mouseStart(l)!==false);if(!this._mouseStarted){l.preventDefault();return true}}this._mouseMoveDelegate=function(n){return k._mouseMove(n)};this._mouseUpDelegate=function(n){return k._mouseUp(n)};c(document).bind("mousemove."+this.widgetName,this._mouseMoveDelegate).bind("mouseup."+this.widgetName,this._mouseUpDelegate);(c.browser.safari||l.preventDefault());l.originalEvent.mouseHandled=true;return true},_mouseMove:function(j){if(c.browser.msie&&!j.button){return this._mouseUp(j)}if(this._mouseStarted){this._mouseDrag(j);return j.preventDefault()}if(this._mouseDistanceMet(j)&&this._mouseDelayMet(j)){this._mouseStarted=(this._mouseStart(this._mouseDownEvent,j)!==false);(this._mouseStarted?this._mouseDrag(j):this._mouseUp(j))}return !this._mouseStarted},_mouseUp:function(j){c(document).unbind("mousemove."+this.widgetName,this._mouseMoveDelegate).unbind("mouseup."+this.widgetName,this._mouseUpDelegate);if(this._mouseStarted){this._mouseStarted=false;this._preventClickEvent=(j.target==this._mouseDownEvent.target);this._mouseStop(j)}return false},_mouseDistanceMet:function(j){return(Math.max(Math.abs(this._mouseDownEvent.pageX-j.pageX),Math.abs(this._mouseDownEvent.pageY-j.pageY))>=this.options.distance)},_mouseDelayMet:function(j){return this.mouseDelayMet},_mouseStart:function(j){},_mouseDrag:function(j){},_mouseStop:function(j){},_mouseCapture:function(j){return true}};c.ui.mouse.defaults={cancel:null,distance:1,delay:0}})(jQuery);

/*
 * jQuery UI Resizable 1.7.3
 *
 * Copyright (c) 2009 AUTHORS.txt (http://jqueryui.com/about)
 * Dual licensed under the MIT (MIT-LICENSE.txt)
 * and GPL (GPL-LICENSE.txt) licenses.
 *
 * http://docs.jquery.com/UI/Resizables
 *
 * Depends:
 *	ui.core.js
 */
(function(c){c.widget("ui.resizable",c.extend({},c.ui.mouse,{_init:function(){var e=this,j=this.options;this.element.addClass("ui-resizable");c.extend(this,{_aspectRatio:!!(j.aspectRatio),aspectRatio:j.aspectRatio,originalElement:this.element,_proportionallyResizeElements:[],_helper:j.helper||j.ghost||j.animate?j.helper||"ui-resizable-helper":null});if(this.element[0].nodeName.match(/canvas|textarea|input|select|button|img/i)){if(/relative/.test(this.element.css("position"))&&c.browser.opera){this.element.css({position:"relative",top:"auto",left:"auto"})}this.element.wrap(c('<div class="ui-wrapper" style="overflow: hidden;"></div>').css({position:this.element.css("position"),width:this.element.outerWidth(),height:this.element.outerHeight(),top:this.element.css("top"),left:this.element.css("left")}));this.element=this.element.parent().data("resizable",this.element.data("resizable"));this.elementIsWrapper=true;this.element.css({marginLeft:this.originalElement.css("marginLeft"),marginTop:this.originalElement.css("marginTop"),marginRight:this.originalElement.css("marginRight"),marginBottom:this.originalElement.css("marginBottom")});this.originalElement.css({marginLeft:0,marginTop:0,marginRight:0,marginBottom:0});this.originalResizeStyle=this.originalElement.css("resize");this.originalElement.css("resize","none");this._proportionallyResizeElements.push(this.originalElement.css({position:"static",zoom:1,display:"block"}));this.originalElement.css({margin:this.originalElement.css("margin")});this._proportionallyResize()}this.handles=j.handles||(!c(".ui-resizable-handle",this.element).length?"e,s,se":{n:".ui-resizable-n",e:".ui-resizable-e",s:".ui-resizable-s",w:".ui-resizable-w",se:".ui-resizable-se",sw:".ui-resizable-sw",ne:".ui-resizable-ne",nw:".ui-resizable-nw"});if(this.handles.constructor==String){if(this.handles=="all"){this.handles="n,e,s,w,se,sw,ne,nw"}var k=this.handles.split(",");this.handles={};for(var f=0;f<k.length;f++){var h=c.trim(k[f]),d="ui-resizable-"+h;var g=c('<div class="ui-resizable-handle '+d+'"></div>');if(/sw|se|ne|nw/.test(h)){g.css({zIndex:++j.zIndex})}if("se"==h){g.addClass("ui-icon ui-icon-gripsmall-diagonal-se")}this.handles[h]=".ui-resizable-"+h;this.element.append(g)}}this._renderAxis=function(p){p=p||this.element;for(var m in this.handles){if(this.handles[m].constructor==String){this.handles[m]=c(this.handles[m],this.element).show()}if(this.elementIsWrapper&&this.originalElement[0].nodeName.match(/textarea|input|select|button/i)){var n=c(this.handles[m],this.element),o=0;o=/sw|ne|nw|se|n|s/.test(m)?n.outerHeight():n.outerWidth();var l=["padding",/ne|nw|n/.test(m)?"Top":/se|sw|s/.test(m)?"Bottom":/^e$/.test(m)?"Right":"Left"].join("");p.css(l,o);this._proportionallyResize()}if(!c(this.handles[m]).length){continue}}};this._renderAxis(this.element);this._handles=c(".ui-resizable-handle",this.element).disableSelection();this._handles.mouseover(function(){if(!e.resizing){if(this.className){var i=this.className.match(/ui-resizable-(se|sw|ne|nw|n|e|s|w)/i)}e.axis=i&&i[1]?i[1]:"se"}});if(j.autoHide){this._handles.hide();c(this.element).addClass("ui-resizable-autohide").hover(function(){c(this).removeClass("ui-resizable-autohide");e._handles.show()},function(){if(!e.resizing){c(this).addClass("ui-resizable-autohide");e._handles.hide()}})}this._mouseInit()},destroy:function(){this._mouseDestroy();var d=function(f){c(f).removeClass("ui-resizable ui-resizable-disabled ui-resizable-resizing").removeData("resizable").unbind(".resizable").find(".ui-resizable-handle").remove()};if(this.elementIsWrapper){d(this.element);var e=this.element;e.parent().append(this.originalElement.css({position:e.css("position"),width:e.outerWidth(),height:e.outerHeight(),top:e.css("top"),left:e.css("left")})).end().remove()}this.originalElement.css("resize",this.originalResizeStyle);d(this.originalElement)},_mouseCapture:function(e){var f=false;for(var d in this.handles){if(c(this.handles[d])[0]==e.target){f=true}}return this.options.disabled||!!f},_mouseStart:function(f){var i=this.options,e=this.element.position(),d=this.element;this.resizing=true;this.documentScroll={top:c(document).scrollTop(),left:c(document).scrollLeft()};if(d.is(".ui-draggable")||(/absolute/).test(d.css("position"))){d.css({position:"absolute",top:e.top,left:e.left})}if(c.browser.opera&&(/relative/).test(d.css("position"))){d.css({position:"relative",top:"auto",left:"auto"})}this._renderProxy();var j=b(this.helper.css("left")),g=b(this.helper.css("top"));if(i.containment){j+=c(i.containment).scrollLeft()||0;g+=c(i.containment).scrollTop()||0}this.offset=this.helper.offset();this.position={left:j,top:g};this.size=this._helper?{width:d.outerWidth(),height:d.outerHeight()}:{width:d.width(),height:d.height()};this.originalSize=this._helper?{width:d.outerWidth(),height:d.outerHeight()}:{width:d.width(),height:d.height()};this.originalPosition={left:j,top:g};this.sizeDiff={width:d.outerWidth()-d.width(),height:d.outerHeight()-d.height()};this.originalMousePosition={left:f.pageX,top:f.pageY};this.aspectRatio=(typeof i.aspectRatio=="number")?i.aspectRatio:((this.originalSize.width/this.originalSize.height)||1);var h=c(".ui-resizable-"+this.axis).css("cursor");c("body").css("cursor",h=="auto"?this.axis+"-resize":h);d.addClass("ui-resizable-resizing");this._propagate("start",f);return true},_mouseDrag:function(d){var g=this.helper,f=this.options,l={},p=this,i=this.originalMousePosition,m=this.axis;var q=(d.pageX-i.left)||0,n=(d.pageY-i.top)||0;var h=this._change[m];if(!h){return false}var k=h.apply(this,[d,q,n]),j=c.browser.msie&&c.browser.version<7,e=this.sizeDiff;if(this._aspectRatio||d.shiftKey){k=this._updateRatio(k,d)}k=this._respectSize(k,d);this._propagate("resize",d);g.css({top:this.position.top+"px",left:this.position.left+"px",width:this.size.width+"px",height:this.size.height+"px"});if(!this._helper&&this._proportionallyResizeElements.length){this._proportionallyResize()}this._updateCache(k);this._trigger("resize",d,this.ui());return false},_mouseStop:function(g){this.resizing=false;var h=this.options,l=this;if(this._helper){var f=this._proportionallyResizeElements,d=f.length&&(/textarea/i).test(f[0].nodeName),e=d&&c.ui.hasScroll(f[0],"left")?0:l.sizeDiff.height,j=d?0:l.sizeDiff.width;var m={width:(l.size.width-j),height:(l.size.height-e)},i=(parseInt(l.element.css("left"),10)+(l.position.left-l.originalPosition.left))||null,k=(parseInt(l.element.css("top"),10)+(l.position.top-l.originalPosition.top))||null;if(!h.animate){this.element.css(c.extend(m,{top:k,left:i}))}l.helper.height(l.size.height);l.helper.width(l.size.width);if(this._helper&&!h.animate){this._proportionallyResize()}}c("body").css("cursor","auto");this.element.removeClass("ui-resizable-resizing");this._propagate("stop",g);if(this._helper){this.helper.remove()}return false},_updateCache:function(d){var e=this.options;this.offset=this.helper.offset();if(a(d.left)){this.position.left=d.left}if(a(d.top)){this.position.top=d.top}if(a(d.height)){this.size.height=d.height}if(a(d.width)){this.size.width=d.width}},_updateRatio:function(g,f){var h=this.options,i=this.position,e=this.size,d=this.axis;if(g.height){g.width=(e.height*this.aspectRatio)}else{if(g.width){g.height=(e.width/this.aspectRatio)}}if(d=="sw"){g.left=i.left+(e.width-g.width);g.top=null}if(d=="nw"){g.top=i.top+(e.height-g.height);g.left=i.left+(e.width-g.width)}return g},_respectSize:function(k,f){var i=this.helper,h=this.options,q=this._aspectRatio||f.shiftKey,p=this.axis,s=a(k.width)&&h.maxWidth&&(h.maxWidth<k.width),l=a(k.height)&&h.maxHeight&&(h.maxHeight<k.height),g=a(k.width)&&h.minWidth&&(h.minWidth>k.width),r=a(k.height)&&h.minHeight&&(h.minHeight>k.height);if(g){k.width=h.minWidth}if(r){k.height=h.minHeight}if(s){k.width=h.maxWidth}if(l){k.height=h.maxHeight}var e=this.originalPosition.left+this.originalSize.width,n=this.position.top+this.size.height;var j=/sw|nw|w/.test(p),d=/nw|ne|n/.test(p);if(g&&j){k.left=e-h.minWidth}if(s&&j){k.left=e-h.maxWidth}if(r&&d){k.top=n-h.minHeight}if(l&&d){k.top=n-h.maxHeight}var m=!k.width&&!k.height;if(m&&!k.left&&k.top){k.top=null}else{if(m&&!k.top&&k.left){k.left=null}}return k},_proportionallyResize:function(){var j=this.options;if(!this._proportionallyResizeElements.length){return}var f=this.helper||this.element;for(var e=0;e<this._proportionallyResizeElements.length;e++){var g=this._proportionallyResizeElements[e];if(!this.borderDif){var d=[g.css("borderTopWidth"),g.css("borderRightWidth"),g.css("borderBottomWidth"),g.css("borderLeftWidth")],h=[g.css("paddingTop"),g.css("paddingRight"),g.css("paddingBottom"),g.css("paddingLeft")];this.borderDif=c.map(d,function(k,m){var l=parseInt(k,10)||0,n=parseInt(h[m],10)||0;return l+n})}if(c.browser.msie&&!(!(c(f).is(":hidden")||c(f).parents(":hidden").length))){continue}g.css({height:(f.height()-this.borderDif[0]-this.borderDif[2])||0,width:(f.width()-this.borderDif[1]-this.borderDif[3])||0})}},_renderProxy:function(){var e=this.element,h=this.options;this.elementOffset=e.offset();if(this._helper){this.helper=this.helper||c('<div style="overflow:hidden;"></div>');var d=c.browser.msie&&c.browser.version<7,f=(d?1:0),g=(d?2:-1);this.helper.addClass(this._helper).css({width:this.element.outerWidth()+g,height:this.element.outerHeight()+g,position:"absolute",left:this.elementOffset.left-f+"px",top:this.elementOffset.top-f+"px",zIndex:++h.zIndex});this.helper.appendTo("body").disableSelection()}else{this.helper=this.element}},_change:{e:function(f,e,d){return{width:this.originalSize.width+e}},w:function(g,e,d){var i=this.options,f=this.originalSize,h=this.originalPosition;return{left:h.left+e,width:f.width-e}},n:function(g,e,d){var i=this.options,f=this.originalSize,h=this.originalPosition;return{top:h.top+d,height:f.height-d}},s:function(f,e,d){return{height:this.originalSize.height+d}},se:function(f,e,d){return c.extend(this._change.s.apply(this,arguments),this._change.e.apply(this,[f,e,d]))},sw:function(f,e,d){return c.extend(this._change.s.apply(this,arguments),this._change.w.apply(this,[f,e,d]))},ne:function(f,e,d){return c.extend(this._change.n.apply(this,arguments),this._change.e.apply(this,[f,e,d]))},nw:function(f,e,d){return c.extend(this._change.n.apply(this,arguments),this._change.w.apply(this,[f,e,d]))}},_propagate:function(e,d){c.ui.plugin.call(this,e,[d,this.ui()]);(e!="resize"&&this._trigger(e,d,this.ui()))},plugins:{},ui:function(){return{originalElement:this.originalElement,element:this.element,helper:this.helper,position:this.position,size:this.size,originalSize:this.originalSize,originalPosition:this.originalPosition}}}));c.extend(c.ui.resizable,{version:"1.7.3",eventPrefix:"resize",defaults:{alsoResize:false,animate:false,animateDuration:"slow",animateEasing:"swing",aspectRatio:false,autoHide:false,cancel:":input,option",containment:false,delay:0,distance:1,ghost:false,grid:false,handles:"e,s,se",helper:false,maxHeight:null,maxWidth:null,minHeight:10,minWidth:10,zIndex:1000}});c.ui.plugin.add("resizable","alsoResize",{start:function(e,f){var d=c(this).data("resizable"),g=d.options;_store=function(h){c(h).each(function(){c(this).data("resizable-alsoresize",{width:parseInt(c(this).width(),10),height:parseInt(c(this).height(),10),left:parseInt(c(this).css("left"),10),top:parseInt(c(this).css("top"),10)})})};if(typeof(g.alsoResize)=="object"&&!g.alsoResize.parentNode){if(g.alsoResize.length){g.alsoResize=g.alsoResize[0];_store(g.alsoResize)}else{c.each(g.alsoResize,function(h,i){_store(h)})}}else{_store(g.alsoResize)}},resize:function(f,h){var e=c(this).data("resizable"),i=e.options,g=e.originalSize,k=e.originalPosition;var j={height:(e.size.height-g.height)||0,width:(e.size.width-g.width)||0,top:(e.position.top-k.top)||0,left:(e.position.left-k.left)||0},d=function(l,m){c(l).each(function(){var p=c(this),q=c(this).data("resizable-alsoresize"),o={},n=m&&m.length?m:["width","height","top","left"];c.each(n||["width","height","top","left"],function(r,t){var s=(q[t]||0)+(j[t]||0);if(s&&s>=0){o[t]=s||null}});if(/relative/.test(p.css("position"))&&c.browser.opera){e._revertToRelativePosition=true;p.css({position:"absolute",top:"auto",left:"auto"})}p.css(o)})};if(typeof(i.alsoResize)=="object"&&!i.alsoResize.nodeType){c.each(i.alsoResize,function(l,m){d(l,m)})}else{d(i.alsoResize)}},stop:function(e,f){var d=c(this).data("resizable");if(d._revertToRelativePosition&&c.browser.opera){d._revertToRelativePosition=false;el.css({position:"relative"})}c(this).removeData("resizable-alsoresize-start")}});c.ui.plugin.add("resizable","animate",{stop:function(h,m){var n=c(this).data("resizable"),i=n.options;var g=n._proportionallyResizeElements,d=g.length&&(/textarea/i).test(g[0].nodeName),e=d&&c.ui.hasScroll(g[0],"left")?0:n.sizeDiff.height,k=d?0:n.sizeDiff.width;var f={width:(n.size.width-k),height:(n.size.height-e)},j=(parseInt(n.element.css("left"),10)+(n.position.left-n.originalPosition.left))||null,l=(parseInt(n.element.css("top"),10)+(n.position.top-n.originalPosition.top))||null;n.element.animate(c.extend(f,l&&j?{top:l,left:j}:{}),{duration:i.animateDuration,easing:i.animateEasing,step:function(){var o={width:parseInt(n.element.css("width"),10),height:parseInt(n.element.css("height"),10),top:parseInt(n.element.css("top"),10),left:parseInt(n.element.css("left"),10)};if(g&&g.length){c(g[0]).css({width:o.width,height:o.height})}n._updateCache(o);n._propagate("resize",h)}})}});c.ui.plugin.add("resizable","containment",{start:function(e,q){var s=c(this).data("resizable"),i=s.options,k=s.element;var f=i.containment,j=(f instanceof c)?f.get(0):(/parent/.test(f))?k.parent().get(0):f;if(!j){return}s.containerElement=c(j);if(/document/.test(f)||f==document){s.containerOffset={left:0,top:0};s.containerPosition={left:0,top:0};s.parentData={element:c(document),left:0,top:0,width:c(document).width(),height:c(document).height()||document.body.parentNode.scrollHeight}}else{var m=c(j),h=[];c(["Top","Right","Left","Bottom"]).each(function(p,o){h[p]=b(m.css("padding"+o))});s.containerOffset=m.offset();s.containerPosition=m.position();s.containerSize={height:(m.innerHeight()-h[3]),width:(m.innerWidth()-h[1])};var n=s.containerOffset,d=s.containerSize.height,l=s.containerSize.width,g=(c.ui.hasScroll(j,"left")?j.scrollWidth:l),r=(c.ui.hasScroll(j)?j.scrollHeight:d);s.parentData={element:j,left:n.left,top:n.top,width:g,height:r}}},resize:function(f,p){var s=c(this).data("resizable"),h=s.options,e=s.containerSize,n=s.containerOffset,l=s.size,m=s.position,q=s._aspectRatio||f.shiftKey,d={top:0,left:0},g=s.containerElement;if(g[0]!=document&&(/static/).test(g.css("position"))){d=n}if(m.left<(s._helper?n.left:0)){s.size.width=s.size.width+(s._helper?(s.position.left-n.left):(s.position.left-d.left));if(q){s.size.height=s.size.width/h.aspectRatio}s.position.left=h.helper?n.left:0}if(m.top<(s._helper?n.top:0)){s.size.height=s.size.height+(s._helper?(s.position.top-n.top):s.position.top);if(q){s.size.width=s.size.height*h.aspectRatio}s.position.top=s._helper?n.top:0}s.offset.left=s.parentData.left+s.position.left;s.offset.top=s.parentData.top+s.position.top;var k=Math.abs((s._helper?s.offset.left-d.left:(s.offset.left-d.left))+s.sizeDiff.width),r=Math.abs((s._helper?s.offset.top-d.top:(s.offset.top-n.top))+s.sizeDiff.height);var j=s.containerElement.get(0)==s.element.parent().get(0),i=/relative|absolute/.test(s.containerElement.css("position"));if(j&&i){k-=s.parentData.left}if(k+s.size.width>=s.parentData.width){s.size.width=s.parentData.width-k;if(q){s.size.height=s.size.width/s.aspectRatio}}if(r+s.size.height>=s.parentData.height){s.size.height=s.parentData.height-r;if(q){s.size.width=s.size.height*s.aspectRatio}}},stop:function(e,m){var p=c(this).data("resizable"),f=p.options,k=p.position,l=p.containerOffset,d=p.containerPosition,g=p.containerElement;var i=c(p.helper),q=i.offset(),n=i.outerWidth()-p.sizeDiff.width,j=i.outerHeight()-p.sizeDiff.height;if(p._helper&&!f.animate&&(/relative/).test(g.css("position"))){c(this).css({left:q.left-d.left-l.left,width:n,height:j})}if(p._helper&&!f.animate&&(/static/).test(g.css("position"))){c(this).css({left:q.left-d.left-l.left,width:n,height:j})}}});c.ui.plugin.add("resizable","ghost",{start:function(f,g){var d=c(this).data("resizable"),h=d.options,e=d.size;d.ghost=d.originalElement.clone();d.ghost.css({opacity:0.25,display:"block",position:"relative",height:e.height,width:e.width,margin:0,left:0,top:0}).addClass("ui-resizable-ghost").addClass(typeof h.ghost=="string"?h.ghost:"");d.ghost.appendTo(d.helper)},resize:function(e,f){var d=c(this).data("resizable"),g=d.options;if(d.ghost){d.ghost.css({position:"relative",height:d.size.height,width:d.size.width})}},stop:function(e,f){var d=c(this).data("resizable"),g=d.options;if(d.ghost&&d.helper){d.helper.get(0).removeChild(d.ghost.get(0))}}});c.ui.plugin.add("resizable","grid",{resize:function(d,l){var n=c(this).data("resizable"),g=n.options,j=n.size,h=n.originalSize,i=n.originalPosition,m=n.axis,k=g._aspectRatio||d.shiftKey;g.grid=typeof g.grid=="number"?[g.grid,g.grid]:g.grid;var f=Math.round((j.width-h.width)/(g.grid[0]||1))*(g.grid[0]||1),e=Math.round((j.height-h.height)/(g.grid[1]||1))*(g.grid[1]||1);if(/^(se|s|e)$/.test(m)){n.size.width=h.width+f;n.size.height=h.height+e}else{if(/^(ne)$/.test(m)){n.size.width=h.width+f;n.size.height=h.height+e;n.position.top=i.top-e}else{if(/^(sw)$/.test(m)){n.size.width=h.width+f;n.size.height=h.height+e;n.position.left=i.left-f}else{n.size.width=h.width+f;n.size.height=h.height+e;n.position.top=i.top-e;n.position.left=i.left-f}}}}});var b=function(d){return parseInt(d,10)||0};var a=function(d){return !isNaN(parseInt(d,10))}})(jQuery);

var theList,theExtraList,toggleWithKeyboard=false;(function(a){setCommentsList=function(){var c,e,h,l=0,g,i,d,k;c=a('.tablenav input[name="_total"]',"#comments-form");e=a('.tablenav input[name="_per_page"]',"#comments-form");h=a('.tablenav input[name="_page"]',"#comments-form");g=function(n,m){var o=a("#"+m.element);if(o.is(".unapproved")){o.find("div.comment_status").html("0")}else{o.find("div.comment_status").html("1")}a("span.pending-count").each(function(){var p=a(this),r,q;r=p.html().replace(/[^0-9]+/g,"");r=parseInt(r,10);if(isNaN(r)){return}q=a("#"+m.element).is("."+m.dimClass)?1:-1;r=r+q;if(r<0){r=0}p.closest("#awaiting-mod")[0==r?"addClass":"removeClass"]("count-0");f(p,r);j()})};i=function(q,u){var w=a(q.target).attr("className"),m,o,p,t,v,s,r=false;q.data._total=c.val()||0;q.data._per_page=e.val()||0;q.data._page=h.val()||0;q.data._url=document.location.href;if(w.indexOf(":trash=1")!=-1){r="trash"}else{if(w.indexOf(":spam=1")!=-1){r="spam"}}if(r){m=w.replace(/.*?comment-([0-9]+).*/,"$1");o=a("#comment-"+m);note=a("#"+r+"-undo-holder").html();o.find(".check-column :checkbox").attr("checked","");if(o.siblings("#replyrow").length&&commentReply.cid==m){commentReply.close()}if(o.is("tr")){p=o.children(":visible").length;s=a(".author strong",o).text();t=a('<tr id="undo-'+m+'" class="undo un'+r+'" style="display:none;"><td colspan="'+p+'">'+note+"</td></tr>")}else{s=a(".comment-author",o).text();t=a('<div id="undo-'+m+'" style="display:none;" class="undo un'+r+'">'+note+"</div>")}o.before(t);a("strong","#undo-"+m).text(s+" ");v=a(".undo a","#undo-"+m);v.attr("href","comment.php?action=un"+r+"comment&c="+m+"&_wpnonce="+q.data._ajax_nonce);v.attr("className","delete:the-comment-list:comment-"+m+"::un"+r+"=1 vim-z vim-destructive");a(".avatar",o).clone().prependTo("#undo-"+m+" ."+r+"-undo-inside");v.click(function(){u.wpList.del(this);a("#undo-"+m).css({backgroundColor:"#ceb"}).fadeOut(350,function(){a(this).remove();a("#comment-"+m).css("backgroundColor","").fadeIn(300,function(){a(this).show()})});return false})}return q};d=function(m,n,o){if(n<l){return}if(o){l=n}c.val(m.toString());a("span.total-type-count").each(function(){f(a(this),m)})};function j(s){var r=a("#dashboard_right_now"),o,q,p,m;s=s||0;if(isNaN(s)||!r.length){return}o=a("span.total-count",r);q=a("span.approved-count",r);p=b(o);p=p+s;m=p-b(a("span.pending-count",r))-b(a("span.spam-count",r));f(o,p);f(q,m)}function b(m){var o=parseInt(m.html().replace(/[^0-9]+/g,""),10);if(isNaN(o)){return 0}return o}function f(o,p){var m="";if(isNaN(p)){return}p=p<1?"0":p.toString();if(p.length>3){while(p.length>3){m=thousandsSeparator+p.substr(p.length-3)+m;p=p.substr(0,p.length-3)}p=p+m}o.html(p)}k=function(m,o){var t,p,q,v=a(o.target).parent().is("span.untrash"),n=a(o.target).parent().is("span.unspam"),u,s;function w(r){if(a(o.target).parent().is("span."+r)){return 1}else{if(a("#"+o.element).is("."+r)){return -1}}return 0}u=w("spam");s=w("trash");if(v){s=-1}if(n){u=-1}a("span.pending-count").each(function(){var r=a(this),y=b(r),x=a("#"+o.element).is(".unapproved");if(a(o.target).parent().is("span.unapprove")||((v||n)&&x)){y=y+1}else{if(x){y=y-1}}if(y<0){y=0}r.closest("#awaiting-mod")[0==y?"addClass":"removeClass"]("count-0");f(r,y);j()});a("span.spam-count").each(function(){var r=a(this),x=b(r)+u;f(r,x)});a("span.trash-count").each(function(){var r=a(this),x=b(r)+s;f(r,x)});if(a("#dashboard_right_now").length){q=s?-1*s:0;j(q)}else{t=c.val()?parseInt(c.val(),10):0;t=t-u-s;if(t<0){t=0}if(("object"==typeof m)&&l<o.parsed.responses[0].supplemental.time){p=o.parsed.responses[0].supplemental.pageLinks||"";if(a.trim(p)){a(".tablenav-pages").find(".page-numbers").remove().end().append(a(p))}else{a(".tablenav-pages").find(".page-numbers").remove()}d(t,o.parsed.responses[0].supplemental.time,true)}else{d(t,m,false)}}if(theExtraList.size()==0||theExtraList.children().size()==0||v){return}theList.get(0).wpList.add(theExtraList.children(":eq(0)").remove().clone());a("#get-extra-comments").submit()};theExtraList=a("#the-extra-comment-list").wpList({alt:"",delColor:"none",addColor:"none"});theList=a("#the-comment-list").wpList({alt:"",delBefore:i,dimAfter:g,delAfter:k,addColor:"none"}).bind("wpListDelEnd",function(n,m){var o=m.element.replace(/[^0-9]+/g,"");if(m.target.className.indexOf(":trash=1")!=-1||m.target.className.indexOf(":spam=1")!=-1){a("#undo-"+o).fadeIn(300,function(){a(this).show()})}})};commentReply={cid:"",act:"",init:function(){var b=a("#replyrow");a("a.cancel",b).click(function(){return commentReply.revert()});a("a.save",b).click(function(){return commentReply.send()});a("input#author, input#author-email, input#author-url",b).keypress(function(c){if(c.which==13){commentReply.send();c.preventDefault();return false}});a("#the-comment-list .column-comment > p").dblclick(function(){commentReply.toggle(a(this).parent())});a("#doaction, #doaction2, #post-query-submit").click(function(c){if(a("#the-comment-list #replyrow").length>0){commentReply.close()}});this.comments_listing=a('#comments-form > input[name="comment_status"]').val()||""},addEvents:function(b){b.each(function(){a(this).find(".column-comment > p").dblclick(function(){commentReply.toggle(a(this).parent())})})},toggle:function(b){if(a(b).css("display")!="none"){a(b).find("a.vim-q").click()}},revert:function(){if(a("#the-comment-list #replyrow").length<1){return false}a("#replyrow").fadeOut("fast",function(){commentReply.close()});return false},close:function(){var b;if(this.cid){b=a("#comment-"+this.cid);if(this.act=="edit-comment"){b.fadeIn(300,function(){b.show()}).css("backgroundColor","")}a("#replyrow").hide();a("#com-reply").append(a("#replyrow"));a("#replycontent").val("");a("input","#edithead").val("");a(".error","#replysubmit").html("").hide();a(".waiting","#replysubmit").hide();if(a.browser.msie){a("#replycontainer, #replycontent").css("height","120px")}else{a("#replycontainer").resizable("destroy").css("height","120px")}this.cid=""}},open:function(b,d,k){var l=this,e,f,i,g,j=a("#comment-"+b);l.close();l.cid=b;a("td","#replyrow").attr("colspan",a("table.widefat thead th:visible").length);e=a("#replyrow");f=a("#inline-"+b);i=l.act=(k=="edit")?"edit-comment":"replyto-comment";a("#action",e).val(i);a("#comment_post_ID",e).val(d);a("#comment_ID",e).val(b);if(k=="edit"){a("#author",e).val(a("div.author",f).text());a("#author-email",e).val(a("div.author-email",f).text());a("#author-url",e).val(a("div.author-url",f).text());a("#status",e).val(a("div.comment_status",f).text());a("#replycontent",e).val(a("textarea.comment",f).val());a("#edithead, #savebtn",e).show();a("#replyhead, #replybtn",e).hide();g=j.height();if(g>220){if(a.browser.msie){a("#replycontainer, #replycontent",e).height(g-105)}else{a("#replycontainer",e).height(g-105)}}j.after(e).fadeOut("fast",function(){a("#replyrow").fadeIn(300,function(){a(this).show()})})}else{a("#edithead, #savebtn",e).hide();a("#replyhead, #replybtn",e).show();j.after(e);a("#replyrow").fadeIn(300,function(){a(this).show()})}if(!a.browser.msie){a("#replycontainer").resizable({handles:"s",axis:"y",minHeight:80,stop:function(){a("#replycontainer").width("auto")}})}setTimeout(function(){var n,h,o,c,m;n=a("#replyrow").offset().top;h=n+a("#replyrow").height();o=window.pageYOffset||document.documentElement.scrollTop;c=document.documentElement.clientHeight||self.innerHeight||0;m=o+c;if(m-20<h){window.scroll(0,h-c+35)}else{if(n-20<o){window.scroll(0,n-35)}}a("#replycontent").focus().keyup(function(p){if(p.which==27){commentReply.revert()}})},600);return false},send:function(){var b={};a("#replysubmit .waiting").show();a("#replyrow input").each(function(){b[a(this).attr("name")]=a(this).val()});b.content=a("#replycontent").val();b.id=b.comment_post_ID;b.comments_listing=this.comments_listing;a.ajax({type:"POST",url:ajaxurl,data:b,success:function(c){commentReply.show(c)},error:function(c){commentReply.error(c)}});return false},show:function(b){var e,g,f,d;if(typeof(b)=="string"){this.error({responseText:b});return false}e=wpAjax.parseAjaxResponse(b);if(e.errors){this.error({responseText:wpAjax.broken});return false}e=e.responses[0];g=e.data;f="#comment-"+e.id;if("edit-comment"==this.act){a(f).remove()}a(g).hide();a("#replyrow").after(g);this.revert();this.addEvents(a(f));d=a(f).hasClass("unapproved")?"#ffffe0":"#fff";a(f).animate({backgroundColor:"#CCEEBB"},600).animate({backgroundColor:d},600);a.fn.wpList.process(a(f))},error:function(b){var c=b.statusText;a("#replysubmit .waiting").hide();if(b.responseText){c=b.responseText.replace(/<.[^<>]*?>/g,"")}if(c){a("#replysubmit .error").html(c).show()}}};a(document).ready(function(){var e,b,c,d;setCommentsList();commentReply.init();a("span.delete a.delete").click(function(){return false});if(typeof QTags!="undefined"){ed_reply=new QTags("ed_reply","replycontent","replycontainer","more")}if(typeof a.table_hotkeys!="undefined"){e=function(f){return function(){var h,g;h="next"==f?"first":"last";g=a("."+f+".page-numbers");if(g.length){window.location=g[0].href.replace(/\&hotkeys_highlight_(first|last)=1/g,"")+"&hotkeys_highlight_"+h+"=1"}}};b=function(g,f){window.location=a("span.edit a",f).attr("href")};c=function(){toggleWithKeyboard=true;a("input:checkbox","#cb").click().attr("checked","");toggleWithKeyboard=false};d=function(f){return function(){var g=a('select[name="action"]');a("option[value="+f+"]",g).attr("selected","selected");a("#comments-form").submit()}};a.table_hotkeys(a("table.widefat"),["a","u","s","d","r","q","z",["e",b],["shift+x",c],["shift+a",d("approve")],["shift+s",d("markspam")],["shift+d",d("delete")],["shift+t",d("trash")],["shift+z",d("untrash")],["shift+u",d("unapprove")]],{highlight_first:adminCommentsL10n.hotkeys_highlight_first,highlight_last:adminCommentsL10n.hotkeys_highlight_last,prev_page_link_cb:e("prev"),next_page_link_cb:e("next")})}})})(jQuery);
/*
 * jQuery UI Sortable 1.7.3
 *
 * Copyright (c) 2009 AUTHORS.txt (http://jqueryui.com/about)
 * Dual licensed under the MIT (MIT-LICENSE.txt)
 * and GPL (GPL-LICENSE.txt) licenses.
 *
 * http://docs.jquery.com/UI/Sortables
 *
 * Depends:
 *	ui.core.js
 */
(function(a){a.widget("ui.sortable",a.extend({},a.ui.mouse,{_init:function(){var b=this.options;this.containerCache={};this.element.addClass("ui-sortable");this.refresh();this.floating=this.items.length?(/left|right/).test(this.items[0].item.css("float")):false;this.offset=this.element.offset();this._mouseInit()},destroy:function(){this.element.removeClass("ui-sortable ui-sortable-disabled").removeData("sortable").unbind(".sortable");this._mouseDestroy();for(var b=this.items.length-1;b>=0;b--){this.items[b].item.removeData("sortable-item")}},_mouseCapture:function(e,f){if(this.reverting){return false}if(this.options.disabled||this.options.type=="static"){return false}this._refreshItems(e);var d=null,c=this,b=a(e.target).parents().each(function(){if(a.data(this,"sortable-item")==c){d=a(this);return false}});if(a.data(e.target,"sortable-item")==c){d=a(e.target)}if(!d){return false}if(this.options.handle&&!f){var g=false;a(this.options.handle,d).find("*").andSelf().each(function(){if(this==e.target){g=true}});if(!g){return false}}this.currentItem=d;this._removeCurrentsFromItems();return true},_mouseStart:function(e,f,b){var g=this.options,c=this;this.currentContainer=this;this.refreshPositions();this.helper=this._createHelper(e);this._cacheHelperProportions();this._cacheMargins();this.scrollParent=this.helper.scrollParent();this.offset=this.currentItem.offset();this.offset={top:this.offset.top-this.margins.top,left:this.offset.left-this.margins.left};this.helper.css("position","absolute");this.cssPosition=this.helper.css("position");a.extend(this.offset,{click:{left:e.pageX-this.offset.left,top:e.pageY-this.offset.top},parent:this._getParentOffset(),relative:this._getRelativeOffset()});this.originalPosition=this._generatePosition(e);this.originalPageX=e.pageX;this.originalPageY=e.pageY;if(g.cursorAt){this._adjustOffsetFromHelper(g.cursorAt)}this.domPosition={prev:this.currentItem.prev()[0],parent:this.currentItem.parent()[0]};if(this.helper[0]!=this.currentItem[0]){this.currentItem.hide()}this._createPlaceholder();if(g.containment){this._setContainment()}if(g.cursor){if(a("body").css("cursor")){this._storedCursor=a("body").css("cursor")}a("body").css("cursor",g.cursor)}if(g.opacity){if(this.helper.css("opacity")){this._storedOpacity=this.helper.css("opacity")}this.helper.css("opacity",g.opacity)}if(g.zIndex){if(this.helper.css("zIndex")){this._storedZIndex=this.helper.css("zIndex")}this.helper.css("zIndex",g.zIndex)}if(this.scrollParent[0]!=document&&this.scrollParent[0].tagName!="HTML"){this.overflowOffset=this.scrollParent.offset()}this._trigger("start",e,this._uiHash());if(!this._preserveHelperProportions){this._cacheHelperProportions()}if(!b){for(var d=this.containers.length-1;d>=0;d--){this.containers[d]._trigger("activate",e,c._uiHash(this))}}if(a.ui.ddmanager){a.ui.ddmanager.current=this}if(a.ui.ddmanager&&!g.dropBehaviour){a.ui.ddmanager.prepareOffsets(this,e)}this.dragging=true;this.helper.addClass("ui-sortable-helper");this._mouseDrag(e);return true},_mouseDrag:function(f){this.position=this._generatePosition(f);this.positionAbs=this._convertPositionTo("absolute");if(!this.lastPositionAbs){this.lastPositionAbs=this.positionAbs}if(this.options.scroll){var g=this.options,b=false;if(this.scrollParent[0]!=document&&this.scrollParent[0].tagName!="HTML"){if((this.overflowOffset.top+this.scrollParent[0].offsetHeight)-f.pageY<g.scrollSensitivity){this.scrollParent[0].scrollTop=b=this.scrollParent[0].scrollTop+g.scrollSpeed}else{if(f.pageY-this.overflowOffset.top<g.scrollSensitivity){this.scrollParent[0].scrollTop=b=this.scrollParent[0].scrollTop-g.scrollSpeed}}if((this.overflowOffset.left+this.scrollParent[0].offsetWidth)-f.pageX<g.scrollSensitivity){this.scrollParent[0].scrollLeft=b=this.scrollParent[0].scrollLeft+g.scrollSpeed}else{if(f.pageX-this.overflowOffset.left<g.scrollSensitivity){this.scrollParent[0].scrollLeft=b=this.scrollParent[0].scrollLeft-g.scrollSpeed}}}else{if(f.pageY-a(document).scrollTop()<g.scrollSensitivity){b=a(document).scrollTop(a(document).scrollTop()-g.scrollSpeed)}else{if(a(window).height()-(f.pageY-a(document).scrollTop())<g.scrollSensitivity){b=a(document).scrollTop(a(document).scrollTop()+g.scrollSpeed)}}if(f.pageX-a(document).scrollLeft()<g.scrollSensitivity){b=a(document).scrollLeft(a(document).scrollLeft()-g.scrollSpeed)}else{if(a(window).width()-(f.pageX-a(document).scrollLeft())<g.scrollSensitivity){b=a(document).scrollLeft(a(document).scrollLeft()+g.scrollSpeed)}}}if(b!==false&&a.ui.ddmanager&&!g.dropBehaviour){a.ui.ddmanager.prepareOffsets(this,f)}}this.positionAbs=this._convertPositionTo("absolute");if(!this.options.axis||this.options.axis!="y"){this.helper[0].style.left=this.position.left+"px"}if(!this.options.axis||this.options.axis!="x"){this.helper[0].style.top=this.position.top+"px"}for(var d=this.items.length-1;d>=0;d--){var e=this.items[d],c=e.item[0],h=this._intersectsWithPointer(e);if(!h){continue}if(c!=this.currentItem[0]&&this.placeholder[h==1?"next":"prev"]()[0]!=c&&!a.ui.contains(this.placeholder[0],c)&&(this.options.type=="semi-dynamic"?!a.ui.contains(this.element[0],c):true)){this.direction=h==1?"down":"up";if(this.options.tolerance=="pointer"||this._intersectsWithSides(e)){this._rearrange(f,e)}else{break}this._trigger("change",f,this._uiHash());break}}this._contactContainers(f);if(a.ui.ddmanager){a.ui.ddmanager.drag(this,f)}this._trigger("sort",f,this._uiHash());this.lastPositionAbs=this.positionAbs;return false},_mouseStop:function(c,d){if(!c){return}if(a.ui.ddmanager&&!this.options.dropBehaviour){a.ui.ddmanager.drop(this,c)}if(this.options.revert){var b=this;var e=b.placeholder.offset();b.reverting=true;a(this.helper).animate({left:e.left-this.offset.parent.left-b.margins.left+(this.offsetParent[0]==document.body?0:this.offsetParent[0].scrollLeft),top:e.top-this.offset.parent.top-b.margins.top+(this.offsetParent[0]==document.body?0:this.offsetParent[0].scrollTop)},parseInt(this.options.revert,10)||500,function(){b._clear(c)})}else{this._clear(c,d)}return false},cancel:function(){var b=this;if(this.dragging){this._mouseUp();if(this.options.helper=="original"){this.currentItem.css(this._storedCSS).removeClass("ui-sortable-helper")}else{this.currentItem.show()}for(var c=this.containers.length-1;c>=0;c--){this.containers[c]._trigger("deactivate",null,b._uiHash(this));if(this.containers[c].containerCache.over){this.containers[c]._trigger("out",null,b._uiHash(this));this.containers[c].containerCache.over=0}}}if(this.placeholder[0].parentNode){this.placeholder[0].parentNode.removeChild(this.placeholder[0])}if(this.options.helper!="original"&&this.helper&&this.helper[0].parentNode){this.helper.remove()}a.extend(this,{helper:null,dragging:false,reverting:false,_noFinalSort:null});if(this.domPosition.prev){a(this.domPosition.prev).after(this.currentItem)}else{a(this.domPosition.parent).prepend(this.currentItem)}return true},serialize:function(d){var b=this._getItemsAsjQuery(d&&d.connected);var c=[];d=d||{};a(b).each(function(){var e=(a(d.item||this).attr(d.attribute||"id")||"").match(d.expression||(/(.+)[-=_](.+)/));if(e){c.push((d.key||e[1]+"[]")+"="+(d.key&&d.expression?e[1]:e[2]))}});return c.join("&")},toArray:function(d){var b=this._getItemsAsjQuery(d&&d.connected);var c=[];d=d||{};b.each(function(){c.push(a(d.item||this).attr(d.attribute||"id")||"")});return c},_intersectsWith:function(m){var e=this.positionAbs.left,d=e+this.helperProportions.width,k=this.positionAbs.top,j=k+this.helperProportions.height;var f=m.left,c=f+m.width,n=m.top,i=n+m.height;var o=this.offset.click.top,h=this.offset.click.left;var g=(k+o)>n&&(k+o)<i&&(e+h)>f&&(e+h)<c;if(this.options.tolerance=="pointer"||this.options.forcePointerForContainers||(this.options.tolerance!="pointer"&&this.helperProportions[this.floating?"width":"height"]>m[this.floating?"width":"height"])){return g}else{return(f<e+(this.helperProportions.width/2)&&d-(this.helperProportions.width/2)<c&&n<k+(this.helperProportions.height/2)&&j-(this.helperProportions.height/2)<i)}},_intersectsWithPointer:function(d){var e=a.ui.isOverAxis(this.positionAbs.top+this.offset.click.top,d.top,d.height),c=a.ui.isOverAxis(this.positionAbs.left+this.offset.click.left,d.left,d.width),g=e&&c,b=this._getDragVerticalDirection(),f=this._getDragHorizontalDirection();if(!g){return false}return this.floating?(((f&&f=="right")||b=="down")?2:1):(b&&(b=="down"?2:1))},_intersectsWithSides:function(e){var c=a.ui.isOverAxis(this.positionAbs.top+this.offset.click.top,e.top+(e.height/2),e.height),d=a.ui.isOverAxis(this.positionAbs.left+this.offset.click.left,e.left+(e.width/2),e.width),b=this._getDragVerticalDirection(),f=this._getDragHorizontalDirection();if(this.floating&&f){return((f=="right"&&d)||(f=="left"&&!d))}else{return b&&((b=="down"&&c)||(b=="up"&&!c))}},_getDragVerticalDirection:function(){var b=this.positionAbs.top-this.lastPositionAbs.top;return b!=0&&(b>0?"down":"up")},_getDragHorizontalDirection:function(){var b=this.positionAbs.left-this.lastPositionAbs.left;return b!=0&&(b>0?"right":"left")},refresh:function(b){this._refreshItems(b);this.refreshPositions()},_connectWith:function(){var b=this.options;return b.connectWith.constructor==String?[b.connectWith]:b.connectWith},_getItemsAsjQuery:function(b){var l=this;var g=[];var e=[];var h=this._connectWith();if(h&&b){for(var d=h.length-1;d>=0;d--){var k=a(h[d]);for(var c=k.length-1;c>=0;c--){var f=a.data(k[c],"sortable");if(f&&f!=this&&!f.options.disabled){e.push([a.isFunction(f.options.items)?f.options.items.call(f.element):a(f.options.items,f.element).not(".ui-sortable-helper"),f])}}}}e.push([a.isFunction(this.options.items)?this.options.items.call(this.element,null,{options:this.options,item:this.currentItem}):a(this.options.items,this.element).not(".ui-sortable-helper"),this]);for(var d=e.length-1;d>=0;d--){e[d][0].each(function(){g.push(this)})}return a(g)},_removeCurrentsFromItems:function(){var d=this.currentItem.find(":data(sortable-item)");for(var c=0;c<this.items.length;c++){for(var b=0;b<d.length;b++){if(d[b]==this.items[c].item[0]){this.items.splice(c,1)}}}},_refreshItems:function(b){this.items=[];this.containers=[this];var h=this.items;var p=this;var f=[[a.isFunction(this.options.items)?this.options.items.call(this.element[0],b,{item:this.currentItem}):a(this.options.items,this.element),this]];var l=this._connectWith();if(l){for(var e=l.length-1;e>=0;e--){var m=a(l[e]);for(var d=m.length-1;d>=0;d--){var g=a.data(m[d],"sortable");if(g&&g!=this&&!g.options.disabled){f.push([a.isFunction(g.options.items)?g.options.items.call(g.element[0],b,{item:this.currentItem}):a(g.options.items,g.element),g]);this.containers.push(g)}}}}for(var e=f.length-1;e>=0;e--){var k=f[e][1];var c=f[e][0];for(var d=0,n=c.length;d<n;d++){var o=a(c[d]);o.data("sortable-item",k);h.push({item:o,instance:k,width:0,height:0,left:0,top:0})}}},refreshPositions:function(b){if(this.offsetParent&&this.helper){this.offset.parent=this._getParentOffset()}for(var d=this.items.length-1;d>=0;d--){var e=this.items[d];if(e.instance!=this.currentContainer&&this.currentContainer&&e.item[0]!=this.currentItem[0]){continue}var c=this.options.toleranceElement?a(this.options.toleranceElement,e.item):e.item;if(!b){e.width=c.outerWidth();e.height=c.outerHeight()}var f=c.offset();e.left=f.left;e.top=f.top}if(this.options.custom&&this.options.custom.refreshContainers){this.options.custom.refreshContainers.call(this)}else{for(var d=this.containers.length-1;d>=0;d--){var f=this.containers[d].element.offset();this.containers[d].containerCache.left=f.left;this.containers[d].containerCache.top=f.top;this.containers[d].containerCache.width=this.containers[d].element.outerWidth();this.containers[d].containerCache.height=this.containers[d].element.outerHeight()}}},_createPlaceholder:function(d){var b=d||this,e=b.options;if(!e.placeholder||e.placeholder.constructor==String){var c=e.placeholder;e.placeholder={element:function(){var f=a(document.createElement(b.currentItem[0].nodeName)).addClass(c||b.currentItem[0].className+" ui-sortable-placeholder").removeClass("ui-sortable-helper")[0];if(!c){f.style.visibility="hidden"}return f},update:function(f,g){if(c&&!e.forcePlaceholderSize){return}if(!g.height()){g.height(b.currentItem.innerHeight()-parseInt(b.currentItem.css("paddingTop")||0,10)-parseInt(b.currentItem.css("paddingBottom")||0,10))}if(!g.width()){g.width(b.currentItem.innerWidth()-parseInt(b.currentItem.css("paddingLeft")||0,10)-parseInt(b.currentItem.css("paddingRight")||0,10))}}}}b.placeholder=a(e.placeholder.element.call(b.element,b.currentItem));b.currentItem.after(b.placeholder);e.placeholder.update(b,b.placeholder)},_contactContainers:function(d){for(var c=this.containers.length-1;c>=0;c--){if(this._intersectsWith(this.containers[c].containerCache)){if(!this.containers[c].containerCache.over){if(this.currentContainer!=this.containers[c]){var h=10000;var g=null;var e=this.positionAbs[this.containers[c].floating?"left":"top"];for(var b=this.items.length-1;b>=0;b--){if(!a.ui.contains(this.containers[c].element[0],this.items[b].item[0])){continue}var f=this.items[b][this.containers[c].floating?"left":"top"];if(Math.abs(f-e)<h){h=Math.abs(f-e);g=this.items[b]}}if(!g&&!this.options.dropOnEmpty){continue}this.currentContainer=this.containers[c];g?this._rearrange(d,g,null,true):this._rearrange(d,null,this.containers[c].element,true);this._trigger("change",d,this._uiHash());this.containers[c]._trigger("change",d,this._uiHash(this));this.options.placeholder.update(this.currentContainer,this.placeholder)}this.containers[c]._trigger("over",d,this._uiHash(this));this.containers[c].containerCache.over=1}}else{if(this.containers[c].containerCache.over){this.containers[c]._trigger("out",d,this._uiHash(this));this.containers[c].containerCache.over=0}}}},_createHelper:function(c){var d=this.options;var b=a.isFunction(d.helper)?a(d.helper.apply(this.element[0],[c,this.currentItem])):(d.helper=="clone"?this.currentItem.clone():this.currentItem);if(!b.parents("body").length){a(d.appendTo!="parent"?d.appendTo:this.currentItem[0].parentNode)[0].appendChild(b[0])}if(b[0]==this.currentItem[0]){this._storedCSS={width:this.currentItem[0].style.width,height:this.currentItem[0].style.height,position:this.currentItem.css("position"),top:this.currentItem.css("top"),left:this.currentItem.css("left")}}if(b[0].style.width==""||d.forceHelperSize){b.width(this.currentItem.width())}if(b[0].style.height==""||d.forceHelperSize){b.height(this.currentItem.height())}return b},_adjustOffsetFromHelper:function(b){if(b.left!=undefined){this.offset.click.left=b.left+this.margins.left}if(b.right!=undefined){this.offset.click.left=this.helperProportions.width-b.right+this.margins.left}if(b.top!=undefined){this.offset.click.top=b.top+this.margins.top}if(b.bottom!=undefined){this.offset.click.top=this.helperProportions.height-b.bottom+this.margins.top}},_getParentOffset:function(){this.offsetParent=this.helper.offsetParent();var b=this.offsetParent.offset();if(this.cssPosition=="absolute"&&this.scrollParent[0]!=document&&a.ui.contains(this.scrollParent[0],this.offsetParent[0])){b.left+=this.scrollParent.scrollLeft();b.top+=this.scrollParent.scrollTop()}if((this.offsetParent[0]==document.body)||(this.offsetParent[0].tagName&&this.offsetParent[0].tagName.toLowerCase()=="html"&&a.browser.msie)){b={top:0,left:0}}return{top:b.top+(parseInt(this.offsetParent.css("borderTopWidth"),10)||0),left:b.left+(parseInt(this.offsetParent.css("borderLeftWidth"),10)||0)}},_getRelativeOffset:function(){if(this.cssPosition=="relative"){var b=this.currentItem.position();return{top:b.top-(parseInt(this.helper.css("top"),10)||0)+this.scrollParent.scrollTop(),left:b.left-(parseInt(this.helper.css("left"),10)||0)+this.scrollParent.scrollLeft()}}else{return{top:0,left:0}}},_cacheMargins:function(){this.margins={left:(parseInt(this.currentItem.css("marginLeft"),10)||0),top:(parseInt(this.currentItem.css("marginTop"),10)||0)}},_cacheHelperProportions:function(){this.helperProportions={width:this.helper.outerWidth(),height:this.helper.outerHeight()}},_setContainment:function(){var e=this.options;if(e.containment=="parent"){e.containment=this.helper[0].parentNode}if(e.containment=="document"||e.containment=="window"){this.containment=[0-this.offset.relative.left-this.offset.parent.left,0-this.offset.relative.top-this.offset.parent.top,a(e.containment=="document"?document:window).width()-this.helperProportions.width-this.margins.left,(a(e.containment=="document"?document:window).height()||document.body.parentNode.scrollHeight)-this.helperProportions.height-this.margins.top]}if(!(/^(document|window|parent)$/).test(e.containment)){var c=a(e.containment)[0];var d=a(e.containment).offset();var b=(a(c).css("overflow")!="hidden");this.containment=[d.left+(parseInt(a(c).css("borderLeftWidth"),10)||0)+(parseInt(a(c).css("paddingLeft"),10)||0)-this.margins.left,d.top+(parseInt(a(c).css("borderTopWidth"),10)||0)+(parseInt(a(c).css("paddingTop"),10)||0)-this.margins.top,d.left+(b?Math.max(c.scrollWidth,c.offsetWidth):c.offsetWidth)-(parseInt(a(c).css("borderLeftWidth"),10)||0)-(parseInt(a(c).css("paddingRight"),10)||0)-this.helperProportions.width-this.margins.left,d.top+(b?Math.max(c.scrollHeight,c.offsetHeight):c.offsetHeight)-(parseInt(a(c).css("borderTopWidth"),10)||0)-(parseInt(a(c).css("paddingBottom"),10)||0)-this.helperProportions.height-this.margins.top]}},_convertPositionTo:function(f,h){if(!h){h=this.position}var c=f=="absolute"?1:-1;var e=this.options,b=this.cssPosition=="absolute"&&!(this.scrollParent[0]!=document&&a.ui.contains(this.scrollParent[0],this.offsetParent[0]))?this.offsetParent:this.scrollParent,g=(/(html|body)/i).test(b[0].tagName);return{top:(h.top+this.offset.relative.top*c+this.offset.parent.top*c-(a.browser.safari&&this.cssPosition=="fixed"?0:(this.cssPosition=="fixed"?-this.scrollParent.scrollTop():(g?0:b.scrollTop()))*c)),left:(h.left+this.offset.relative.left*c+this.offset.parent.left*c-(a.browser.safari&&this.cssPosition=="fixed"?0:(this.cssPosition=="fixed"?-this.scrollParent.scrollLeft():g?0:b.scrollLeft())*c))}},_generatePosition:function(e){var h=this.options,b=this.cssPosition=="absolute"&&!(this.scrollParent[0]!=document&&a.ui.contains(this.scrollParent[0],this.offsetParent[0]))?this.offsetParent:this.scrollParent,i=(/(html|body)/i).test(b[0].tagName);if(this.cssPosition=="relative"&&!(this.scrollParent[0]!=document&&this.scrollParent[0]!=this.offsetParent[0])){this.offset.relative=this._getRelativeOffset()}var d=e.pageX;var c=e.pageY;if(this.originalPosition){if(this.containment){if(e.pageX-this.offset.click.left<this.containment[0]){d=this.containment[0]+this.offset.click.left}if(e.pageY-this.offset.click.top<this.containment[1]){c=this.containment[1]+this.offset.click.top}if(e.pageX-this.offset.click.left>this.containment[2]){d=this.containment[2]+this.offset.click.left}if(e.pageY-this.offset.click.top>this.containment[3]){c=this.containment[3]+this.offset.click.top}}if(h.grid){var g=this.originalPageY+Math.round((c-this.originalPageY)/h.grid[1])*h.grid[1];c=this.containment?(!(g-this.offset.click.top<this.containment[1]||g-this.offset.click.top>this.containment[3])?g:(!(g-this.offset.click.top<this.containment[1])?g-h.grid[1]:g+h.grid[1])):g;var f=this.originalPageX+Math.round((d-this.originalPageX)/h.grid[0])*h.grid[0];d=this.containment?(!(f-this.offset.click.left<this.containment[0]||f-this.offset.click.left>this.containment[2])?f:(!(f-this.offset.click.left<this.containment[0])?f-h.grid[0]:f+h.grid[0])):f}}return{top:(c-this.offset.click.top-this.offset.relative.top-this.offset.parent.top+(a.browser.safari&&this.cssPosition=="fixed"?0:(this.cssPosition=="fixed"?-this.scrollParent.scrollTop():(i?0:b.scrollTop())))),left:(d-this.offset.click.left-this.offset.relative.left-this.offset.parent.left+(a.browser.safari&&this.cssPosition=="fixed"?0:(this.cssPosition=="fixed"?-this.scrollParent.scrollLeft():i?0:b.scrollLeft())))}},_rearrange:function(g,f,c,e){c?c[0].appendChild(this.placeholder[0]):f.item[0].parentNode.insertBefore(this.placeholder[0],(this.direction=="down"?f.item[0]:f.item[0].nextSibling));this.counter=this.counter?++this.counter:1;var d=this,b=this.counter;window.setTimeout(function(){if(b==d.counter){d.refreshPositions(!e)}},0)},_clear:function(d,e){this.reverting=false;var f=[],b=this;if(!this._noFinalSort&&this.currentItem[0].parentNode){this.placeholder.before(this.currentItem)}this._noFinalSort=null;if(this.helper[0]==this.currentItem[0]){for(var c in this._storedCSS){if(this._storedCSS[c]=="auto"||this._storedCSS[c]=="static"){this._storedCSS[c]=""}}this.currentItem.css(this._storedCSS).removeClass("ui-sortable-helper")}else{this.currentItem.show()}if(this.fromOutside&&!e){f.push(function(g){this._trigger("receive",g,this._uiHash(this.fromOutside))})}if((this.fromOutside||this.domPosition.prev!=this.currentItem.prev().not(".ui-sortable-helper")[0]||this.domPosition.parent!=this.currentItem.parent()[0])&&!e){f.push(function(g){this._trigger("update",g,this._uiHash())})}if(!a.ui.contains(this.element[0],this.currentItem[0])){if(!e){f.push(function(g){this._trigger("remove",g,this._uiHash())})}for(var c=this.containers.length-1;c>=0;c--){if(a.ui.contains(this.containers[c].element[0],this.currentItem[0])&&!e){f.push((function(g){return function(h){g._trigger("receive",h,this._uiHash(this))}}).call(this,this.containers[c]));f.push((function(g){return function(h){g._trigger("update",h,this._uiHash(this))}}).call(this,this.containers[c]))}}}for(var c=this.containers.length-1;c>=0;c--){if(!e){f.push((function(g){return function(h){g._trigger("deactivate",h,this._uiHash(this))}}).call(this,this.containers[c]))}if(this.containers[c].containerCache.over){f.push((function(g){return function(h){g._trigger("out",h,this._uiHash(this))}}).call(this,this.containers[c]));this.containers[c].containerCache.over=0}}if(this._storedCursor){a("body").css("cursor",this._storedCursor)}if(this._storedOpacity){this.helper.css("opacity",this._storedOpacity)}if(this._storedZIndex){this.helper.css("zIndex",this._storedZIndex=="auto"?"":this._storedZIndex)}this.dragging=false;if(this.cancelHelperRemoval){if(!e){this._trigger("beforeStop",d,this._uiHash());for(var c=0;c<f.length;c++){f[c].call(this,d)}this._trigger("stop",d,this._uiHash())}return false}if(!e){this._trigger("beforeStop",d,this._uiHash())}this.placeholder[0].parentNode.removeChild(this.placeholder[0]);if(this.helper[0]!=this.currentItem[0]){this.helper.remove()}this.helper=null;if(!e){for(var c=0;c<f.length;c++){f[c].call(this,d)}this._trigger("stop",d,this._uiHash())}this.fromOutside=false;return true},_trigger:function(){if(a.widget.prototype._trigger.apply(this,arguments)===false){this.cancel()}},_uiHash:function(c){var b=c||this;return{helper:b.helper,placeholder:b.placeholder||a([]),position:b.position,absolutePosition:b.positionAbs,offset:b.positionAbs,item:b.currentItem,sender:c?c.element:null}}}));a.extend(a.ui.sortable,{getter:"serialize toArray",version:"1.7.3",eventPrefix:"sort",defaults:{appendTo:"parent",axis:false,cancel:":input,option",connectWith:false,containment:false,cursor:"auto",cursorAt:false,delay:0,distance:1,dropOnEmpty:true,forcePlaceholderSize:false,forceHelperSize:false,grid:false,handle:false,helper:"original",items:"> *",opacity:false,placeholder:false,revert:false,scroll:true,scrollSensitivity:20,scrollSpeed:20,scope:"default",tolerance:"intersect",zIndex:1000}})})(jQuery);

var postboxes;(function(a){postboxes={add_postbox_toggles:function(c,b){this.init(c,b);a(".postbox h3, .postbox .handlediv").click(function(){var e=a(this).parent(".postbox"),f=e.attr("id");e.toggleClass("closed");postboxes.save_state(c);if(f){if(!e.hasClass("closed")&&a.isFunction(postboxes.pbshow)){postboxes.pbshow(f)}else{if(e.hasClass("closed")&&a.isFunction(postboxes.pbhide)){postboxes.pbhide(f)}}}});a(".postbox h3 a").click(function(f){f.stopPropagation()});a(".hide-postbox-tog").click(function(){var e=a(this).val();if(a(this).attr("checked")){a("#"+e).show();if(a.isFunction(postboxes.pbshow)){postboxes.pbshow(e)} if(window.configwidget !== undefined){configwidget();} }else{a("#"+e).hide();if(a.isFunction(postboxes.pbhide)){postboxes.pbhide(e)} if(window.configwidget !== undefined){configwidget();}}postboxes.save_state(c); });a('.columns-prefs input[type="radio"]').click(function(){var e=a(this).val(),f,g,h=a("#poststuff");if(h.length){if(e==2){h.addClass("has-right-sidebar");a("#side-sortables").addClass("temp-border")}else{if(e==1){h.removeClass("has-right-sidebar");a("#normal-sortables").append(a("#side-sortables").children(".postbox"))}}}else{for(f=4;(f>e&&f>1);f--){g=a("#"+d(f)+"-sortables");a("#"+d(f-1)+"-sortables").append(g.children(".postbox"));g.parent().hide()}for(f=1;f<=e;f++){g=a("#"+d(f)+"-sortables");if(g.parent().is(":hidden")){g.addClass("temp-border").parent().show()}}a(".postbox-container:visible").css("width",98/e+"%")}postboxes.save_order(c)});function d(e){switch(e){case 1:return"normal";break;case 2:return"side";break;case 3:return"column3";break;case 4:return"column4";break;default:return""}}},init:function(c,b){a.extend(this,b||{});a("#wpbody-content").css("overflow","hidden");a(".meta-box-sortables").sortable({placeholder:"sortable-placeholder",connectWith:".meta-box-sortables",items:".postbox",handle:".hndle",cursor:"move",distance:2,tolerance:"pointer",forcePlaceholderSize:true,helper:"clone",opacity:0.65,start:function(f,d){a("body").css({WebkitUserSelect:"none",KhtmlUserSelect:"none"})},stop:function(f,d){if(window.configwidget!==undefined){configwidget()}postboxes.save_order(c);d.item.parent().removeClass("temp-border");a("body").css({WebkitUserSelect:"",KhtmlUserSelect:""})}})},save_state:function(d){var b=a(".postbox").filter(".closed").map(function(){return this.id}).get().join(","),c=a(".postbox").filter(":hidden").map(function(){return this.id}).get().join(",");a.post(ajaxurl,{action:"closed-postboxes",closed:b,hidden:c,closedpostboxesnonce:jQuery("#closedpostboxesnonce").val(),page:d})},save_order:function(c){var b,d=a(".columns-prefs input:checked").val()||0;b={action:"meta-box-order",_ajax_nonce:a("#meta-box-order-nonce").val(),page_columns:d,page:c};a(".meta-box-sortables").each(function(){b["order["+this.id.split("-")[0]+"]"]=a(this).sortable("toArray").join(",")});a.post(ajaxurl,b)},pbshow:false,pbhide:false}}(jQuery));
var ajaxWidgets,ajaxPopulateWidgets,quickPressLoad;jQuery(document).ready(function(a){ajaxWidgets=["dashboard_incoming_links","dashboard_primary","dashboard_secondary","dashboard_plugins","dashboard_quick_press"];ajaxPopulateWidgets=function(b){show=function(g,c){var f,d=a("#"+g+" div.inside:visible").find(".widget-loading");if(d.length){f=d.parent();setTimeout(function(){f.load("index-extra.php?jax="+g,"",function(){f.hide().slideDown("normal",function(){a(this).css("display","");if("dashboard_plugins"==g&&a.isFunction(tb_init)){tb_init("#dashboard_plugins a.thickbox")}if("dashboard_quick_press"==g&&a.isFunction(tb_init)){tb_init("#dashboard_quick_press a.thickbox");quickPressLoad()}})})},c*500)}};if(b){b=b.toString();if(a.inArray(b,ajaxWidgets)!=-1){show(b,0)}}else{a.each(ajaxWidgets,function(c){show(this,c)})}};ajaxPopulateWidgets();postboxes.add_postbox_toggles("dashboard",{pbshow:ajaxPopulateWidgets});quickPressLoad=function(){var b=a("#quickpost-action"),c;c=a("#quick-press").submit(function(){a("#dashboard_quick_press #publishing-action img.waiting").css("visibility","visible");a('#quick-press .submit input[type="submit"], #quick-press .submit input[type="reset"]').attr("disabled","disabled");if("post"==b.val()){b.val("post-quickpress-publish")}a("#dashboard_quick_press div.inside").load(c.attr("action"),c.serializeArray(),function(){a("#dashboard_quick_press #publishing-action img.waiting").css("visibility","hidden");a('#quick-press .submit input[type="submit"], #quick-press .submit input[type="reset"]').attr("disabled","");a("#dashboard_quick_press ul").next("p").remove();a("#dashboard_quick_press ul").find("li").each(function(){a("#dashboard_recent_drafts ul").prepend(this)}).end().remove();tb_init("a.thickbox");quickPressLoad()});return false});a("#publish").click(function(){b.val("post-quickpress-publish")})}});
/*
 * Thickbox 3.1 - One Box To Rule Them All.
 * By Cody Lindley (http://www.codylindley.com)
 * Copyright (c) 2007 cody lindley
 * Licensed under the MIT License: http://www.opensource.org/licenses/mit-license.php
*/

if ( typeof tb_pathToImage != 'string' ) {
	var tb_pathToImage = "../wp-includes/js/thickbox/loadingAnimation.gif";
}
if ( typeof tb_closeImage != 'string' ) {
	var tb_closeImage = "../wp-includes/js/thickbox/tb-close.png";
}

/*!!!!!!!!!!!!!!!!! edit below this line at your own risk !!!!!!!!!!!!!!!!!!!!!!!*/

//on page load call tb_init
jQuery(document).ready(function(){
	tb_init('a.thickbox, area.thickbox, input.thickbox');//pass where to apply thickbox
	imgLoader = new Image();// preload image
	imgLoader.src = tb_pathToImage;
});

//add thickbox to href & area elements that have a class of .thickbox
function tb_init(domChunk){
    if (/[1-9]\.[7-9].[0-9]/.test($.fn.jquery)) {
		$(document).on("click", domChunk, tb_click);
	} else {
		jQuery(domChunk).live('click', tb_click);
	}
}

function tb_click(){
	var t = this.title || this.name || null;
	var a = this.href || this.alt;
	var g = this.rel || false;
	tb_show(t,a,g);
	this.blur();
	return false;
}

function tb_show(caption, url, imageGroup) {//function called when the user clicks on a thickbox link

	try {
		if (typeof document.body.style.maxHeight === "undefined") {//if IE 6
			jQuery("body","html").css({height: "100%", width: "100%"});
			jQuery("html").css("overflow","hidden");
			if (document.getElementById("TB_HideSelect") === null) {//iframe to hide select elements in ie6
				jQuery("body").append("<iframe id='TB_HideSelect'>"+thickboxL10n.noiframes+"</iframe><div id='TB_overlay'></div><div id='TB_window'></div>");
				jQuery("#TB_overlay").click(tb_remove);
			}
		}else{//all others
			if(document.getElementById("TB_overlay") === null){
				jQuery("body").append("<div id='TB_overlay'></div><div id='TB_window'></div>");
				jQuery("#TB_overlay").click(tb_remove);
			}
		}

		if(tb_detectMacXFF()){
			jQuery("#TB_overlay").addClass("TB_overlayMacFFBGHack");//use png overlay so hide flash
		}else{
			jQuery("#TB_overlay").addClass("TB_overlayBG");//use background and opacity
		}

		if(caption===null){caption="";}
		jQuery("body").append("<div id='TB_load'><img src='"+imgLoader.src+"' /></div>");//add loader to the page
		jQuery('#TB_load').show();//show loader

		var baseURL;
	   if(url.indexOf("?")!==-1){ //ff there is a query string involved
			baseURL = url.substr(0, url.indexOf("?"));
	   }else{
	   		baseURL = url;
	   }

	   var urlString = /\.jpg$|\.jpeg$|\.png$|\.gif$|\.bmp$/;
	   var urlType = baseURL.toLowerCase().match(urlString);

		if(urlType == '.jpg' || urlType == '.jpeg' || urlType == '.png' || urlType == '.gif' || urlType == '.bmp'){//code to show images

			TB_PrevCaption = "";
			TB_PrevURL = "";
			TB_PrevHTML = "";
			TB_NextCaption = "";
			TB_NextURL = "";
			TB_NextHTML = "";
			TB_imageCount = "";
			TB_FoundURL = false;
			if(imageGroup){
				TB_TempArray = jQuery("a[rel="+imageGroup+"]").get();
				for (TB_Counter = 0; ((TB_Counter < TB_TempArray.length) && (TB_NextHTML === "")); TB_Counter++) {
					var urlTypeTemp = TB_TempArray[TB_Counter].href.toLowerCase().match(urlString);
						if (!(TB_TempArray[TB_Counter].href == url)) {
							if (TB_FoundURL) {
								TB_NextCaption = TB_TempArray[TB_Counter].title;
								TB_NextURL = TB_TempArray[TB_Counter].href;
								TB_NextHTML = "<span id='TB_next'>&nbsp;&nbsp;<a href='#'>"+thickboxL10n.next+"</a></span>";
							} else {
								TB_PrevCaption = TB_TempArray[TB_Counter].title;
								TB_PrevURL = TB_TempArray[TB_Counter].href;
								TB_PrevHTML = "<span id='TB_prev'>&nbsp;&nbsp;<a href='#'>"+thickboxL10n.prev+"</a></span>";
							}
						} else {
							TB_FoundURL = true;
							TB_imageCount = thickboxL10n.image + ' ' + (TB_Counter + 1) + ' ' + thickboxL10n.of + ' ' + (TB_TempArray.length);
						}
				}
			}

			imgPreloader = new Image();
			imgPreloader.onload = function(){
			imgPreloader.onload = null;

			// Resizing large images - orginal by Christian Montoya edited by me.
			var pagesize = tb_getPageSize();
			var x = pagesize[0] - 150;
			var y = pagesize[1] - 150;
			var imageWidth = imgPreloader.width;
			var imageHeight = imgPreloader.height;
			if (imageWidth > x) {
				imageHeight = imageHeight * (x / imageWidth);
				imageWidth = x;
				if (imageHeight > y) {
					imageWidth = imageWidth * (y / imageHeight);
					imageHeight = y;
				}
			} else if (imageHeight > y) {
				imageWidth = imageWidth * (y / imageHeight);
				imageHeight = y;
				if (imageWidth > x) {
					imageHeight = imageHeight * (x / imageWidth);
					imageWidth = x;
				}
			}
			// End Resizing

			TB_WIDTH = imageWidth + 30;
			TB_HEIGHT = imageHeight + 60;
			jQuery("#TB_window").append("<a href='' id='TB_ImageOff' title='"+thickboxL10n.close+"'><img id='TB_Image' src='"+url+"' width='"+imageWidth+"' height='"+imageHeight+"' alt='"+caption+"'/></a>" + "<div id='TB_caption'>"+caption+"<div id='TB_secondLine'>" + TB_imageCount + TB_PrevHTML + TB_NextHTML + "</div></div><div id='TB_closeWindow'><a href='#' id='TB_closeWindowButton' title='"+thickboxL10n.close+"'><img src='" + tb_closeImage + "' /></a></div>");

			jQuery("#TB_closeWindowButton").click(tb_remove);

			if (!(TB_PrevHTML === "")) {
				function goPrev(){
					if(jQuery(document).unbind("click",goPrev)){jQuery(document).unbind("click",goPrev);}
					jQuery("#TB_window").remove();
					jQuery("body").append("<div id='TB_window'></div>");
					tb_show(TB_PrevCaption, TB_PrevURL, imageGroup);
					return false;
				}
				jQuery("#TB_prev").click(goPrev);
			}

			if (!(TB_NextHTML === "")) {
				function goNext(){
					jQuery("#TB_window").remove();
					jQuery("body").append("<div id='TB_window'></div>");
					tb_show(TB_NextCaption, TB_NextURL, imageGroup);
					return false;
				}
				jQuery("#TB_next").click(goNext);

			}

			document.onkeydown = function(e){
				if (e == null) { // ie
					keycode = event.keyCode;
				} else { // mozilla
					keycode = e.which;
				}
				if(keycode == 27){ // close
					tb_remove();
				} else if(keycode == 190){ // display previous image
					if(!(TB_NextHTML == "")){
						document.onkeydown = "";
						goNext();
					}
				} else if(keycode == 188){ // display next image
					if(!(TB_PrevHTML == "")){
						document.onkeydown = "";
						goPrev();
					}
				}
			};

			tb_position();
			jQuery("#TB_load").remove();
			jQuery("#TB_ImageOff").click(tb_remove);
			jQuery("#TB_window").css({display:"block"}); //for safari using css instead of show
			};

			imgPreloader.src = url;
		}else{//code to show html

			var queryString = url.replace(/^[^\?]+\??/,'');
			var params = tb_parseQuery( queryString );

			TB_WIDTH = (params['width']*1) + 30 || 630; //defaults to 630 if no paramaters were added to URL
			TB_HEIGHT = (params['height']*1) + 40 || 440; //defaults to 440 if no paramaters were added to URL
			ajaxContentW = TB_WIDTH - 30;
			ajaxContentH = TB_HEIGHT - 45;

			if(url.indexOf('TB_iframe') != -1){// either iframe or ajax window
					urlNoQuery = url.split('TB_');
					jQuery("#TB_iframeContent").remove();
					if(params['modal'] != "true"){//iframe no modal
						jQuery("#TB_window").append("<div id='TB_title'><div id='TB_ajaxWindowTitle'>"+caption+"</div><div id='TB_closeAjaxWindow'><a href='#' id='TB_closeWindowButton' title='"+thickboxL10n.close+"'><img src='" + tb_closeImage + "' /></a></div></div><iframe frameborder='0' hspace='0' src='"+urlNoQuery[0]+"' id='TB_iframeContent' name='TB_iframeContent"+Math.round(Math.random()*1000)+"' onload='tb_showIframe()' style='width:"+(ajaxContentW + 29)+"px;height:"+(ajaxContentH + 17)+"px;' >"+thickboxL10n.noiframes+"</iframe>");
					}else{//iframe modal
					jQuery("#TB_overlay").unbind();
						jQuery("#TB_window").append("<iframe frameborder='0' hspace='0' src='"+urlNoQuery[0]+"' id='TB_iframeContent' name='TB_iframeContent"+Math.round(Math.random()*1000)+"' onload='tb_showIframe()' style='width:"+(ajaxContentW + 29)+"px;height:"+(ajaxContentH + 17)+"px;'>"+thickboxL10n.noiframes+"</iframe>");
					}
			}else{// not an iframe, ajax
					if(jQuery("#TB_window").css("display") != "block"){
						if(params['modal'] != "true"){//ajax no modal
						jQuery("#TB_window").append("<div id='TB_title'><div id='TB_ajaxWindowTitle'>"+caption+"</div><div id='TB_closeAjaxWindow'><a href='#' id='TB_closeWindowButton'><img src='" + tb_closeImage + "' /></a></div></div><div id='TB_ajaxContent' style='width:"+ajaxContentW+"px;height:"+ajaxContentH+"px'></div>");
						}else{//ajax modal
						jQuery("#TB_overlay").unbind();
						jQuery("#TB_window").append("<div id='TB_ajaxContent' class='TB_modal' style='width:"+ajaxContentW+"px;height:"+ajaxContentH+"px;'></div>");
						}
					}else{//this means the window is already up, we are just loading new content via ajax
						jQuery("#TB_ajaxContent")[0].style.width = ajaxContentW +"px";
						jQuery("#TB_ajaxContent")[0].style.height = ajaxContentH +"px";
						jQuery("#TB_ajaxContent")[0].scrollTop = 0;
						jQuery("#TB_ajaxWindowTitle").html(caption);
					}
			}

			jQuery("#TB_closeWindowButton").click(tb_remove);

				if(url.indexOf('TB_inline') != -1){
					jQuery("#TB_ajaxContent").append(jQuery('#' + params['inlineId']).children());
					jQuery("#TB_window").unload(function () {
						jQuery('#' + params['inlineId']).append( jQuery("#TB_ajaxContent").children() ); // move elements back when you're finished
					});
					tb_position();
					jQuery("#TB_load").remove();
					jQuery("#TB_window").css({display:"block"});
				}else if(url.indexOf('TB_iframe') != -1){
					tb_position();
					if(jQuery.browser.safari){//safari needs help because it will not fire iframe onload
						jQuery("#TB_load").remove();
						jQuery("#TB_window").css({display:"block"});
					}
				}else{
					jQuery("#TB_ajaxContent").load(url += "&random=" + (new Date().getTime()),function(){//to do a post change this load method
						tb_position();
						jQuery("#TB_load").remove();
						tb_init("#TB_ajaxContent a.thickbox");
						jQuery("#TB_window").css({display:"block"});
					});
				}

		}

		if(!params['modal']){
			document.onkeyup = function(e){
				if (e == null) { // ie
					keycode = event.keyCode;
				} else { // mozilla
					keycode = e.which;
				}
				if(keycode == 27){ // close
					tb_remove();
				}
			};
		}

	} catch(e) {
		//nothing here
	}
}

//helper functions below
function tb_showIframe(){
	jQuery("#TB_load").remove();
	jQuery("#TB_window").css({display:"block"});
}

function tb_remove() {
 	jQuery("#TB_imageOff").unbind("click");
	jQuery("#TB_closeWindowButton").unbind("click");
	jQuery("#TB_window").fadeOut("fast",function(){jQuery('#TB_window,#TB_overlay,#TB_HideSelect').trigger("unload").unbind().remove();});
	jQuery("#TB_load").remove();
	if (typeof document.body.style.maxHeight == "undefined") {//if IE 6
		jQuery("body","html").css({height: "auto", width: "auto"});
		jQuery("html").css("overflow","");
	}
	document.onkeydown = "";
	document.onkeyup = "";
	return false;
}

function tb_position() {
var isIE6 = typeof document.body.style.maxHeight === "undefined";
jQuery("#TB_window").css({marginLeft: '-' + parseInt((TB_WIDTH / 2),10) + 'px', width: TB_WIDTH + 'px'});
	if ( ! isIE6 ) { // take away IE6
		jQuery("#TB_window").css({marginTop: '-' + parseInt((TB_HEIGHT / 2),10) + 'px'});
	}
}

function tb_parseQuery ( query ) {
   var Params = {};
   if ( ! query ) {return Params;}// return empty object
   var Pairs = query.split(/[;&]/);
   for ( var i = 0; i < Pairs.length; i++ ) {
      var KeyVal = Pairs[i].split('=');
      if ( ! KeyVal || KeyVal.length != 2 ) {continue;}
      var key = unescape( KeyVal[0] );
      var val = unescape( KeyVal[1] );
      val = val.replace(/\+/g, ' ');
      Params[key] = val;
   }
   return Params;
}

function tb_getPageSize(){
	var de = document.documentElement;
	var w = window.innerWidth || self.innerWidth || (de&&de.clientWidth) || document.body.clientWidth;
	var h = window.innerHeight || self.innerHeight || (de&&de.clientHeight) || document.body.clientHeight;
	arrayPageSize = [w,h];
	return arrayPageSize;
}

function tb_detectMacXFF() {
  var userAgent = navigator.userAgent.toLowerCase();
  if (userAgent.indexOf('mac') != -1 && userAgent.indexOf('firefox')!=-1) {
    return true;
  }
}

jQuery(document).ready(function(b){var a=function(){var f=b("#TB_window"),e=b(window).width(),d=b(window).height(),c=(720<e)?720:e;if(f.size()){f.width(c-50).height(d-45);b("#TB_iframeContent").width(c-50).height(d-75);f.css({"margin-left":"-"+parseInt(((c-50)/2),10)+"px"});if(!(b.browser.msie&&b.browser.version.substr(0,1)<7)){f.css({top:"20px","margin-top":"0"})}}return b("#dashboard_plugins a.thickbox, .plugins a.thickbox").each(function(){var g=b(this).attr("href");if(!g){return}g=g.replace(/&width=[0-9]+/g,"");g=g.replace(/&height=[0-9]+/g,"");b(this).attr("href",g+"&width="+(c-80)+"&height="+(d-85))})};a().click(function(){tb_click.call(this);b("#TB_title").css({"background-color":"#222",color:"#cfcfcf"});b("#TB_ajaxWindowTitle").html("<strong>"+plugininstallL10n.plugin_information+"</strong>&nbsp;"+b(this).attr("title"));return false});b("#plugin-information #sidemenu a").click(function(){var c=b(this).attr("name");b("#plugin-information-header a.current").removeClass("current");b(this).addClass("current");b("#section-holder div.section").hide();b("#section-"+c).show();return false});b("#install-plugins .action-links .install-now").click(function(){return confirm(plugininstallL10n.ays)})});
function send_to_editor(b){var a;if(typeof tinyMCE!="undefined"&&(a=tinyMCE.activeEditor)&&!a.isHidden()){a.focus();if(tinymce.isIE){a.selection.moveToBookmark(tinymce.EditorManager.activeEditor.windowManager.bookmark)}if(b.indexOf("[caption")===0){if(a.plugins.wpeditimage){b=a.plugins.wpeditimage._do_shcode(b)}}else{if(b.indexOf("[gallery")===0){if(a.plugins.wpgallery){b=a.plugins.wpgallery._do_gallery(b)}}else{if(b.indexOf("[embed")===0){if(a.plugins.wordpress){b=a.plugins.wordpress._setEmbed(b)}}}}a.execCommand("mceInsertContent",false,b)}else{if(typeof edInsertContent=="function"){edInsertContent(edCanvas,b)}else{jQuery(edCanvas).val(jQuery(edCanvas).val()+b)}}tb_remove()}var tb_position;(function(a){tb_position=function(){var e=a("#TB_window"),d=a(window).width(),c=a(window).height(),b=(720<d)?720:d;if(e.size()){e.width(b-50).height(c-45);a("#TB_iframeContent").width(b-50).height(c-75);e.css({"margin-left":"-"+parseInt(((b-50)/2),10)+"px"});if(typeof document.body.style.maxWidth!="undefined"){e.css({top:"20px","margin-top":"0"})}}return a("a.thickbox").each(function(){var f=a(this).attr("href");if(!f){return}f=f.replace(/&width=[0-9]+/g,"");f=f.replace(/&height=[0-9]+/g,"");a(this).attr("href",f+"&width="+(b-80)+"&height="+(c-85))})};a(window).resize(function(){tb_position()})})(jQuery);jQuery(document).ready(function(a){a("a.thickbox").click(function(){if(typeof tinyMCE!="undefined"&&tinyMCE.activeEditor){tinyMCE.get("content").focus();tinyMCE.activeEditor.windowManager.bookmark=tinyMCE.activeEditor.selection.getBookmark("simple")}})});
