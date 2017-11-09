
$(document).ready(function() {
    $('.button-to-report-ticket-list').click(function() {
        createTicketList('Lista de ingressos');
    });
    $('.button-to-report-report-lot').click(function() {
        createTicketList('Lotes de ingressos');
    });
    $('.button-to-report-report-seller').click(function() {
        createTicketList('Venda de ingresso por vendedor');
    });
    $('.button-to-report-report-day').click(function() {
        createTicketList('Venda de ingressos por dia');
    });
});

$('.datepicker').datepicker({
    format: 'dd-mm-yyyy',
    language: 'pt-BR'
});

function createTicketList(header){
    var htmlColumns = $('.columns-to-print');
    var columns = [];
    htmlColumns.each(function(index) {
        columns.push(htmlColumns[index].innerText);
    });
    var htmlRows = $('.row-to-print');
    var rows = [];
    htmlRows.each(function(index) {
        var item = [];
        var subitem = $(this).find('td');
        subitem.each(function (subindex) {
            item.push(subitem[subindex].innerText);
        });
        rows.push(item);
    });
    createFileFromTable(rows, columns, header);
}

function createFileFromTable(rows, columns, header) {
    var doc = new jsPDF('p', 'pt');
    doc.autoTable(columns, rows, {
        columnStyles: {
    	    id: {fillColor: 255}
        },
        margin: {top: 90, left: 40, right: 40},
        addPageContent: function(data) {
    	    doc.text(header, 40, 60);
        }
    });
    doc.save('relatorio.pdf');
}