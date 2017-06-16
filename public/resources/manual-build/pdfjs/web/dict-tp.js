        /**
         * Created by tuyenlv on 6/11/17.
         */

        function addDictSidebar() {
            $('body').append(
                '<div class="dict-sb-wrapper"><iframe class="dict-cambridge" src="https://dictionary.cambridge.org/dictionary/english/inception"></iframe><iframe class="dict-vdict" src="https://vdict.com/inception,1,0,0.html"></iframe></div>'
            );
            $('<style type="text/css">html.dicts-creed div.dict-sb-wrapper{z-index: 1000;position:fixed;top:-111px;right:0;bottom:0;display:none}html.dicts-creed div.dict-sb-wrapper iframe{z-index: 1001;width:100%}html.dicts-creed div.dict-sb-wrapper.dict-active{display:block}html.dicts-creed div.dict-sb-wrapper iframe.dict-cambridge{overflow-x:hidden;}</style>').appendTo("head");
            $('.dict-sb-wrapper').mouseleave(function () {
                $(this).removeClass('dict-active');
            });
            var docWidth = $(document).width();
            var docHeight = $(window).height();
            var dict1H = docHeight / 2 + 111;
            var dict2H = docHeight / 2
            var widthThres = 0.75;
            $('.dict-sb-wrapper').width(docWidth * (1 - widthThres));
            $('.dict-cambridge').height(dict1H + "px");
            $('.dict-vdict').height(dict2H + "px");
            $('html').addClass('dicts-creed');
        }

        $(document).ready(function() {
            if(!confirm('Using dictionary?')) {
                return;
            }
            addDictSidebar();
            $(document).bind('copy', function (e) {
                var text = "";
                if (window.getSelection) {
                    text = window.getSelection().toString();
                } else if (document.selection && document.selection.type !== "Control") {
                    text = document.selection.createRange().text;
                }
                if(text === undefined || text === '' || text.length > 30) {
                    return;
                }
                text = text.toLowerCase();
                text = text.trim();
                $('.dict-cambridge').attr('src', 'https://dictionary.cambridge.org/dictionary/english/' + text);
                $('.dict-vdict').attr('src', 'https://vdict.com/' + text + ',1,0,0.html');
                $('.dict-sb-wrapper').addClass('dict-active');
            });
        });
