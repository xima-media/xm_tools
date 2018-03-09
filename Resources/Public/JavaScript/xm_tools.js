/**
 * respimg.js - A javascript to choose the best fitting image for responsive design
 *
 * @author Sebastian Gierth <sgi@xima.de>
 * @version 1.0.0
 * @depends
 *        jQuery 1.4.1
 *
 */
if (typeof XIMA.api === "undefined") {
    var XIMA = {
        api: {}
    };
}

XIMA.api.responsiveImages = (function (window, document, $, undefined) {

    /**
     * selectors
     * @var object
     */
    var selectors = {};

    /**
     * Set selectors for jQuery objects
     * @param {string} wrapperSelector Selector of element that wraps the <img/>.
     * @param {string} imageSelector   Selector of responsive <img/>.
     * @return void
     */
    function init(wrapperSelector, imageSelector) {

        selectors = {
            wrapper: wrapperSelector || 'body',
            image: imageSelector || 'img'
        };
    }

    /**
     * Get sourceset and set the best fitting source as image.
     * @return void
     */
    function setResponsiveImages() {

        $(selectors.wrapper).each(function () {

            var $this = $(this);

            var wrapElement = {
                width: $this.width()
            };

            // Iterate through all <img/> of wrapper
            $(selectors.image, $(this)).each(function () {

                var $this = $(this);

                // Get sourceset by comma seperated data-attribute
                var srcsets = $this.data('srcset').split(',');

                // create objects from array of sourcesets
                var images = [];
                for (var i in srcsets) {

                    if (srcsets[i]) {

                        images.push({
                            src: /(?:.(?!\d+w))+/.exec(srcsets[i])[0].trim(),
                            width: /(\d+)(?:w)/.exec(srcsets[i])[1]
                        });
                    }
                }

                // Sort ascending by width property
                images.sort(function (a, b) {
                    return a.width - b.width;
                });

                // Set the best fitting source as src-attribute
                for (var j in images) {
                    if (images[j].width >= wrapElement.width) {
                        $this.attr('src', images[j].src);
                        $this.attr('width', wrapElement.width);
                        break;
                    }
                    else if (j === images.length - 1) {
                        $this.attr('src', images[j].src);
                        $this.attr('width', wrapElement.width);
                    }
                }
            });
        });
    }

    /**
     * Event handler on resizing window.
     */
    window.onresize = setResponsiveImages;

    /**
     * Return public object
     */
    return {
        run: function (wrapperSelector, imageSelector) {

            init(wrapperSelector, imageSelector);
            setResponsiveImages();
        },
        refresh: function () {

            setResponsiveImages();
        }
    };

})(window, document, jQuery);
