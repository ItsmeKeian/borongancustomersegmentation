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


$(function () {
    let currentPage = 1;
    const limit = 10;
    let searchTimer = null;
    let activeRequest = null;
    let lastRequestId = 0; // unique identifier for latest request

    function loadClientData(page = 1, search = "") {
        // Cancel any ongoing request
        if (activeRequest) activeRequest.abort();

        const requestId = ++lastRequestId;

        $("#allrecords tbody").empty().append(
            "<tr><td colspan='12' class='text-center'>Loading...</td></tr>"
        );

        activeRequest = $.ajax({
            type: "POST",
            url: "php/retrieve/retrieve_customer_rec.php",
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
                    $("#recordCount").text("0 customers found");
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
                            <td>${client.location}</td>
                            <td>${client.email}</td>
                            <td>${client.phone}</td>
                            <td>${client.segment}</td>
                            <td>${client.occupation}</td>
                            <td>₱${client.estimated_income}</td>
                            <td>${client.education}</td>

                            <td>
                                <button class='btn btn-sm btn-outline-primary view-btn' data-id='${client.customer_sid}'><i class='fas fa-eye'></i></button>
                                <button class='btn btn-sm btn-outline-success edit-btn' data-id='${client.customer_sid}'><i class='fas fa-edit'></i></button>
                                <button class='btn btn-sm btn-outline-danger delete-btn' data-id='${client.customer_sid}'><i class='fas fa-trash'></i></button>
                            </td>
                        </tr>
                    `);
                });

                // Record count
                const start = (page - 1) * limit + 1;
                const end = start + result.data.length - 1;
                $("#recordCount").text(
                    `Showing ${start} - ${end} of ${result.total} customers`
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

                // Page numbers (max 5)
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
                }
            },
            complete: function () {
                activeRequest = null;
            },
        });
    }

    // Initial load
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
        }, 400);
    });
});









    //  Function to load segments dynamically
function loadSegmentsDropdown(targetSelectId, selectedValue = "") {
    $.getJSON("php/get/get_dropdown_segment_forcustomer.php", function(response) {
        if (response.status === 1) {
            const $select = $("#" + targetSelectId);
            $select.empty().append('<option value="Others">Select type</option>');

            response.segments.forEach(seg => {
                const option = $('<option>', { value: seg.name, text: seg.name });
                if (seg.name === selectedValue) {
                    option.attr("selected", "selected");
                }
                $select.append(option);
            });
        } else {
            alert("⚠️ Failed to load segments: " + response.message);
        }
    });
}

//  Add Modal – load dropdown when modal opens
$('#addCustomerModal').on('show.bs.modal', function() {
    loadSegmentsDropdown("segment");
});

//  Edit Modal – populate fields and load dropdown
// ✅ OPEN EDIT MODAL
$(document).on("click", ".edit-btn", function () {
    let id = $(this).data("id");

    $.post("php/get/get_edit_customer.php", { id: id }, function (response) {
        if (response.status == 1) {
            let c = response.data;

            $("#edit_id").val(c.customer_sid);
            $("#edit_fname").val(c.full_name);
            $("#edit_age").val(c.age);
            $("#edit_gender").val(c.gender);
            $("#edit_location").val(c.location);
            $("#edit_email").val(c.email);
            $("#edit_phone").val(c.phone);
            $("#edit_date_created").val(c.created_at_iso);

            // ✅ NEW FIELDS
            $("#edit_occupation").val(c.occupation);
            $("#edit_income").val(c.estimated_income);
            $("#edit_education").val(c.education);

            loadSegmentsDropdown("edit_segment", c.segment);

            $("#EditModal").modal("show");
        } else {
            Swal.fire("Error", response.message, "error");
        }
    }, "json");
});


// ✅ SAVE EDIT
$("#edit_save").click(function () {

    $.post("php/update/update_customer.php", {
        id: $("#edit_id").val(),
        full_name: $("#edit_fname").val(),
        age: $("#edit_age").val(),
        gender: $("#edit_gender").val(),
        location: $("#edit_location").val(),
        email: $("#edit_email").val(),
        phone: $("#edit_phone").val(),
        segment: $("#edit_segment").val(),
        created_at: $("#edit_date_created").val(),

        // ✅ NEW FIELDS
        occupation: $("#edit_occupation").val(),
        income: $("#edit_income").val(),
        education: $("#edit_education").val()

    }, function (response) {
        if (response.status == 1) {
            Swal.fire({
                icon: "success",
                title: "Updated!",
                text: "Customer updated successfully",
                timer: 1500,
                showConfirmButton: false
            }).then(() => location.reload());
        } else {
            Swal.fire("Update Failed", response.message, "error");
        }
    }, "json");
});


    





  //count customer records
          function countc() {
    $.ajax({
        type: "POST",
        url: "php/count/count_customer_rec.php", // correct spelling
        dataType: "json",
       
        success: function(result) {
            if (result.status == 1) {
                $("#recordCount").text(result.unid);
            }
        }
    });
}

$(window).on("load", function() {
    countc();
   
});



    //import files

$("#importFile").change(function(){
    let formData = new FormData($("#importForm")[0]);

    $.ajax({
        url: "php/import/import_customer.php",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "json", //  ensure JSON
        success: function(response){
            alert(response.message);
            if(response.status == 1){
                location.reload();
            }
        },
        error: function(xhr, status, error){
            alert("Upload failed: " + error);
        }
    });
});







    // View button
$(document).on("click", ".view-btn", function() {
    let id = $(this).data("id");

    $.post("php/get/get_customer.php", { id: id }, function(response) {
        if (response.status == 1) {
            let c = response.data;

           $("#viewDetails").html(`
<table class="table table-bordered">

<tr><th>Full Name</th><td>${c.full_name}</td></tr>
<tr><th>Age</th><td>${c.age}</td></tr>
<tr><th>Sex</th><td>${c.gender}</td></tr>
<tr><th>Location</th><td>${c.location}</td></tr>
<tr><th>Email</th><td>${c.email}</td></tr>
<tr><th>Phone</th><td>${c.phone}</td></tr>
<tr><th>Segment</th><td>${c.segment}</td></tr>
<tr><th>Occupation</th><td>${c.occupation}</td></tr>
<tr><th>Estimated Income</th><td>₱${c.estimated_income}</td></tr>
<tr><th>Education</th><td>${c.education}</td></tr>

<tr class="table-primary"><th colspan="2">Purchase Analytics</th></tr>
<tr><th>Purchase Count</th><td>${c.purchase_count ?? 0}</td></tr>
<tr><th>Total Spent</th><td>₱${c.total_spent ?? 0}</td></tr>
<tr><th>Last Purchase</th><td>${c.last_purchase || "N/A"}</td></tr>

<tr><th>Date Created</th><td>${c.created_at_formatted}</td></tr>

</table>
`);



            $("#viewModal").modal("show");
        } else {
            alert(response.message);
        }
    }, "json");
});





// Delete button
$(document).on("click", ".delete-btn", function() {
    let id = $(this).data("id");
     Swal.fire({
        title: "Are you sure?",
        text: "You won’t be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes",
        cancelButtonText: "Cancel",
        position: "center",
        customClass: {
            popup: 'small-alert',  // Add custom class for smaller alert
            title: 'small-title',  // Add custom class for smaller title
            content: 'small-content',  // Add custom class for smaller content
            actions: 'small-actions'  // Add custom class for smaller actions
        }
    }).then((result) => {
     if (result.isConfirmed) {
            // 👉 send AJAX request to delete PHP file
            $.post("php/delete/delete_customer.php", { id: id }, function(response) {
                if (response.status == 1) {
                    Swal.fire({
                        icon: "success",
                        title: "Deleted!",
                        text: "Record deleted successfully",
                        showConfirmButton: false,
                        timer: 1500,
                        position: "center",
                        customClass: {
                            popup: 'small-alert',
                            title: 'small-title',
                            content: 'small-content',
                            actions: 'small-actions'
                        }
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Failed!",
                        text: response.message,
                        position: "center",
                        customClass: {
                            popup: 'small-alert',
                            title: 'small-title',
                            content: 'small-content',
                            actions: 'small-actions'
                        }
                    });
                }
            }, "json");
        }
    });
});







         //Create Customer

      $("#save").click(function (event) {
    event.preventDefault();

    var a = $("#fname").val().trim();
    var b = $("#age").val().trim();
    var c = $("#gender").val();
    var d = $("#location").val();
    var e = $("#email").val().trim();
    var f = $("#phone").val().trim();
    var g = $("#segment").val();
    var h = $("#occupation").val().trim();  
    var j = $("#income").val().trim();       
    var k = $("#education").val();    
    var i = $("#date_created").val();

    if (
        !a || !b || !c || !d || !e || !f || !h || !j || !k || !i
    ) {
        Swal.fire({
            icon: "warning",
            title: "Incomplete!",
            text: "Please fill in all fields before saving."
        });
        return;
    }

    savedocument(a, b, c, d, e, f, g, h, j, k, i);
});


function savedocument(a, b, c, d, e, f, g, h, j, k, i) {
    $.ajax({
        type: "POST",
        url: "php/create/create_customer.php",
        dataType: "json",
        data: {
            a, b, c, d, e, f, g, h, j, k, i
        },
        success: function (result) {
            if (result.status == 1) {
                Swal.fire({
                    icon: "success",
                    title: "Success!",
                    text: "Customer created successfully",
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Save Failed",
                    text: result.message
                });
            }
        },
        error: function (xhr) {
            console.error(xhr.responseText);
            Swal.fire("Server Error", "Check console for details", "error");
        }
    });
}

