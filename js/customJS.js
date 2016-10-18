/* 
 * Copyright (C) 2016 spectral369
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */



/* global XMLSerializer, pdfMake */

var db_selected;
var table_selected;
var colSelected = [];
var colSelected2 = [];
var queryString = " ";

function removeOptions(selectbox)
{
    var i;
    for (i = selectbox.options.length - 1; i >= 0; i--)
    {
        selectbox.remove(i);
    }
}


$(document).ready(function () {
    $('#dbs').change(function () {
        $('#table').prop('disabled', false);
        var optionSelected = $("option:selected", this);
        var valueSelected = this.value;
        db_selected = valueSelected;
        var val = valueSelected;
        // $("#dbs option[value='remove']").remove(); remove "chose a database"
        removeOptions(document.getElementById("col2"));
        $('#col2').prop('disabled', true);
        $('#toxml').prop('disabled', true);
        $('#query').prop('disabled', true);
        $.ajax({
            url: "work/handlers/getTables.php",
            dataType: 'json',
            data: {val: val},
            success: function (result) {
                removeOptions(document.getElementById("table"));
                var min = 0,
                        max = 0;
                for (var obj in result) {
                    max = Object.keys(result).indexOf(obj);
                }
                select = document.getElementById('table');

                for (var i = min; i <= max; i++) {
                    var opt = document.createElement('option');
                    opt.value = result[i][0];
                    opt.innerHTML = result[i][0];
                    select.appendChild(opt);
                }
//testing
//var myDDL = $('myID');
//myDDL[0].selectedIndex = 0;
//

            }

        });

    });
});

$(document).ready(function () {
    $('#table').change(function () {
        $('#col2').prop('disabled', false);
        $('#toxml').prop('disabled', true);
        $('#query').prop('disabled', true);
        $('#topdf').prop('disabled', true);
        var optionSelected = $("option:selected", this);
        var valueSelected = this.value;
        var val2 = valueSelected;
        table_selected = valueSelected;
        $.ajax({
            url: "work/handlers/getCol.php",
            dataType: 'json',
            data: {db_selected: db_selected,
                val2: val2},
            success: function (result) {
                removeOptions(document.getElementById("col2"));
                var min = 0,
                        max = 0;
                for (var obj in result) {
                    max = Object.keys(result).indexOf(obj);
                }
                select = document.getElementById('col2');

                for (var i = min; i <= max; i++) {
                    var opt = document.createElement('option');
                    opt.value = result[i][0];
                    opt.innerHTML = result[i][0];
                    select.appendChild(opt);
                }


            }
        });
    });
});
var i = 0;
var isSel = 0;
$(document).ready(function () {
    $('#col2').click(function () {
        $('#col2 option:selected').each(function () {

            $('#query').prop('disabled', false);

            colSelected2 = [];
            colSelected[i] = $(this).val();
            i++;
        });
        colSelected2 = colSelected;
        i = 0;
        if (colSelected.length < 1) {
            isSel = 1;
        }
        colSelected = [];

    });
});
function strQuery(callback) {
    var querystr = $('#queryString').val();
    if (isSel !== 0) {
        var options = $('#col2 option');

        colSelected2 = $.map(options, function (option) {
            isSel = 0;
            return option.value;

        });

    }
    $.ajax({
        url: "work/handlers/getQBEString.php",
        // dataType: 'json',
        data: {querystr: querystr,
            table_selected: table_selected,
            colSelected2: colSelected2,
            db_selected: db_selected},
        success: function (result) {
            var t = result;
            var er = "-1";
            if (t.indexOf(er) !== -1 && t < 6) {
                $('#queryString').addClass("error");


            } else {
                queryString = result;
                $('#queryString').removeClass("error").addClass("querytxt");

            }
            callback();
        }
    });
}
//test 
var tabl;
//table query
function tabQuery(callback) {
    $('#topdf').prop('disabled', false);
    $('#toxml').prop('disabled', false);
    $('#loading').show();
    $("table").children().remove();
    var cols = 0;
    var rows = 0;
    var qs = queryString;
    $.ajax({
        url: "work/handlers/getQBEQuery.php",
        dataType: 'json',
        data: {qs: qs},
        success: function (result) {

            rows = result.length;
            tabl = result;


            for (var i = 0; i < colSelected2.length; i++) {
                $('table').append('<th>' + colSelected2[i] + '</th>');
            }

            for (var i = 0; i < rows; i++) {
                $('table').append('<tr></td></td></tr>');
                for (var j = 0; j < colSelected2.length; j++) {
                    $('table').find('tr').eq(i).append('<td>' + result[i][j] + '</td>');
                    $('table').find('tr').eq(i).find('td').eq(j).attr('data-row', result[i][j]).attr('data-col', result[i][j]);
                }
            }
            $('#loading').hide();
            //  callback(); here test
        }

    });


}

$(document).ready(function () {
    $('#toxml').click(function () {

        $.ajax({
            url: "work/handlers/toxml.php",
            dataType: "xml",
            data: {queryString: queryString,
                colSelected2: colSelected2},
            success: function (result) {

                download('query.xml', xml_to_string(result));
            }

        });
    });
});

function both() {
    strQuery(function () {
        tabQuery();
    });
}


//download model stackoverflow
function download(filename, text) {
    var element = document.createElement('a');
    element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
    element.setAttribute('download', filename);

    element.style.display = 'none';
    document.body.appendChild(element);

    element.click();

    document.body.removeChild(element);
}



//xml_to_string stackoverflow
function xml_to_string(xml_node)
{
    if (xml_node.xml)
        return xml_node.xml;
    else if (XMLSerializer)
    {
        var xml_serializer = new XMLSerializer();
        return xml_serializer.serializeToString(xml_node);
    } else
    {
        alert("ERROR: Extremely old browser");
        return "";
    }
}



//remove pass storage
var unloaded = false;
$(document).ready(function () {
    $(window).on('beforeunload', unload);
    $(window).on('unload', unload);
    function unload() {
        if (!unloaded) {
            $.ajax({
                url: "work/handlers/unload.php",
                success: function (result) {
                    unloaded = true;
                    echo(result);
                },
                timeout: 5000
            });
        }
    }
});


///wait ing for response/*
/*
 jQuery.ajaxSetup({
 beforeSend: function() {
 $('#loading').show();
 },
 complete: function(){
 $('#loading').hide();
 },
 success: function() {}
 });
 */
$(document).ready(function () {
    $('#topdf').click(function () {
        $("#dialog-message").dialog({
            modal: true,
            draggable: false,
            resizable: false,
            position: ['center', 'top'],
            show: 'blind',
            hide: 'blind',
            width: 400,
            dialogClass: 'ui-dialog-osx',
            buttons: {
                "Close": function () {
                    $(this).dialog("close");
                },
                "Ok": function () {
                    var pagesize = $('#pdf-page-size').val();
                    var orientation = $('#orientation').val();
                    var description = $('#description').val();
                    toPDF(pagesize, orientation, description);
                    $(this).dialog("close");
                }
            }
        });
    });
});


function toPDF(pageSize, orientation, description) {



    function buildTableBody(data, columns) {
        var body = [];

        body.push(columns);

        data.forEach(function (row) {
            var dataRow = [];

            columns.forEach(function (column) {
                dataRow.push(row[column].toString());
            });

            body.push(dataRow);
        });

        return body;
    }

    function table(data, columns) {
        return {
            table: {
                headerRows: 1,
                widths: '*',
                body: buildTableBody(data, columns)
            }
        };
    }
    var dd = {
        pageOrientation: '' + orientation + '',
        pageSize: '' + pageSize + '',
        content: [
            {text: 'PDF Report', style: 'anotherStyle'},
            {text: '' + description + '', style: 'anotherStyle2'},
            table(tabl, colSelected2)
        ],
        styles: {

            anotherStyle: {
                fontSize: 26,
                bold: true,
                alignment: 'center'
            },
            anotherStyle2: {
                fontSize: 16,
                alignment: 'center'
            }

        }
    };
    pdfMake.createPdf(dd).download('Report.pdf');
    //pdfMake.createPdf(dd).open();
}