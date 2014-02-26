(function($){

   var goPaginate = function(){
       var defaults = {
                perPage : 25,
                rowscount : 1000
           },
           getTotalPage = function(defaults){
                return Math.max(rowscount.rowscount/defaults.perPage);
           };
           return {
                init : function(rowscount){
                   this.append(pagination.getTotalPage);
                }
           };
   };
   $.fn.extend({
        goPaginate : goPaginate.init
   });

})(jQuery);
