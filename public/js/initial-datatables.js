function initializeDatatable(tableId, ajaxUrl,columns) {
    var columnsFix = genColumn(columns);
    return $('#' + tableId).DataTable({
        serverSide: true,
        ajax: ajaxUrl,
        order: [[0, 'desc']],
        columns: columnsFix
    });
}

function genColumn(columnNames) {
    var columns = [{
        title: "#",
        render: function(data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
        }
    }];

    columnNames.forEach(function(columnObj) {
        var columnName = columnObj.attr;
        var title = columnObj.title ? columnObj.title : columnName.split('_').map(function(word) {
            return word.charAt(0).toUpperCase() + word.slice(1);
        }).join(' ');

        var column = {
            data: columnName,
            title: title
        };
        columns.push(column);
    });

    return columns;
}

function initializeDatatableEvents(datatable,tableId) {
    $('[data-toggle="sidebar"]').on('click', function(e) {
        datatable.columns.adjust().draw();
        console.log("90909");
    });

    $('#'+tableId).on('click', '.edit', function(e) {
        e.preventDefault();
        let id = $(this).data("id");
        let url = window.location.href;
        window.location.href = url + "/" + id + "/edit";
    });

    $('#'+tableId).on('click', '.delete', function() {
        let id = $(this).data("id");
        console.log(window.location.href+"/"+id);
        Swal.fire({
            title: 'Delete This User!',
            text: "Are you sure you want to delete this user?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes!',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value },
                    type: "DELETE",
                    url: window.location.href + '/' + id,
                    success: function(data) {
                        datatable.draw();
                        Swal.fire({
                            icon: data.icon,
                            title: data.title,
                            text: data.message,
                        })
                    },
                    error: function(data) {
                        Swal.fire({
                            icon: data.responseJSON.icon,
                            title: data.responseJSON.title,
                            text: data.responseJSON.message,
                        })
                    }
                });
            }
        })
    });
}
