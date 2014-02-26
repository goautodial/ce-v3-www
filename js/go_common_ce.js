/*
############################################################################################
####  Name:             go_common_ce.js                                                 ####
####  Type:             ci common jquery used in the ci                                 ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Franco Hora <info@goautodial.com>             ####
####  License:          AGPLv2                                                          ####
############################################################################################
*/

// global for protocol use and host to use
var $_protocol = window.location.protocol;
var $_host = window.location.host;
var $_path_string = window.location.pathname.substr(1);
var $_base_path = $_path_string.split("/")[0];
var $_active_elem = null; 

// override value of basepath
if($_base_path === "index.php"){
   $_base_path = null;
}else{
   $_base_path = "/"+$_base_path;
}

// toggle-click
$(document).ready(function(){

    $("div.inside").children().delegate('div.toggle-click',"click",function(){

         // close previous other element
         $.each($.makeArray($(this).parent().siblings('div')),function(){
            if($(this).hasClass('closed-element') === false){
                $(this).toggleClass("closed-element");
                $(this).children('div[class^="container"]').slideToggle("fast");
            } 
         });
         $(this).parent().toggleClass("closed-element");
         $(this).siblings('div').slideToggle("fast");
    });

});




function pass_to_php(){
   return $_active_elem;
}

function windowOpener(url){
    window.open (url,"",'width=620,height=300,scrollbars=yes,menubar=yes,address=yes');
}


function sortrows($obj,$cols,$order){

    //bubble
    $($obj).children().each(function(){
         var $row = $(this);
         var $content = new String($(this).children("div:nth-child("+($cols+1)+")").text());
         var $contentPosition = $(this).children().offset();       
         var $totalchild = $($obj).children().length;
         var $idx = $row.index();
         if($($row).css("display") === "block"){
              for($j=($idx+1);$j<$totalchild;$j++){
                  var $compareRow = $($obj).children()[$j];
                  var $compare = new String($($compareRow).children("div:nth-child("+($cols+1)+")").text());
                  var $position = $($compareRow).offset();
                  var $rowIndx = $($row).attr('class');
                  var $compareIndx = $($compareRow).attr('class');
                  if($($compareRow).css("display") === "block"){
                    if($order === "asc"){
                       if($compare < $content){
                           $($compareRow).attr("class",$rowIndx);
                           $($row).attr("class",$compareIndx);
                           $($compareRow).offset($contentPosition);
                           $($row).offset($position);
                           $contentPosition = $($row).offset();
                       }
                    } else {
                       if($compare > $content){
                           $($compareRow).attr("class",$rowIndx);
                           $($row).attr("class",$compareIndx);
                           $($compareRow).offset($contentPosition);
                           $($row).offset($position);
                           $contentPosition = $($row).offset();
                       }
                    }
                  }
              }
         }
    });
}

function display($obj,$start,$end){

    try{
 
        $($obj).children().hide().slice($start,$end).show();

    } catch(err) {
        console.log(err);
    }

}

var $perpage = 0;
var $offset = 0;
var $total_page = 0;
function paginate($obj,$displayperpage){

    try {

         // init
         var $curr_page = null;
         var $next = null;
         $offset = 0;
         $currPage = 0;
         $($(".pager-paginater > input")).val("1");

         // display per page
         $perpage = $displayperpage;
         if(isNaN($perpage)){
             $perpage = $($obj).children().size();
         }
  
         // total page
         $total_page = Math.ceil($($obj).children().size() / $perpage);
         $("#pager-totalpage").empty();
         $("#pager-totalpage").append($total_page);
       
         // set display perpage
         display($obj,$offset,$perpage);



    } catch(err) {
         console.log(err);
    }

}


function firstpage($obj){
     if($perpage !== "all"){
          $($(".pager-paginater > input")).val("1");
          $currEnd = $perpage * 1;
          $offset = 0;
          display($obj,$offset,$currEnd);
     }   
}

function nextpage($obj,$next){
     if($perpage !== "all"){
        if($next <= $total_page){
              $($(".pager-paginater > input")).val($next);
              $currEnd = $perpage * $next;
              $offset = parseInt($perpage) + parseInt($offset); 
              display($obj,$offset,$currEnd);
        }
     }
}

function backpage($obj,$next){
     if($perpage !== "all"){
          if($next >= 1){
                $($(".pager-paginater > input")).val($next);
                $currEnd = $currEnd - $perpage;
                $offset = parseInt($offset) - parseInt($perpage); 
                display($obj,$offset,$currEnd);                     
          }
     }
}

function lastpage($obj,$lastpage){
     if($perpage !== "all"){

          $($(".pager-paginater > input")).val($lastpage);
          $currEnd = $perpage * $lastpage;
          $last = parseInt($($obj).children().size()) % $perpage;
          if($last !== 0){
              $offset = $($obj).children().size() - $last;
          }else{
              $offset = $currEnd - $perpage;
          }
          display($obj,$offset,$currEnd);

     }   
}


function closer($_obj){

    try{

        $(".overlay-closer").animate({top:-3000},900);
        $($_obj).animate({top:-3000},900);
        $(".overlay").fadeOut(2000);

    } catch(err) {
       alert(err);
    }
}

function reposition($_objs){

    try{

        if($_objs){

            if($_objs instanceof Array){

                  $.each($_objs,function(){

                       //$(this).offset({left:((Math.ceil($(window).width()) / 2) - (Math.ceil($(this).outerWidth(true)) / 2))});
                       //$(".overlay-closer").offset({left:((Math.ceil($(window).width()) / 2) + (Math.ceil($(this).outerWidth(true)) / 2) - 10)});
                       $(this).css("margin-left","auto");
                       $(this).css("margin-right","auto");
                 

                  });

            } else {
               alert("Please provide an array of your wizard box");
            }

        }

    } catch(err){
        alert(err);
    }

}

function wizard($_obj,$extraWidth,$extraHeight){

    try{

       if($_obj){

            $(".overlay").fadeIn(900);
            $(".overlay-closer").animate({top:60},900);
            $($_obj).animate({top:70},900);

       } else {
           alert("Object is undefined");
       }

    } catch(err) {
        alert(err);
    }

}

/*
function wizard($obj,$extraWidth,$extraHeight,$closer_extaHeight){


     var $_alive = $obj;
     var $_overlay = $("div.overlay");
     var $_overlay_closer = $("div.overlay-closer");

     if($_alive){

         try{

            if($_overlay.length > 0 && $_overlay_closer.length > 0){

                // show overlay
                $($_overlay).fadeIn(900);


                // clone first the object
                var $obj_copy = $($obj).clone(true);
                var $_overlay_closer_copy = $($_overlay_closer).clone(true);

                // config the position of the object 
                $($obj_copy).offset({left:((Math.ceil($(window).width()) / 2) - (Math.ceil($($obj_copy).outerWidth(true)) / 2) - $extraWidth)})
                            .css("position","fixed").animate({"top":"70px"},900);
               
                $($_overlay_closer_copy).offset({ left:((Math.ceil($(window).width()) / 2) + (Math.ceil($($obj_copy).outerWidth(true)) / 2) - $extraWidth )
                                               }).css("position","fixed").animate({"top":(80 - $closer_extaHeight)},900);


                // appending objects here 
                $("div.overlay-container").append($_overlay_closer_copy);
                $("div.overlay-container").append($obj_copy);

            } else {

               alert("Error : There is no overlay objects or overlay-closer is not defined");
 
            }

         }catch(err){
 
             alert("Error on:" + err);

         } 

     } else {

         alert("Your object is undefined");
  
     }

}*/
