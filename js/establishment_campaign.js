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
  
  
  
  document.addEventListener("DOMContentLoaded", function () {
    fetch("php/get/get_dropdown_segments.php")
        .then(res => res.json())
        .then(data => {
            const select = document.getElementById("targetSegment");
            select.innerHTML = ""; // clear old options

            if (data.status === 1 && data.segments.length > 0) {
                // Add "All Customers" option
                const allOpt = document.createElement("option");
                allOpt.value = "all";
                allOpt.textContent = "All Customers";
                select.appendChild(allOpt);

                // Add segments from DB
                data.segments.forEach(seg => {
                    const opt = document.createElement("option");
                    opt.value = seg.name;       // 👈 ito dapat
                    opt.textContent = seg.name; // 👈 ito rin
                    select.appendChild(opt);
                });
            } else {
                const opt = document.createElement("option");
                opt.textContent = "No segments found";
                select.appendChild(opt);
            }
        })
        .catch(err => {
            console.error("Failed to load segments:", err);
            const select = document.getElementById("targetSegment");
            select.innerHTML = "<option>Error loading</option>";
        });
});






$("#campaignForm").submit(function (event) {
    event.preventDefault(); // Stop normal form submission

    // ✅ Get form values
    var campaignName = $("#campaignName").val();
    var targetSegment = $("#targetSegment").val();
    var channel = $("input[name='channel']:checked").val();
    var messageContent = $("#messageContent").val();
    var scheduleTime = $("#scheduleTime").val();
    var status = $("#status").val();

    // ✅ Validation check
    if (!campaignName || !targetSegment || !messageContent || !channel || !status) {
        Swal.fire({
            icon: "warning",
            title: "Incomplete Form",
            text: "Please fill out all required fields.",
            position: "center",
            customClass: {
                popup: 'small-alert',
                title: 'small-title',
                content: 'small-content',
                actions: 'small-actions'
            }
        });
        return;
    }

    // ✅ Send data via AJAX
    $.ajax({
        type: "POST",
        url: "php/create/create_campaign.php",
        dataType: "json",
        data: {
            campaignName: campaignName,
            targetSegment: targetSegment,
            channel: channel,
            messageContent: messageContent,
            scheduleTime: scheduleTime,
            status: status
        },
        success: function (result) {
            if (result.status == 1) {
                Swal.fire({
                    icon: "success",
                    title: "Success!",
                    text: result.message || "Campaign sent successfully.",
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
                    // ✅ Reset form & close modal
                    $("#campaignForm")[0].reset();
                    const modal = bootstrap.Modal.getInstance(
                        document.getElementById("createCampaignModal")
                    );
                    modal.hide();
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Failed!",
                    text: result.message || "Failed to send campaign.",
                    position: "center",
                    customClass: {
                        popup: 'small-alert',
                        title: 'small-title',
                        content: 'small-content',
                        actions: 'small-actions'
                    }
                });
            }
        },
        error: function (xhr, status, error) {
            Swal.fire({
                icon: "error",
                title: "Error!",
                text: "An error occurred: " + error,
                position: "center",
                customClass: {
                    popup: 'small-alert',
                    title: 'small-title',
                    content: 'small-content',
                    actions: 'small-actions'
                }
            });
        }
    });
});






 
// Delete campaign button
$(document).on("click", ".delete-btn", function() {
    let id = $(this).data("id");
    console.log("Deleting ID:", id); // For debugging

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
            popup: 'small-alert',
            title: 'small-title',
            content: 'small-content',
            actions: 'small-actions'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // 👉 Send AJAX request to delete PHP file
            $.post("php/delete/delete_campaign.php", { id: id }, function(response) {
                if (response.status == 1) {
                    Swal.fire({
                        icon: "success",
                        title: "Deleted!",
                        text: "Record deleted successfully.",
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
                        text: response.message || "Failed to delete campaign.",
                        position: "center",
                        customClass: {
                            popup: 'small-alert',
                            title: 'small-title',
                            content: 'small-content',
                            actions: 'small-actions'
                        }
                    });
                }
            }, "json").fail(function(err) {
                Swal.fire({
                    icon: "error",
                    title: "Error!",
                    text: "Something went wrong while deleting.",
                    position: "center",
                    customClass: {
                        popup: 'small-alert',
                        title: 'small-title',
                        content: 'small-content',
                        actions: 'small-actions'
                    }
                });
                console.error("Delete error:", err);
            });
        }
    });
});






   $(function () {
    function loadClientData() {
        $("#allrecords").find("tr.re-list").remove(); // Remove any existing records

        $.ajax({
            type: "POST",
            url: "php/retrieve/retrieve_campaign.php",  
            dataType: "json",  
            success: function(result) {
                if (result.status === 0) {
                    // If no records found, display a message
                    $("#allrecords tbody").append("<tr class='re-list'><td colspan='9' class='text-center'>" + result.message + "</td></tr>");
                } else {
                    // Loop through the result and append the data to the table
                    $.each(result.data, function(index, client) {
                        $("#allrecords tbody").append(
                            "<tr class='re-list'>" +
                            "<td>" + client.campaign_name + "</td>" +
                            "<td>" + client.target_segment + "</td>" +
                            "<td>" + client.channel + "</td>" +
                            "<td>" + client.sent_count + "</td>" +          // content/message
                            "<td>" + client.created_at + "</td>" +
                             "<td>" + client.schedule_time + "</td>" +
                            "<td>" + client.status + "</td>" +
                            "<td>" +
                                
                                "<button class='btn btn-sm btn-outline-danger delete-btn' data-id='" + client.campaign_sid + "'><i class='fas fa-trash'></i></button>" +
                            "</td>" +
                            "</tr>"
                        );
                    });
                }
            },
            error: function(xhr, status, error) {
                // Log any AJAX errors
                console.error("AJAX Error: " + error);
                $("#allrecords tbody").append("<tr class='re-list'><td colspan='9' class='text-center'>An error occurred while fetching the data.</td></tr>");
            }
        });
    }

    // Call the loadClientData function on page load
    loadClientData();
});