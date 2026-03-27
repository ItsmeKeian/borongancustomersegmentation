     function loadLogs(page = 1) {
                    fetch('php/retrieve/retrieve_logs.php?page=' + page)
                        .then(response => response.text())
                        .then(data => {
                            document.getElementById('logsTable').innerHTML = data;
                        })
                        .catch(error => console.error('Error fetching logs:', error));
                }

                // Auto load logs every 5 seconds
                setInterval(() => {
                    let currentPage = document.querySelector('.pagination .active a')?.innerText || 1;
                    loadLogs(currentPage);
                }, 30000);

                // Initial load
                loadLogs(1);

function loadLoginAttempts(page = 1) {
    $.ajax({
        url: "php/get/get_login_attempts.php?page=" + page,
        type: "GET",
        dataType: "json",
        success: function(response) {
            let tbody = $("#login_attemp");
            tbody.empty();

            if (response.status == 1 && response.data.length > 0) {
                response.data.forEach(function(row) {
                    let tr = `
                        <tr>
                            <td>${row.username}</td>
                            <td>${row.establishment_name}</td>
                            <td>${row.attempts}</td>
                            <td>${row.last_attempt}</td>
                        </tr>`;
                    tbody.append(tr);
                });

                //  Build pagination
                let pagination = `<nav><ul class="pagination justify-content-center">`;

                // Previous button
                let prevDisabled = (response.currentPage <= 1) ? "disabled" : "";
                pagination += `
                    <li class="page-item ${prevDisabled}">
                        <a class="page-link" href="#" onclick="loadLoginAttempts(${response.currentPage - 1})">Previous</a>
                    </li>`;

                // Show only 3 pages at a time
                let start = Math.max(1, response.currentPage - 1);
                let end = Math.min(response.totalPages, start + 2);
                if (end - start < 2) start = Math.max(1, end - 2);

                for (let i = start; i <= end; i++) {
                    let active = (i === response.currentPage) ? "active" : "";
                    pagination += `
                        <li class="page-item ${active}">
                            <a class="page-link" href="#" onclick="loadLoginAttempts(${i})">${i}</a>
                        </li>`;
                }

                // Next button
                let nextDisabled = (response.currentPage >= response.totalPages) ? "disabled" : "";
                pagination += `
                    <li class="page-item ${nextDisabled}">
                        <a class="page-link" href="#" onclick="loadLoginAttempts(${response.currentPage + 1})">Next</a>
                    </li>`;

                pagination += `</ul></nav>`;

                $("#login_pagination").html(pagination);

            } else {
                tbody.append(`<tr><td colspan="4" class="text-center">No login attempts found</td></tr>`);
                $("#login_pagination").html("");
            }
        },
        error: function(xhr, status, error) {
            console.error("Error loading login attempts:", error);
            $("#login_attemp").html(`<tr><td colspan="4" class="text-center text-danger">Error loading data</td></tr>`);
            $("#login_pagination").html("");
        }
    });
}

$(document).ready(function() {
    loadLoginAttempts();
    setInterval(() => {
        let currentPage = $(".pagination .active a").text() || 1;
        loadLoginAttempts(parseInt(currentPage));
    }, 300000); //  refresh every 30s
});

