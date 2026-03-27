

// Get establishment name and show in navbar
document.addEventListener("DOMContentLoaded", function () {
    fetch("php/get/get_establishment_name.php")
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const el = document.getElementById("name1");
                if (el) el.textContent = data.business_name;
            } else {
                console.warn(data.message);
            }
        })
        .catch(err => console.error("Error fetching establishment:", err));
});

// -------------------------------
// Utility: load segments into a select
// -------------------------------
function loadSegmentsDropdown(targetSelectId, selectedValue = "") {
    $.getJSON("php/get/get_dropdown_segments.php", function(response) {
        if (response.status === 1) {
            const $select = $("#" + targetSelectId);
            $select.empty().append('<option value="">Select type</option>');

            response.segments.forEach(seg => {
                const option = $('<option>', { value: seg.name, text: seg.name });
                if (seg.name === selectedValue) {
                    option.attr("selected", "selected");
                }
                $select.append(option);
            });
        } else {
            console.warn("Failed to load segments: " + (response.message || "unknown"));
            // keep a default "Select" option if failure
            $("#" + targetSelectId).empty().append('<option value="">Select</option>');
        }
    }).fail(function(xhr, status, err) {
        console.error("Error loading segments:", xhr.responseText || err);
        $("#" + targetSelectId).empty().append('<option value="">Select</option>');
    });
}

// -------------------------------
// Global filters & config
// -------------------------------
let activeFilters = {}; 
let currentPage = 1;
const limit = 10;

// -------------------------------
// Export to Excel (uses activeFilters)
// -------------------------------
$("#exportExcel").on("click", function() {
    if ($.isEmptyObject(activeFilters)) {
        Swal.fire({
            icon: "info",
            title: "No Filters Applied",
            text: "Please apply at least one filter before exporting to Excel.",
            confirmButtonColor: "#3085d6",
            confirmButtonText: "OK"
        });
        return;
    }

    let params = new URLSearchParams(activeFilters).toString();

    Swal.fire({
        title: "Export Filtered Data?",
        text: "Your filtered customer data will be downloaded as an Excel file.",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, export now"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "php/filter/filter_export_customer.php?type=excel&" + params;
        }
    });
});

// jQuery ready
$(function () {

    // Location options HTML (kept inline to match your original)
    const locationOptionsHTML = `
        <option value="">Select</option>
        <option>Alang-alang</option>
        <option>Amantacop</option>
        <option>Ando</option>
        <option>Balacdas</option>
        <option>Balud</option>
        <option>Banuyo</option>
        <option>Baras</option>
        <option>Bato</option>
        <option>Bayobay</option>
        <option>Benowangan</option>
        <option>Bugas</option>
        <option>Cabalagnan</option>
        <option>Cabong</option>
        <option>Cagbonga</option>
        <option>Calico-an</option>
        <option>Calingatnan</option>
        <option>Camada</option>
        <option>Campesao</option>
        <option>Can-abong</option>
        <option>Can-aga</option>
        <option>Canjaway</option>
        <option>Canlaray</option>
        <option>Canyopay</option>
        <option>Divinubo</option>
        <option>Hebacong</option>
        <option>Hindang</option>
        <option>Lalawigan</option>
        <option>Libuton</option>
        <option>Locso-on</option>
        <option>Maybacong</option>
        <option>Maypangdan</option>
        <option>Pepelitan</option>
        <option>Pinanag-an</option>
        <option>Punta Maria</option>
        <option>Purok A (Pob.)</option>
        <option>Purok B (Pob.)</option>
        <option>Purok C (Pob.)</option>
        <option>Purok D1 (Pob.)</option>
        <option>Purok D2 (Pob.)</option>
        <option>Purok E (Pob.)</option>
        <option>Purok F (Pob.)</option>
        <option>Purok G (Pob.)</option>
        <option>Purok H (Pob.)</option>
        <option>Sabang North</option>
        <option>Sabang South</option>
        <option>San Andres</option>
        <option>San Gabriel</option>
        <option>San Gregorio</option>
        <option>San Jose</option>
        <option>San Mateo</option>
        <option>San Pablo</option>
        <option>San Saturnino</option>
        <option>Santa Fe</option>
        <option>Siha</option>
        <option>Sohutan</option>
        <option>Songco</option>
        <option>Suribao</option>
        <option>Surok</option>
        <option>Taboc</option>
        <option>Tabunan</option>
        <option>Tamoso</option>
    `;

    // -------------------------------
    // Show appropriate filter input fields dynamically
    // -------------------------------
    $("#filterType").on("change", function () {
        let type = $(this).val();
        let container = $("#filterInputs");
        container.empty();

        // SEGMENT -> use dynamic dropdown (loads from server)
        if (type === "segment") {
            container.append(`
                <div class="mb-3">
                    <label class="form-label">Segment</label>
                    <select class="form-select" id="segment">
                        <option value="">Loading...</option>
                    </select>
                </div>
            `);

            // Populate the segment dropdown (reuses your working endpoint)
            loadSegmentsDropdown("segment");

            // When the filter segment select is cleared, clear results (same behavior as text input)
            $(document).off("change", "#segment").on("change", "#segment", function () {
                let v = $(this).val();
                if (!v) {
                    $("#allrecords tbody").empty();
                    $("#recordCount").text("");
                    $(".pagination").empty().hide();
                    activeFilters = {};
                }
            });

        }
        // LOCATION -> static dropdown (as you had)
        else if (type === "location") {
            container.append(`
                <div class="mb-3">
                    <label class="form-label">Select Location</label>
                    <select class="form-select" id="filterValue">
                        ${locationOptionsHTML}
                    </select>
                </div>
            `);

            // Keep parity with text input clearing: when user selects empty "Select", clear results
            $(document).off("change", "#filterValue").on("change", "#filterValue", function () {
                if ($(this).val() === "") {
                    $("#allrecords tbody").empty();
                    $("#recordCount").text("");
                    $(".pagination").empty().hide();
                    activeFilters = {};
                }
            });
        }
        // NAME -> text input (search by full_name)
        else if (type === "name") {
            container.append(`
                <div class="mb-3">
                    <label class="form-label">Enter ${type.charAt(0).toUpperCase() + type.slice(1)}</label>
                    <input type="text" class="form-control" id="filterValue">
                </div>
            `);

            // Clear behavior already handled below via input event
        }
        // AGE -> min/max inputs
        else if (type === "age") {
            container.append(`
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Minimum Age</label>
                        <input type="number" class="form-control" id="minAge">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Maximum Age</label>
                        <input type="number" class="form-control" id="maxAge">
                    </div>
                </div>
            `);
        }
    });

    // -------------------------------
    // Load table data with pagination
    // -------------------------------
    function loadClientData(page = 1, filters = {}) {
        $("#allrecords tbody").empty();

        $.ajax({
            type: "POST",
            url: "php/filter/filter_segment_customer.php",
            data: { page, limit, ...filters },
            success: function (response) {
                console.log("Server response:", response);

                let result;
                try {
                    result = typeof response === "string" ? JSON.parse(response) : response;
                } catch (e) {
                    console.error("Invalid JSON:", response);
                    return;
                }

                $("#allrecords tbody").empty();

                // If no records found
                if (result.status === 0) {
                    $("#allrecords tbody").append(
                        "<tr><td colspan='7' class='text-center'>" + result.message + "</td></tr>"
                    );
                    $("#recordCount").text("");
                    $(".pagination").empty().hide(); // hide pagination
                } else {
                    // Fill the table with data
                    $.each(result.data, function (index, client) {
                        $("#allrecords tbody").append(`
                            <tr>
                                <td>${client.full_name}</td>
                                <td>${client.age}</td>
                                <td>${client.gender}</td>
                                <td>${client.location}</td>
                                <td>${client.email}</td>
                                <td>${client.phone}</td>
                                
                            </tr>
                        `);
                    });

                    // Update record count
                    $("#recordCount").text(`Total Records: ${result.total || result.data.length}`);

                    // Build pagination
                    let totalPages = Math.ceil(result.total / limit);
                    let pagination = $(".pagination");
                    pagination.empty();

                    // Show pagination only if more than 1 page
                    if (totalPages > 1) {
                        pagination.show();

                        let prevClass = (page <= 1) ? "disabled" : "";
                        pagination.append("<li class='page-item " + prevClass + "'><a class='page-link' href='#' data-page='" + (page-1) + "'>Previous</a></li>");

                        for (let i = 1; i <= totalPages; i++) {
                            let active = (i === page) ? "active" : "";
                            pagination.append("<li class='page-item " + active + "'><a class='page-link' href='#' data-page='" + i + "'>" + i + "</a></li>");
                        }

                        let nextClass = (page >= totalPages) ? "disabled" : "";
                        pagination.append("<li class='page-item " + nextClass + "'><a class='page-link' href='#' data-page='" + (page+1) + "'>Next</a></li>");
                    } else {
                        pagination.hide(); // hide if only 1 page
                    }
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error: " + error);
                console.error(xhr.responseText);
            }
        });
    }

    // -------------------------------
    // When the user types or clears the text input, clear table/results
    // -------------------------------
    $(document).on("input", "#filterValue", function () {
        let value = $(this).val().trim();
        if (value === "") {
            // Clear table, count, pagination
            $("#allrecords tbody").empty();
            $("#recordCount").text("");
            $(".pagination").empty().hide();
            activeFilters = {};
        }
    });

    // -------------------------------
    // Apply filters on button click
    // -------------------------------
    $("#filterBtn").on("click", function () {
        let type = $("#filterType").val();
        let filters = {};

        // Collect filters
        if (type === "segment") {
            // segment dropdown id = #segment (populated by loadSegmentsDropdown)
            let selectedSeg = $("#segment").val();
            if (selectedSeg && selectedSeg.trim() !== "") {
                filters.segment = selectedSeg;
            }
        } else if (type === "location" || type === "name") {
            let value = $("#filterValue").val() ? $("#filterValue").val().trim() : "";
            if (value !== "") {
                filters[type] = value;
            }
        } else if (type === "age") {
            let minAge = $("#minAge").val() ? $("#minAge").val().trim() : "";
            let maxAge = $("#maxAge").val() ? $("#maxAge").val().trim() : "";
            if (minAge !== "" || maxAge !== "") {
                filters.minAge = minAge;
                filters.maxAge = maxAge;
            }
        }

        // ✅ Validation: No filters applied
        if ($.isEmptyObject(filters)) {
            Swal.fire({
                icon: "info",
                title: "No Filters Applied",
                text: "Please select a filter type and enter a value before applying.",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "OK"
            });
            return;
        }

        // Apply filters globally and load results
        activeFilters = filters;
        console.log("Filter applied:", activeFilters);
        loadClientData(1, activeFilters);
    });

    // -------------------------------
    // Pagination click event
    // -------------------------------
    $(document).on("click", ".pagination a.page-link", function (e) {
        e.preventDefault();
        const page = $(this).data("page");

        if (page && !$(this).parent().hasClass("disabled")) {
            currentPage = page;
            loadClientData(currentPage, activeFilters);
        }
    });

}); // end jQuery ready
