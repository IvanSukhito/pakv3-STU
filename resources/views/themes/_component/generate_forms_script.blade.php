<script type="text/javascript">
    'use strict';
    $(document).ready(function() {
        $('.texteditor').each(function(i, item) {
            CKEDITOR.replace(item.id, {
                autoParagraph: true,
                allowedContent: true,
                extraAllowedContent: '*(*);*{*};*[*]{*};div(class);span(class);h5[*]',
                extraPlugins: 'justify,format,colorbutton,font,smiley',
                filebrowserBrowseUrl: '/assets/themes/js/ckeditor/plugins/ckfinder/ckfinder.html',
                filebrowserUploadUrl: '/assets/themes/js/ckeditor/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images&_token={{csrf_token()}}'
            });
        });

        $('.dateyear').datepicker({
            format: 'yyyy',
            weekStart:1,
            keyboardNavigation: false,
            viewMode: "years",
            minViewMode: "years",
            todayHighlight: true,
            autoclose: true
        });

        $('.datemonthyear').datepicker({
            viewMode: 1,
            minViewMode: 1,
            format: 'yyyy-mm',
            weekStart:0,
            todayHighlight: true,
            autoclose: true
        });

        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });

        $('.datetime').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss'
        });

        $('.datetime2').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            inline: true
        });

        $('.time').datetimepicker({
            format: 'HH:mm:ss'
        });

        $('.daterange').daterangepicker({
            // timePicker: true,
            // timePicker24Hour: true,
            // timePickerIncrement: 15,
            locale: {
                "format": "YYYY-MM-DD",
                "separator": " | "
            }
        });

        $('.daterangetime').daterangepicker({
            timePicker: true,
            timePicker24Hour: true,
            // timePickerIncrement: 15,
            locale: {
                "format": "YYYY-MM-DD HH:mm",
                "separator": " | "
            }
        });
        $('.select2').select2();
        $('.tagging').select2({
            tags: true
        });

        $('.money-format').inputmask('numeric', {
            radixPoint: ".",
            groupSeparator: ",",
            digits: 2,
            autoGroup: true,
            prefix: '', //Space after $, this will not truncate the first character.
            rightAlign: true
        });
    });

    function removeFormatMoney(money) {
        if (typeof money !== "undefined") {
            return Number(money.replace(/[^0-9.-]+/g,""));
        }
        else {
            return 0;
        }
    }

    function setFormatMoney(number, decPlaces) {
        if (typeof number !== "undefined") {
            return setFormatMoney(number, decPlaces, '.', ',');
        }
        else {
            return 0;
        }
    }

    function setFormatMoney(number, decPlaces, decSep, thouSep) {
        if (typeof number !== "undefined") {
            decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces;
            decSep = typeof decSep === "undefined" ? "." : decSep;
            thouSep = typeof thouSep === "undefined" ? "," : thouSep;
            let sign = number < 0 ? "-" : "";
            let i = String(parseInt(number = Math.abs(Number(number) || 0).toFixed(decPlaces)));
            let j = i.length;
            j = j > 3 ? j % 3 : 0;

            return sign +
                (j ? i.substr(0, j) + thouSep : "") +
                i.substr(j).replace(/(\decSep{3})(?=\decSep)/g, "$1" + thouSep) +
                (decPlaces ? decSep + Math.abs(number - i).toFixed(decPlaces).slice(2) : "");
        }
        else {
            return 0;
        }
    }

</script>
