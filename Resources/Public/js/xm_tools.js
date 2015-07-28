/**
 * Class XmTools
 */

function XmTools() 
{
    //singleton pattern (https://code.google.com/p/jslibs/wiki/JavascriptTips#Singleton_pattern);
    if ( arguments.callee._singletonInstance )
    {
        return arguments.callee._singletonInstance;
    }
    arguments.callee._singletonInstance = this;
    
    var _translations = {};
    var _parameters = {};
    
    /**
     * Return current language key
     */
    this.getLang = function () 
    {
        return jQuery('html').attr('lang');
    };
    
    /**
     * Add an object of translations in current language
     */
    this.addTranslations = function (translations)
    {
        _translations = jQuery.extend(_translations, translations);
        
        return true;
    };
    
    /**
     * Get a translation in current language
     */
    this.translate = function (key)
    {
        var translation = 'Translation missing: ' + key;
        
        if (_translations[key])
        {
            translation = _translations[key];
        }
        
        return translation;
    };
    
    /**
     * Set site parameters
     */
    this.setParameters = function(parameters)
    {
        _parameters = parameters;
        
        return true;
    };
    
    /**
     * Get parameters
     * 
     * @returns Object
     */
    this.getParameters = function ()
    {
        return _parameters;
    };
    
    /**
     * Get a parameter
     * 
     * @returns Object|string|int
     */
    this.getParameter = function (key)
    {
        var parameter = null;
        
        if (_parameters[key])
        {
            parameter = _parameters[key];
        }
        
        return parameter;
    };
    
    /**
     * Fixt probleme bei href="#" und baseUrl
     * 
     * @returns {void}
     */
    this.fixBaseUrl = function () 
    {
        var baseUri = location.href.toString().split("#");
        
        jQuery('a[href^=#]').each(function() 
        {
            jQuery(this).attr("href", baseUri[0] + jQuery(this).attr("href"));
        });
    };
    

}

var xmTools = new XmTools();