<!DOCTYPE html>
<html>

<head></head>

<body>
    <input type="text" id="searchInput" placeholder="Enter search term...">
    <div id="searchResult"></div>
</body>

<?php include('partials/app-scripts.php'); ?>
<script>
    var typingTimer;                // Timer identifier
    var doneTypingInterval = 500;   // Time in ms (.5 seconds interval)

    document.addEventListener('keyup', function (ev) {
        const targetElement = ev.target;

        if (targetElement.id === "searchInput") {
            const searchTerm = targetElement.value;

            // Use clearTimeout to stop running setTimeout
            clearTimeout(typingTimer);

            // Set timeout
            typingTimer = setTimeout(function () {
                searchDb(searchTerm);
            }, doneTypingInterval);
        }
    });

    function searchDb(searchTerm) {
        const searchResultElement = document.getElementById("searchResult");

        if (searchTerm.length > 0) {
            searchResultElement.style.display = 'block';
            
            $.ajax({
                type: 'GET',
                data: { search_term: searchTerm },
                url: 'database/live-search.php',
                success: function (response) {
                    if (response.length === 0) {
                        searchResultElement.innerHTML = 'no data found';
                    } else {
                        let html = '';
                        for (const [tableName, rows] of Object.entries(response.data)) {
                            rows.forEach(row => {
                                let text = '';
                                let url = '';
                                if (tableName === 'UserLoginInformation') {
                                    text = row.first_name + ' ' + row.last_name;
                                    url = './users-view.php';
                                } else if (tableName === 'products') {
                                    text = row.product_name;
                                    url = './product-view.php';
                                } else if (tableName === 'suppliers') {
                                    text = row.supplier_name;
                                    url = './supplier-view.php';
                                }

                                html += '<a href="' + url + '">' + text + '</a><br/>';
                            });
                            searchResultElement.innerHTML = html;
                        }
                    }
                },
                dataType: 'json'
            });
        } else {
            searchResultElement.style.display = 'none';
        }
    }
</script>

</html>