var X = function(){
	return {
		ajax: function(){

		}
	}
}

var Tools = function () {

    return {
    	noty: function(json){
            if(noty != undefined)
            {
                noty(json);
            }
            else
            {
                alert(json.text);
            }
        },

         // wrapper function to  block element(indicate loading)
        blockUI: function (el, msg, opacity, centerY) {
            var el = jQuery(el); 
            if(msg == undefined)
            {
            	msg = '<img src="http://makaprojects.oss-cn-hangzhou.aliyuncs.com/plat/1-12/jiazai.gif" align="" width="10%">';
            }
            if(opacity == undefined)
            {
            	opacity = 0.5;
            }
            el.block({
                    message: msg,
                    //message: 'loading',
                    centerY: false,
                    css: {
                        top: '10%',
                        border: 'none',
                        padding: '2px',
                        backgroundColor: 'none'
                    },
                    overlayCSS: {
                        backgroundColor: '#fff',
                        opacity: opacity,
                        cursor: 'wait'
                    }
                });
        },

        // wrapper function to  un-block element(finish loading)
        unblockUI: function (el) {
            jQuery(el).unblock({
                    onUnblock: function () {
                        jQuery(el).removeAttr("style");
                    }
                });
        },

    };

}();

var Info = function () {
    return {
        success: function(msg) {
            display_noty(msg, "success");
        },
        error: function (msg) {
            display_noty(msg, "error");
        },
        infomation: function(msg) {
            display_noty(msg, "infomation");
        },
        alert: function(msg, confirm_callback) {
            display_noty(msg, "alert", confirm_callback);
        },
        confirm: function(msg, confirm_callback, cancel_callback) {
            display_noty(msg, "confirm", confirm_callback, cancel_callback);
        }
    }
}();

function display_noty(text, type, confirm_callback, cancel_callback) {
    if(!type) {
        type = 'error';
    }
    switch (type) {
        case 'alert' :
            text = "<br/>" + '<i class="hi hi-info-sign icon-fix text-primary fa-2x"></i> ' + text + "<br/><br/>"
            noty({
                text        : text,
                type        : type,
                layout      : 'topCenter',
                theme       : 'confirmTheme', // 修改了蒙层的透明度
                template    : '<div class="confirm-box"><div class="block-title"><h4>请注意</h4></div><div class="noty_message"><span class="noty_text"></span><div class="noty_close"></div></div></div>',
                buttons: [
                  {addClass: 'btn btn-sm btn-primary btn-spacing', text: '确定', onClick: function($noty) {$noty.close(); confirm_callback && confirm_callback()}},
                ],
                animation: {
                    open: {height: 'toggle'},
                    close: {height: 'toggle'},
                    speed: 300,
                },
                modal       : true,
                maxVisible  : 1,
            });
        break;
        case 'confirm':
            text = "<br/>" + '<i class="hi hi-question-sign icon-fix text-primary fa-2x"></i> ' + text + "<br/><br/>"
            noty({
                text        : text,
                type        : type,
                layout      : 'center',
                theme       : 'confirmTheme',
                modal       : true,
                maxVisible  : 1,
                template    : '<div class="confirm-box"><div class="block-title"><h4>确认操作</h4></div><div class="noty_message"><span class="noty_text"></span><div class="noty_close"></div></div></div>',
                buttons: [
                  {addClass: 'btn btn-sm btn-primary btn-spacing', text: '确定', onClick: function($noty) {$noty.close(); confirm_callback && confirm_callback()}},
                  {addClass: 'btn btn-sm btn-default btn-spacing', text: '取消', onClick: function($noty) {$noty.close(); cancel_callback && cancel_callback()}},
                ],
                animation: {
                    open: {height: 'toggle'},
                    close: {height: 'toggle'},
                    speed: 300,
                },
            });
        break;
        default:
            var n = noty({
                text        : text,
                type        : type,
                dismissQueue: true,
                layout      : 'topCenter',
                theme       : 'defaultTheme',
                timeout     : 2000
            });
    }
}


/**
 * 继承参照对象的高度
 * resize 也要随着变更
 * fullHeight
 */

(function($){
	$.fn.fullHeight = function(settings){

		var defaultSettings = {
			type	: 'window',
		}
		
		/* Combining the default settings object with the supplied one */
		settings = $.extend(defaultSettings,settings);

		/*
		*	Looping through all the elements and returning them afterwards.
		*	This will add chainability to the plugin.
		*/
		
		return this.each(function(){

			var elem = $(this);
			
			// If the title attribute is empty, continue with the next element
			if(elem.data('parent'))
			{
				settings = $.extend(settings,{type: elem.data('parent')});
			}
			
			var handlefh = new handleFH();
			handlefh.init(elem, settings.type);

		});
		
	}

	function handleFH(){}
	
	handleFH.prototype = {
		init: function (tar, type){
			this.set(tar, type);
			$(window).resize(function(){
				setHeight(tar, type);
			});
		},
		set: function(tar, type){
			setHeight(tar, type);
		}

	}

	function setHeight(tar, type)
	{
		if(type == 'window')
		{
			tar.height(calculate($(window), tar));
			return;
		}

		if(type == 'parent')
		{
			tar.height(calculate(tar.parent(), tar));
			return;
		}

		if(!$(type))
		{
			return;
		}

		tar.height(calculate($(type), tar));
	}

	function calculate(ori, tar)
	{
		return ori.height()
				 - parseInt(tar.css('padding-top')) - parseInt(tar.css('padding-bottom'))
				 - parseInt(tar.css('margin-top')) - parseInt(tar.css('margin-bottom'))
				 - parseInt(tar.css('borderTopWidth')) - parseInt(tar.css('borderBottomWidth'))
				 - (tar.data('offsetheight')==undefined?0:parseInt(tar.data('offsetheight')));
	}
	
})(jQuery);


/**
 * 继承参照对象的高度
 * resize 也要随着变更
 * fullWidth
 */

(function($){
	$.fn.fullWidth = function(settings){

		var defaultSettings = {
			type	: 'window',
		}
		
		/* Combining the default settings object with the supplied one */
		settings = $.extend(defaultSettings,settings);

		/*
		*	Looping through all the elements and returning them afterwards.
		*	This will add chainability to the plugin.
		*/
		
		return this.each(function(){

			var elem = $(this);
			
			// If the title attribute is empty, continue with the next element
			if(elem.data('parent'))
			{
				settings = $.extend(settings,{type: elem.data('parent')});
			}
			
			var handlefw = new handleFW();
			handlefw.init(elem, settings.type);

		});
		
	}

	function handleFW(){}
	
	handleFW.prototype = {
		init: function (tar, type){
			this.set(tar, type);
			$(window).resize(function(){
				setWeight(tar, type);
			});
		},
		set: function(tar, type){
			setWeight(tar, type);
		}

	}

	function setWeight(tar, type)
	{
		if(type == 'window')
		{
			tar.width(calculate($(window), tar));
			return;
		}

		if(type == 'parent')
		{
			tar.width(calculate(tar.parent(), tar));
			return;
		}

		if(!$(type))
		{
			return;
		}

		tar.width(calculate($(type), tar));
	}

	function calculate(ori, tar)
	{
		return ori.width()
				 - parseInt(tar.css('padding-left')) - parseInt(tar.css('padding-right'))
				 - parseInt(tar.css('margin-left')) - parseInt(tar.css('margin-right'))
				 - parseInt(tar.css('borderLeftWidth')) - parseInt(tar.css('borderRightWidth'))
				 - (tar.data('offsetwidth')==undefined?0:parseInt(tar.data('offsetwidth')));
	}
	
})(jQuery);


/**
 * 全选
 * fullWidth
 */

(function(){

	window.checkAll = function(tar, check){

		if(check)
		{
			$(tar.data('target')).iCheck('check');
		}
		else
		{
			$(tar.data('target')).iCheck('uncheck');
		}
	}

	//获取所有的id
	window.get_ids = function(selector){
	    var ids = '';
	    $(selector +' input:checkbox').each(function() {
	      if(this.checked) {
	          var vs = this.value.split('::');
	          ids += vs[0] + ',';
	        }
	  });
	  return ids;
	}
	
})();


