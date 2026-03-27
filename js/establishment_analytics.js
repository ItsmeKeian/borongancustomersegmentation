
     //get the establishment name
        document.addEventListener("DOMContentLoaded", function () {
    fetch("php/get/get_establishment_name.php")
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById("name1").textContent = data.business_name;
            } else {
                console.warn(data.message);
            }
        })
        .catch(err => console.error("Error fetching establishment:", err));
});




document.getElementById('reportForm').addEventListener('click', function () {
    const reportType = document.getElementById('reportType').value;
    const dateRange = document.getElementById('dateRange').value;

    // ✅ Check if a report type is selected
    if (!reportType) {
        alert("⚠️ Please select a report type before generating the report.");
        return;
    }

    // ✅ Check if a date range is provided (optional but recommended)
    if (!dateRange) {
        const confirmGenerate = confirm("No date range selected. Do you want to generate a full report?");
        if (!confirmGenerate) {
            alert("❌ Report generation canceled.");
            return;
        }
    }

    // ✅ Show confirmation before generating
    const confirmGenerate = confirm("📄 Generate " + reportType + " report now?");
    if (!confirmGenerate) {
        alert("❌ Report generation canceled.");
        return;
    }

    // ✅ Create a hidden form for submission
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'php/generate/generate_reports.php';

    const typeInput = document.createElement('input');
    typeInput.type = 'hidden';
    typeInput.name = 'reportType';
    typeInput.value = reportType;

    const rangeInput = document.createElement('input');
    rangeInput.type = 'hidden';
    rangeInput.name = 'dateRange';
    rangeInput.value = dateRange;

    form.appendChild(typeInput);
    form.appendChild(rangeInput);
    document.body.appendChild(form);

    // ✅ Alert success and submit
    alert("✅ Generating report... Please wait while your " + reportType + " report is being prepared.");
    form.submit();
});





        // Export to Excel
$("#exportExcel").on("click", function () {
    let search = $("#searchInput").val().trim();

    Swal.fire({
        title: "Export Records?",
        text: search 
            ? "This will download the records based on your current search results."
            : "This will download all records currently displayed in the table.",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, export now",
        cancelButtonText: "Cancel",
        position: "center",
        customClass: {
            popup: 'small-alert',
            title: 'small-title',
            content: 'small-content',
            actions: 'small-actions'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: "Preparing Export...",
                text: "Please wait while your Excel file is being generated.",
                icon: "info",
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();

                    // 🟢 Export all or filtered records
                    setTimeout(() => {
                        window.open("php/export/export_reports.php?search=" + encodeURIComponent(search), "_blank");
                        Swal.close();
                    }, 1000);
                }
            });
        }
    });
});


$(function () {
    let currentPage = 1;
    const limit = 10;
    let searchTimer = null;
    let activeRequest = null;
    let lastRequestId = 0;

    function loadClientData(page = 1, search = "") {
        // Cancel ongoing request
        if (activeRequest) activeRequest.abort();

        const requestId = ++lastRequestId;

        $("#allrecords tbody").html(
            "<tr><td colspan='12' class='text-center'>Loading...</td></tr>"
        );

        activeRequest = $.ajax({
            type: "POST",
            url: "php/retrieve/retrieve_reports.php",
            data: { page: page, limit: limit, search: search },
            dataType: "json",
            success: function (result) {
                // Ignore outdated response
                if (requestId !== lastRequestId) return;

                $("#allrecords tbody").empty();

                if (result.status === 0 || !result.data || result.data.length === 0) {
                    $("#allrecords tbody").append(
                        "<tr><td colspan='12' class='text-center'>" +
                            (result.message || "No records found") +
                            "</td></tr>"
                    );
                    $("#recordCount").text("0 entries found");
                    $(".pagination").empty();
                    return;
                }

                // Populate table
                $.each(result.data, function (index, client) {
                    $("#allrecords tbody").append(`
                        <tr>
                            <td>${client.full_name}</td>
                            <td>${client.age}</td>
                            <td>${client.gender}</td>
                            <td>${client.phone}</td>
                            <td>${client.email}</td>
                            <td>${client.location}</td>
                            <td>${client.item_purchase}</td>
                            <td>${client.item_price}</td>
                            <td>${client.quantity}</td>
                            <td>${client.total}</td>
                            <td>${client.date_purchase}</td>
                        </tr>
                    `);
                });

                // Count text
                const start = (page - 1) * limit + 1;
                const end = start + result.data.length - 1;
                $("#recordCount").text(
                    `Showing ${start} - ${end} of ${result.total} entries`
                );

                // Pagination
                const totalPages = Math.ceil(result.total / limit);
                const pagination = $(".pagination");
                pagination.empty();

                // Prev
                pagination.append(`
                    <li class="page-item ${page <= 1 ? "disabled" : ""}">
                        <a class="page-link" href="#" data-page="${page - 1}">Previous</a>
                    </li>
                `);

                // Page numbers (max 5 visible)
                const maxVisible = 5;
                const startPage = Math.max(1, page - Math.floor(maxVisible / 2));
                const endPage = Math.min(totalPages, startPage + maxVisible - 1);

                for (let i = startPage; i <= endPage; i++) {
                    pagination.append(`
                        <li class="page-item ${i === page ? "active" : ""}">
                            <a class="page-link" href="#" data-page="${i}">${i}</a>
                        </li>
                    `);
                }

                // Next
                pagination.append(`
                    <li class="page-item ${page >= totalPages ? "disabled" : ""}">
                        <a class="page-link" href="#" data-page="${page + 1}">Next</a>
                    </li>
                `);
            },
            error: function (xhr, status, error) {
                if (status !== "abort") {
                    console.error("AJAX Error:", error);
                    $("#allrecords tbody").html(
                        "<tr><td colspan='12' class='text-center'>Error loading data.</td></tr>"
                    );
                    $("#recordCount").text("0 entries found");
                }
            },
            complete: function () {
                activeRequest = null;
            },
        });
    }

    // Default load
    loadClientData(currentPage);

    // Pagination click
    $(document).on("click", ".pagination a", function (e) {
        e.preventDefault();
        const page = parseInt($(this).data("page"));
        const search = $("#searchInput").val().trim();
        if (!isNaN(page) && page > 0) {
            currentPage = page;
            loadClientData(currentPage, search);
        }
    });

    // Debounced search
    $("#searchInput").on("input", function () {
        const keyword = $(this).val().trim();
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            currentPage = 1;
            loadClientData(currentPage, keyword);
        }, 400); // 🔹 debounce delay
    });
});


