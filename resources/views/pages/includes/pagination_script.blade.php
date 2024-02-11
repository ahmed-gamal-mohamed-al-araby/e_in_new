$(document).ready(function () {

    $(document).on('click', '.pagination a', function (event) {
        event.preventDefault();
        const page = $(this).attr('href').split('page=')[1],
            length = $('[name="table_records_length"]').val(),
            searchContent = $('[name="search"]').val();

        fetch_data(page, length, searchContent);
    });

    $(document).on('change', '[name="table_records_length"]', function (event) {
        event.preventDefault();
        const searchContent = $('[name="search"]').val(),
            length = $('[name="table_records_length"]').val();
        fetch_data(1, length, searchContent);
    });

    $(document).on('click', '#search-content', function (event) {
        const searchContent = $('[name="search"]').val(),
            length = $('[name="table_records_length"]').val();
        fetch_data(1, length, searchContent);
    });

    function fetch_data(page, length = 5, searchContent = '') {
        $('.loader-container').fadeIn();
        $.ajax({
            url: `${fetchDataURL}?page=` + page + '&length=' + length +
                '&search_content=' + searchContent + '&page_type=' + pageType,
            success: function (data) {
                $('#table-data').html(data);
                $('.loader-container').fadeOut();
                // Sort table
                $.extend($.tablesorter.defaults, {
                    theme: 'materialize',
                });
                $(".sort-table").tablesorter();
            }
        });
    }
});
