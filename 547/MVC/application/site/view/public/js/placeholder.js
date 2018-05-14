;(function(win, $){
    var mypalce = function(obj){
        $(obj).each(function(i){
            var _this = $(this);
            var _palce = _this.attr('placeholder');

            _this.attr('placeholder', '').val(_palce);
            _this.bind('focus',function(){
                if ($.trim(_this.val()) == _palce) {
                    _this.val('')
                }
            }).bind('blur', function(){
                if (!$.trim(_this.val()) || $.trim(_this.val()) == _palce) {
                    _this.val(_palce);
                }
            });
        });
    };
    mypalce.prototype = {
        'test': function(){}
    };
    mypalce.init = function(obj){
        new this(obj);
    };
    win['myPalce'] = mypalce;
})(window, jQuery);
