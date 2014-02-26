var $offsets = [],
    $currperpages = [],
    $totalpages = [];

function pagination($obj,$displayperpage,$parentclass){

    try {

         // init
         var $curr_page = null;
         var $next = null;
         $offsets[$parentclass] = 0;
         var $currPage = 0;
         $("."+$parentclass+" > span.pager-paginater > input").empty().val("1");


         // display per page
         $perpage = $displayperpage;
         if(isNaN($perpage)){
             $perpage = $($obj).children().size();
         }
  
         // total page
         $total_page = Math.ceil($($obj).children().size() / $perpage);
         $("."+$parentclass+" > span.pager-paginater > span").empty().append($total_page);

         // hide pagination if $obj children is less than $displayperpage
         if(($($obj).children().size()) < ($perpage*1)){
              if($($obj).children().size() !== $perpage ){
                 $("."+$parentclass+" > span").hide();
              }
         } else {
              $("."+$parentclass+" > span").show();
         }

         $currperpages[$parentclass] = $perpage;
         $totalpages[$parentclass] = $total_page;
         $offsets[$parentclass] = 0;
       
         // set display perpage
         console.log($offsets[$parentclass]);
         display($obj,$offsets[$parentclass],$perpage);

    } catch(err) {
         console.log(err);
    }

}

function firstpager($obj,$parentclass){

     if($currperpages[$parentclass] !== "all"){
          $($("."+$parentclass+" > span.pager-paginater > input")).val("1");
          $currEnd = parseInt($currperpages[$parentclass]) * 1;
          $currperpages[$parentclass] = $currEnd;
          $offsets[$parentclass] = 0;
          display($obj,$offsets[$parentclass],$currEnd);
     }   
}

function nextpager($obj,$next,$parentclass){
     if($currperpages[$parentclass] !== "all"){
        if($next <= $totalpages[$parentclass]){
              $($("."+$parentclass+" > span.pager-paginater > input")).val($next);
              $currEnd = $currperpages[$parentclass] * $next;
              $offsets[$parentclass] = parseInt($currperpages[$parentclass]) + parseInt($offsets[$parentclass]); 
              display($obj,$offsets[$parentclass],$currEnd);
        }
     }
}

function backpager($obj,$next,$parentclass){
     if($currperpages[$parentclass] !== "all"){
          if($next >= 1){
                $($("."+$parentclass+" > span.pager-paginater > input")).val($next);
                $currEnd = $currEnd - $currperpages[$parentclass];
                $offsets[$parentclass] = parseInt($offsets[$parentclass]) - parseInt($currperpages[$parentclass]); 
                display($obj,$offsets[$parentclass],$currEnd);                     
          }
     }
}

function lastpager($obj,$lastpage,$parentclass){
     if($perpage !== "all"){

          $($(".pager-paginater > input")).val($lastpage);
          $currEnd = $currperpages[$parentclass] * $lastpage;
          $last = parseInt($($obj).children().size()) % $currperpages[$parentclass];
          if($last !== 0){
              $offsets[$parentclass] = $($obj).children().size() - $last;
          }else{
              $offsets[$parentclass] = $currEnd - $currperpages[$parentclass];
          }
          display($obj,$offsets[$parentclass],$currEnd);

     }   
}

function totalperpage($divContent,$divIndex,$parentclass,$page){

    var $totals = [],$currEnd=25,timeformat='',hour=0,min=0,sec=0,time=[],$seconds=0,$subtotals=0;
    
    try{

        if($("."+$divContent).children().size() !== 0){

            if($offsets[$parentclass] === undefined){
                 $offsets[$parentclass] = 0;
            }
            if($currperpages[$parentclass] !== undefined){
                $currEnd = $currperpages[$parentclass] * $page; 
            }
            $currEnd -= 1;

            $("."+$divContent).children().each(function(indx){
                  if(indx >= $offsets[$parentclass] && indx <= $currEnd){
                      if($divIndex instanceof Array){
                         $div = this;
                         $.each($divIndex,function(indx,val){
                             time = $($($div).children()[val]).text().split(":");
                             $seconds = parseInt(time[0]*3600) + parseInt(time[1]*60) + parseInt(time[2]*1);
                             if($totals[val] === undefined){
                                 $totals[val] = 0;
                             }
                             $totals[val] = parseInt($totals[val]) + parseInt($seconds);
                         });
                      }else{
                         alert("Please use arrray variables");
                      }
                  }
            });
            $.each($totals,function(indx,val){
                 if(val !== undefined){
                    hour = parseInt(val / 3600) % 24;
                    min = parseInt(val / 60) % 60;
                    sec = parseInt(val) % 60;
                    $($("."+$parentclass+" > div.totaltime > div")[indx]).empty().append(((hour>9)? hour :'0'+hour)+':'+((min>9)? min :'0'+min)+":"+((sec>9)? sec :'0'+sec));
                    //$("."+$parentclass+" > div.totaltime > div."+$divTototal).empty().append(); */
                 }
            });
        }
    } catch(e){
        console.log(e);
    }
}
