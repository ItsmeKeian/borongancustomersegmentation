


// View button
$(document).on("click", ".view-btn", function() {
    let id = $(this).data("id");

    $.post("php/get/get_establishment.php", { id: id }, function(response) {
        if (response.status == 1) {
            let c = response.data;

            $("#viewDetails").html(`
                <table class="table table-bordered">
                    <tbody>
                        <tr ">
                            <th>Business Name</th>
                            <td>${c.business_name}</td>
                        </tr>
                        <tr >
                            <th>Business Type</th>
                            <td>${c.business_type}</td>
                        </tr>
                        <tr >
                            <th>Owner</th>
                            <td>${c.owners_name}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>${c.email}</td>
                        </tr>
                        <tr>
                            <th>Contact</th>
                            <td>${c.contact}</td>
                        </tr>
                        <tr >
                            <th>Date Created</th>
                            <td>${c.date_time}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>${c.address}</td>
                        </tr>
                        <tr>
                            <th>Password</th>
                            <td>${c.password}</td>
                        </tr>
                    </tbody>
                </table>
            `);

            $("#viewModal").modal("show");
        } else {
            alert(response.message);
        }
    }, "json");
});



// Edit button
// Load data into edit modal
$(document).on("click", ".edit-btn", function() {
    let id = $(this).data("id");

    $.post("php/get/get_establishment.php", { id: id }, function(response) {
        if (response.status == 1) {
            let c = response.data;
            $("#edit_id").val(c.establishment_sid);
            $("#edit_bname").val(c.business_name);
            $("#edit_btype").val(c.business_type);
            $("#edit_owner").val(c.owners_name);
            $("#edit_email").val(c.email);
            $("#edit_contact").val(c.contact);
            $("#edit_address").val(c.address);
             $("#edit_date").val(c.date_time);
            $("#edit_password").val(c.password);
            $("#edit_confirmpassword").val(c.confirmpassword);

            $("#editModal").modal("show");
        } else {
            alert(response.message);
        }
    }, "json");
});

// ✅ Save edited record
$("#edit_save").click(function() {
    $.post("php/update/update_establishment.php", {
        id: $("#edit_id").val(),
        business_name: $("#edit_bname").val(),
        business_type: $("#edit_btype").val(),
        owners_name: $("#edit_owner").val(),
        email: $("#edit_email").val(),
        contact: $("#edit_contact").val(),
        address: $("#edit_address").val(),
        date_time: $("#edit_date").val(),
        password: $("#edit_password").val(),
        confirmpassword: $("#edit_confirmpassword").val()
    }, function(response) {
        if (response.status == 1) {
            Swal.fire({
                icon: "success",
                title: "Updated!",
                text: "Record updated successfully.",
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
                title: "Update Failed!",
                text: response.message || "Unable to update record.",
                position: "center",
                confirmButtonColor: "#d33",
                customClass: {
                    popup: 'small-alert',
                    title: 'small-title',
                    content: 'small-content',
                    actions: 'small-actions'
                }
            });
        }
    }, "json").fail(function(xhr, status, error) {
        Swal.fire({
            icon: "error",
            title: "Error!",
            text: "An error occurred: " + error,
            confirmButtonColor: "#d33",
            position: "center",
            customClass: {
                popup: 'small-alert',
                title: 'small-title',
                content: 'small-content',
                actions: 'small-actions'
            }
        });
    });
});





// ✅ Delete Establishment
$(document).on("click", ".delete-btn", function() {
    let id = $(this).data("id");

    Swal.fire({
        title: "Are you sure?",
        text: "This establishment will be permanently deleted!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it",
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
            // 👉 send AJAX request to delete PHP file
            $.post("php/delete/delete_establishment.php", { id: id }, function(response) {
                if (response.status == 1) {
                    Swal.fire({
                        icon: "success",
                        title: "Deleted!",
                        text: "Establishment deleted successfully.",
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
                        text: response.message || "Unable to delete establishment.",
                        position: "center",
                        customClass: {
                            popup: 'small-alert',
                            title: 'small-title',
                            content: 'small-content',
                            actions: 'small-actions'
                        }
                    });
                }
            }, "json").fail(function(xhr, status, error) {
                Swal.fire({
                    icon: "error",
                    title: "Error!",
                    text: "An error occurred: " + error,
                    confirmButtonColor: "#d33",
                    position: "center",
                    customClass: {
                        popup: 'small-alert',
                        title: 'small-title',
                        content: 'small-content',
                        actions: 'small-actions'
                    }
                });
            });
        }
    });
});



 // ✅ Create Establishments
$("#save").click(function (event) {
    event.preventDefault(); // Prevent the form submission

    var a = $("#bname").val();
    var b = $("#btype").val();
    var c = $("#ownersname").val();
    var d = $("#email").val();
    var e = $("#contact").val();
    var f = $("#address").val();
    var g = $("#password").val();
    var h = $("#confirmpassword").val();
    var i = $("#date_time").val();

    if (a && b && c && d && e && f && g && h && i) {
        savedocument(a, b, c, d, e, f, g, h, i);
    } else {
        Swal.fire({
            icon: "warning",
            title: "Incomplete Fields!",
            text: "⚠️ Please complete all required fields before saving.",
            confirmButtonColor: "#d33",
            confirmButtonText: "Okay",
            position: "center"
        });
    }
});

function savedocument(a, b, c, d, e, f, g, h, i) {
    $.ajax({
        type: "POST",
        url: "php/create/create_establishment.php",
        dataType: "json",
        data: {
            a: a,
            b: b,
            c: c,
            d: d,
            e: e,
            f: f,
            g: g,
            h: h,
            i: i
        },
        success: function (result) {
            if (result.status == 1) {
                Swal.fire({
                    icon: "success",
                    title: "Success!",
                    text: result.message || "Establishment saved successfully!",
                    showConfirmButton: false,
                    timer: 1500,
                    position: "center"
                }).then(() => {
                    // clear form after save
                    $("#bname, #btype, #ownersname, #email, #contact, #address, #password, #confirmpassword, #date_time").val("");
                    location.reload(); // reload after success
                });
            } else if (result.status == 2) {
                Swal.fire({
                    icon: "error",
                    title: "Duplicate Record!",
                    text: result.message || "This establishment already exists.",
                    showConfirmButton: false,
                    timer: 1500,
                    position: "center"
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Failed!",
                    text: result.message || "Failed to submit establishment.",
                    showConfirmButton: false,
                    timer: 1500,
                    position: "center"
                });
            }
        },
        error: function (xhr, status, error) {
            Swal.fire({
                icon: "error",
                title: "Error!",
                text: "An error occurred: " + error,
                confirmButtonColor: "#d33",
                confirmButtonText: "Okay",
                position: "center"
            });
        }
    });
}


        //filter records
$(function () {
    let currentPage = 1;
    const limit = 10;

    function loadClientData(page = 1) {
        $("#allrecords tbody").empty();

        $.ajax({
            type: "POST",
            url: "php/retrieve/retrieve_establishments_rec.php",
            data: { page: page, limit: limit },
            dataType: "json",
            success: function(result) {
                if (result.status === 0 || result.data.length === 0) {
                    $("#allrecords tbody").append(
                        "<tr><td colspan='8' class='text-center'>" + (result.message || "No establishments found") + "</td></tr>"
                    );
                    $(".pagination").empty();
                    return;
                }

                //  Populate table
                $.each(result.data, function(index, client) {
                    $("#allrecords tbody").append(
                        "<tr class='re-list'>" +
                            "<td>" + client.business_name + "</td>" +
                            "<td>" + client.business_type + "</td>" +
                            "<td>" + client.owners_name + "</td>" +
                            "<td>" + client.email + "</td>" +
                            "<td>" + client.contact + "</td>" +
                            "<td>" + client.date_time + "</td>" +
                            "<td>" + client.address + "</td>" +
                            "<td>" + (client.computed_status === "Active" 
                                ? "<span class='badge bg-success'>Active</span>" 
                                : "<span class='badge bg-danger'>Inactive</span>") + "</td>" +
                            "<td>" +
                                "<button class='btn btn-sm btn-outline-primary view-btn' data-id='" + client.establishment_sid + "'><i class='fas fa-eye'></i></button> " +
                                "<button class='btn btn-sm btn-outline-success edit-btn' data-id='" + client.establishment_sid + "'><i class='fas fa-edit'></i></button> " +
                                "<button class='btn btn-sm btn-outline-danger delete-btn' data-id='" + client.establishment_sid + "'><i class='fas fa-trash'></i></button>" +
                            "</td>" +
                        "</tr>"
                    );
                });

                //  Build pagination
                let totalPages = Math.ceil(result.total / limit);
                let pagination = $(".pagination");
                pagination.empty();

                let prevClass = (page <= 1) ? "disabled" : "";
                pagination.append("<li class='page-item " + prevClass + "'><a class='page-link' href='#' data-page='" + (page-1) + "'>Previous</a></li>");

                let maxVisible = 5;
                let startPage = Math.max(1, page - Math.floor(maxVisible / 2));
                let endPage = Math.min(totalPages, startPage + maxVisible - 1);

                if (startPage > 1) {
                    pagination.append("<li class='page-item'><a class='page-link' href='#' data-page='1'>1</a></li>");
                    if (startPage > 2) {
                        pagination.append("<li class='page-item disabled'><span class='page-link'>...</span></li>");
                    }
                }

                for (let i = startPage; i <= endPage; i++) {
                    let active = (i === page) ? "active" : "";
                    pagination.append("<li class='page-item " + active + "'><a class='page-link' href='#' data-page='" + i + "'>" + i + "</a></li>");
                }

                if (endPage < totalPages) {
                    if (endPage < totalPages - 1) {
                        pagination.append("<li class='page-item disabled'><span class='page-link'>...</span></li>");
                    }
                    pagination.append("<li class='page-item'><a class='page-link' href='#' data-page='" + totalPages + "'>" + totalPages + "</a></li>");
                }

                let nextClass = (page >= totalPages) ? "disabled" : "";
                pagination.append("<li class='page-item " + nextClass + "'><a class='page-link' href='#' data-page='" + (page+1) + "'>Next</a></li>");
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error: " + error);
                $("#allrecords tbody").append("<tr><td colspan='8' class='text-center'>An error occurred while fetching the data.</td></tr>");
            }
        });
    }

    //  Initial load
    loadClientData(currentPage);

    // Pagination click
    $(document).on("click", ".pagination a", function(e) {
        e.preventDefault();
        let page = parseInt($(this).data("page"));
        if (!isNaN(page) && page > 0) {
            currentPage = page;
            loadClientData(currentPage);
        }
    });
});
