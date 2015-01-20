function drawTable(data)
{
    for (var i = 0; i < data.length; i++) {
        drawRow(data[i]);
    }
    $("tbody td.color").click(function(e) {
        var current_color = $(this).text();
        var color_id = $(this).prev().text();
        var vote_cell = $(this).next();
        var total_votes = $("#datatable tfoot tr td.total-votes").text();

        $.ajax({
            url: '/Services/getVotes/'+color_id,
            type: "get",
            dataType: "json",
            success: function(data, textStatus, jqXHR) {
                vote_cell.text(data.votes);
                getTableTotals();
            }
        });
        ;
    });
}

function drawRow(data)
{
    var row = $("<tr />")
    $("#datatable tbody").append(row);
    row.append($("<td>" + data.id + "</td>"));
    row.append($("<td class='color'><span style='background-color:"+data.name+";'>&nbsp;&nbsp;</span>&nbsp;" + data.name + "</td>"));
    row.append($("<td class='totals'> - </td>"));
}

function getTableData()
{
    $.ajax({
        url: '/Services/getColors',
        type: "post",
        dataType: "json",
        success: function(data, textStatus, jqXHR) {
            drawTable(data);
        }
    });
}

function getTableTotals()
{
    var total = 0;
    $('#datatable tbody tr td.totals').each(function()
    {
        var value = parseInt($(this).text());
        if (!isNaN(value))
        {
            total += value;
        }
    });

    $('#datatable tfoot tr td.total-votes').text(total);
}

