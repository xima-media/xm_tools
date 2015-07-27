/**
 * Class TxXmTools
 */
function TxXmTools()
{
  /**
   * Message after submit or open new page
   * 
   * @param string clickSelector
   * @param string message
   * @returns void
   */
  this.infoAfterFormSubmit = function(clickSelector, message)
  {
    jQuery(clickSelector).click(function(event) {

		event.preventDefault();
		
		var msg = message;

		/* message can also be a selector */
		if (msg.indexOf('#') > 0){
			msg = jQuery(msg).val();
		}
			
		if (jQuery(this).html() !== null) {
			var msg = msg.replace(/(?:{{ph}})/g, jQuery(this).attr('title'));
		}
	
		jQuery.colorbox({
	        html: '<div class="loading" id="loading_'+currentLang+'"><p>' + msg + '</p></div>',
	        closeButton: false
	    });
	
		setTimeout(function(){
			var target = jQuery(event.currentTarget);
			var targetType = target.prop('tagName');
			
			switch (targetType){
				case 'BUTTON':
					target.closest('form').submit();
					break;
				case 'A':
					window.location = target.attr('href');
					break;
				default:
					break;
			}
	    },1);
	
   });
  };
  
  /**
   * Init bootstrap datepicker
   * 
   * @param string idSelectorPrefix Example: #[idSelectorPrefix]_from and #[idSelectorPrefix]_to
   * @param string format Example: dd.mm.yyyy|yyyy-mm-dd
   * @param string lang Language code (e.g.: en, de, fr ...)
   * @param int range Default range betwen from and to date
   */
  this.initDatepicker = function(idSelectorPrefix, format, lang, range)
  {
    var format = format || 'dd.mm.yyyy';
    var lang = lang || 'en';
    var range = range || 1;
    var nowTemp = new Date();
    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
    var config = {
      format: format,
      startDate: 'now',
      weekStart: 1,
      language: lang,
      onClose: function(dateText, inst) {
        jQuery(this).attr('disabled', false);
      },
      beforeShow: function(input, inst) {
        jQuery(this).attr('disabled', false);
      },
      onRender: function(date) {
        return date.valueOf() < now.valueOf() ? 'disabled' : '';
      }
    };

    // checkout
    var checkout = jQuery('#' + idSelectorPrefix + '_to').datepicker(config).on('changeDate', function(ev) {
      checkout.hide();
    }).data('datepicker');

    // checkin
    var checkin = jQuery('#' + idSelectorPrefix + '_from').datepicker(config).on('changeDate', function(ev) {
      if (ev.date.valueOf() > checkout.date.valueOf()) {
        var newDate = new Date(ev.date);
        newDate.setDate(newDate.getDate() + range);
        checkout.setDate(newDate);
      }

      jQuery('#' + idSelectorPrefix + '_to').datepicker('setStartDate', ev.date);
      checkin.hide();

      if (typeof window.innerWidth !== 'undefined')
      {
        viewportwidth = window.innerWidth;
        if (viewportwidth > 767) {
          jQuery('#' + idSelectorPrefix + '_to')[0].focus();
        }
      }
    }).data('datepicker');
  };
  /**
   * Init single bootstrap datepicker
   * 
   * @param string selector
   * @param string format Example: dd.mm.yyyy|yyyy-mm-dd
   * @param string lang Language code (e.g.: en, de, fr ...)
   */
  this.initSingleDatepicker = function(selector, format, lang)
  {
    var format = format || 'dd.mm.yyyy';
    var lang = lang || 'en';
    var config = {
      // don't delete this comment (workaround for correct behaviour of this array)
      format: format,
      startDate: 'now',
      weekStart: 1,
      language: lang,
      onClose: function(dateText, inst) {
        jQuery(this).attr('disabled', false);
      },
      beforeShow: function(input, inst) {
        jQuery(this).attr('disabled', false);
      },
      onRender: function(date) {
        return date.valueOf() < now.valueOf() ? 'disabled' : '';
      }
    };

    var checkin = jQuery(selector).datepicker(config).on('changeDate', function(ev) {
      checkin.hide();
    }).data('datepicker');
  };
}