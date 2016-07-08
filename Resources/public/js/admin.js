(function ($) {
    'use strict';

    toastr.options = {
        "closeButton": true,
        "debug": false,
        "positionClass": "toast-bottom-left",
        "toastClass": "black",
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    var activeAjaxConnections = 0;
    var ajaxLoader;

    $.ajaxSetup({
        beforeSend: function () {
            if (activeAjaxConnections == 0) {
                ajaxLoader = toastr.info('Please wait.', 'Loading', {
                    timeOut: 0,
                    closeButton: false
                });
            }
            activeAjaxConnections++;
        },
        complete: function () {
            activeAjaxConnections--;
        }
    });

    $(document).ajaxStop(function () {
        ajaxLoader.remove();
    });


    $(document).ready(function () {
        LangTabs();
    });

    function LangTabs() {
        var $langTabs,
            $langFields;

        _init();

        function _changeLangNotUsedTabs(event) {
            var $clickedTab = $(event.currentTarget),
                clickedTabIndex = $clickedTab.index();

            for (var i = 1; i < $langTabs.length; i++) {
                var $otherLangTab = $($langTabs[i]);

                $otherLangTab.find('li:eq(' + clickedTabIndex + ') > a').click();
            }
        }

        function _hideNotUsedTabs() {
            for (var i = 1; i < $langTabs.length; i++) {
                var $langTab = $($langTabs[i]);

                $langTab.hide();
            }
        }

        function _isOnlyOneLang() {
            return $langTabs.first().find('li').length === 1;
        }

        function _hideAllTabs() {
            $langTabs.hide();
        }

        function _events() {
            var $mainLangTab;

            $mainLangTab = $langTabs.first();

            $mainLangTab.on('click', 'li', function (event) {
                _changeLangNotUsedTabs(event);
            });
        }

        function _init() {
            $langTabs = $('.a2lix_translationsLocales');
            $langFields = $('.a2lix_translationsFields');

            if (!$langTabs.length) {
                return false;
            }

            if (_isOnlyOneLang()) {
                _hideAllTabs();
            } else if ($langTabs.length > 1) {
                _hideNotUsedTabs();
                _events();
            }
        }
    }
})(jQuery);