
// ============================
// VIEW CUSTOMERS PER SEGMENT
// ============================
// ============================
// VIEW CUSTOMERS PER SEGMENT
// ============================
function viewSegmentCustomers(segment_name) {

    document.getElementById("segmentModalTitle").innerText =
        `Customers in "${segment_name}" Segment`;

    // ✅ SIMPLE: LAGI LANG SA ISANG ENDPOINT
    const url = "php/get/get_segment_customers.php?segment=" + encodeURIComponent(segment_name);

    fetch(url)
        .then(res => res.json())
        .then(response => {

            let tbody = document.getElementById("segmentCustomerTable");
            tbody.innerHTML = "";

            if (response.status === 1 && response.customers.length > 0) {
                response.customers.forEach(cust => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${cust.full_name}</td>
                            <td>${cust.age}</td>
                            <td>${cust.gender}</td>
                            <td>${cust.occupation}</td>
                            <td>${cust.education}</td>
                            <td>${cust.location}</td>
                            <td>${cust.email}</td>
                            <td>${cust.last_purchase ?? 'No purchase yet'}</td>
                            <td>₱${parseFloat(cust.total_spent || 0).toFixed(2)}</td>
                        </tr>
                    `;
                });
            } else {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="9" class="text-center">No customers found in this segment</td>
                    </tr>
                `;
            }

            new bootstrap.Modal(
                document.getElementById("viewSegmentCustomersModal")
            ).show();
        })
        .catch(err => console.error("Segment Customer Error:", err));
}













// ============================
// GET ESTABLISHMENT NAME
// ============================
document.addEventListener("DOMContentLoaded", function () {
    fetch("php/get/get_establishment_name.php")
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById("name1").textContent = data.business_name;
            }
        })
        .catch(err => console.error("Error fetching establishment:", err));
});


// ============================
// CREATE SEGMENT (SweetAlert2)
// ============================
document.getElementById("segmentForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const data = {
        segment_name: document.getElementById("segment_name").value,
        age_min: document.getElementById("age_min").value,
        age_max: document.getElementById("age_max").value,
        description: document.getElementById("description").value,
    };

    fetch("php/create/create_segment.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data)
    })
        .then(res => res.json())
        .then(response => {

            if (response.success) {
                Swal.fire({
                    icon: "success",
                    title: "Success!",
                    text: response.message || "Segment created successfully.",
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    const modal = bootstrap.Modal.getInstance(document.getElementById("createSegmentModal"));
                    modal.hide();
                    loadSegments();
                });

            } else {
                Swal.fire({
                    icon: "error",
                    title: "Failed!",
                    text: response.message || "Failed to create segment."
                });
            }
        })
        .catch(err => {
            console.error("Fetch error:", err);
            Swal.fire({
                icon: "error",
                title: "Error!",
                text: "Something went wrong. Please try again later."
            });
        });
});


// ============================
// LOAD SEGMENTS
// ============================
function loadSegments() {
    fetch("php/get/get_customer_segment.php")
        .then(res => res.json())
        .then(response => {
            console.log("Segments response:", response);

            if (response.status === 1) {
                const container = document.getElementById("segmentCards");
                container.innerHTML = "";

                let totalRevenue = response.segments.reduce(
                    (sum, seg) => sum + Number(seg.total_revenue || 0),
                    0
                );


                response.segments.forEach(seg => {
                    let revenuePercent = totalRevenue > 0
                        ? ((seg.total_revenue / totalRevenue) * 100).toFixed(1)
                        : 0;

                    let segmentHTML = `
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm rounded-3 segment-card"
             data-segment="${seg.name}"
             data-id="${seg.id}"
             style="cursor:pointer;">

            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    ${seg.name}
                    ${seg.criteria === "AUTO"
                    ? `<span class="badge bg-secondary ms-2">AUTO</span>`
                    : ``}

                </h5>

                ${seg.id != 0 ? `
                <button class="btn btn-sm btn-outline-primary delete-btn">
                    <i class="fas fa-trash"></i>
                </button>` : ``}
            </div>

            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="number">👥 ${seg.customer_count} customers</span>
                </div>

                <p class="card-text">${seg.description || "No description available."}</p>

                <ul class="list-unstyled small mb-3">
                    <li>👤 Avg Spend / Customer: ₱${parseFloat(seg.avg_spent_per_customer || 0).toFixed(2)}</li>
                </ul>

                <div class="progress mb-2">
                    <div class="progress-bar bg-primary"
                         role="progressbar"
                         style="width: ${revenuePercent}%">
                         ${revenuePercent}%
                    </div>
                </div>

                <small>${revenuePercent}% of total revenue (₱${parseFloat(seg.total_revenue || 0).toFixed(2)})</small>
            </div>
        </div>
    </div>
`;


                    container.innerHTML += segmentHTML;
                });

            } else {
                alert("⚠️ Failed to load segments: " + response.message);
            }
        })
        .catch(err => console.error("Fetch error:", err));
}



// AUTO LOAD SEGMENTS
loadSegments();


// ============================
// DELETE SEGMENT
// ============================
function deleteSegment(id) {

     if (id == 0) {
        Swal.fire("Protected", "This segment cannot be deleted.", "info");
        return;
    }
    
    Swal.fire({
        title: "Are you sure?",
        text: "You won’t be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes"
    }).then(result => {

        if (!result.isConfirmed) return;

        fetch("php/delete/delete_segment.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "id=" + encodeURIComponent(id)
        })
            .then(res => res.json())
            .then(response => {

                if (response.status === 1) {
                    Swal.fire({
                        icon: "success",
                        title: "Deleted!",
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => loadSegments());

                } else {
                    Swal.fire({ icon: "error", title: "Failed!", text: response.message });
                }
            })
            .catch(err => {
                Swal.fire({
                    icon: "error",
                    title: "Error!",
                    text: "Something went wrong while deleting."
                });
                console.error("Delete error:", err);
            });
    });
}


// ✅ SAFE CLICK HANDLER ONLY FOR SEGMENT CARDS
document.getElementById("segmentCards").addEventListener("click", function (e) {

    // ✅ DELETE BUTTON CLICK
    if (e.target.closest(".delete-btn")) {
        e.stopPropagation();
        const card = e.target.closest(".segment-card");
        const id = card.dataset.id;
        deleteSegment(id);
        return;
    }

    // ✅ CARD CLICK
    const card = e.target.closest(".segment-card");
    if (card) {
        const segmentName = card.dataset.segment;
        viewSegmentCustomers(segmentName);
    }
});
