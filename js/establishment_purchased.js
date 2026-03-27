




// ✅ LOAD CUSTOMER DROPDOWN (SEARCHABLE LIKE ITEM)
function loadCustomerDropdown() {
  $.getJSON("php/get/get_customername_dropdown.php", function (res) {
    let options = "";
    res.forEach(c => {
      options += `<option value="${c.full_name}" data-id="${c.customer_sid}"></option>`;
    });
    $("#customerList").html(options);
  });
}

// ✅ AUTO LOAD PAG BUKAS NG MODAL
$("#addCustomerModal").on("shown.bs.modal", function () {
  loadCustomerDropdown();
});







$("#item_purchase").on("change", function () {
    let price = $(this).find(":selected").data("price") || 0;
    $("#item_price").val(price);
    $("#quantity").val("");
    $("#total").val("");
});

$("#quantity, #item_price").on("input", function () {
    let qty = parseFloat($("#quantity").val()) || 0;
    let price = parseFloat($("#item_price").val()) || 0;
    $("#total").val(qty * price);
});



function loadInventoryDropdown() {
    $.post("php/inventory/retrieve_inventory.php", function (res) {
        let options = `<option value="">Select Item</option>`;
        res.forEach(item => {
            if (item.quantity > 0) {
                options += `<option value="${item.item_name}" data-price="${item.price}" data-stock="${item.quantity}">
                    ${item.item_name} (Stock: ${item.quantity})
                </option>`;
            }
        });
        $("#item_purchase").html(options);
    }, "json");
}

     
     
    loadInventoryDropdown();



     
     
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
    let lastRequestId = 0; // unique token for each request

    function loadClientData(page = 1, search = "") {
        // Cancel any running AJAX call
        if (activeRequest) activeRequest.abort();

        const requestId = ++lastRequestId; // generate new token
        $("#allrecords tbody").empty();
        $("#allrecords tbody").append("<tr><td colspan='10' class='text-center'>Loading...</td></tr>");

        activeRequest = $.ajax({
            type: "POST",
            url: "php/retrieve/retrieve_purchased_rec.php",
            data: { page: page, limit: limit, search: search },
            dataType: "json",
            success: function (result) {
                // Ignore outdated results
                if (requestId !== lastRequestId) return;

                $("#allrecords tbody").empty();

                if (result.status === 0 || !result.data || result.data.length === 0) {
                    $("#allrecords tbody").append(
                        `<tr><td colspan='10' class='text-center'>${result.message || "No records found"}</td></tr>`
                    );
                    $("#recordCount").text("0 entries found");
                    $(".pagination").empty();
                    return;
                }

                // Render table
                $.each(result.data, function (index, client) {
                    $("#allrecords tbody").append(`
                    <tr>
                        <td>${client.full_name}</td>
                        <td>${client.items || "—"}</td>
                        <td>${client.date_purchase}</td>
                        <td>${client.total}</td>
                        <td>
                        <button class='btn btn-sm btn-outline-primary view-btn' data-id='${client.purchased_sid}'><i class='fas fa-eye'></i></button>
                        <button class='btn btn-sm btn-outline-danger delete-btn' data-id='${client.purchased_sid}'><i class='fas fa-trash'></i></button>
                        </td>
                    </tr>
                    `);

                });

                // Record count
                let start = (page - 1) * limit + 1;
                let end = start + result.data.length - 1;
                $("#recordCount").text(`Showing ${start} - ${end} of ${result.total} entries`);

                // Pagination
                let totalPages = Math.ceil(result.total / limit);
                let pagination = $(".pagination");
                pagination.empty();

                // Prev
                pagination.append(`
                    <li class="page-item ${page <= 1 ? "disabled" : ""}">
                        <a class="page-link" href="#" data-page="${page - 1}">Previous</a>
                    </li>
                `);

                // Pages (max 5)
                let maxPagesToShow = 5;
                let startPage = Math.max(1, page - Math.floor(maxPagesToShow / 2));
                let endPage = Math.min(totalPages, startPage + maxPagesToShow - 1);

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
                    $("#allrecords tbody").html("<tr><td colspan='10' class='text-center'>Error loading data.</td></tr>");
                }
            },
            complete: function () {
                activeRequest = null;
            }
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
        }, 400); // 400ms debounce
    });
});





    //import files

$("#importFile").change(function(){
    let formData = new FormData($("#importForm")[0]);

    $.ajax({
        url: "php/import/import_purchased.php",
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






//view button
$(document).on("click", ".view-btn", function() {
  let id = $(this).data("id");

  $.post("php/get/get_purchased.php", { id }, function(res) {

    if (res.status !== 1) {
      alert(res.message);
      return;
    }

    let r = res.receipt;
    let rows = "";
    let grand = 0;

    res.items.forEach(i => {
      grand += parseFloat(i.subtotal);
      rows += `
        <tr>
          <td>${i.item_name}</td>
          <td>${i.price}</td>
          <td>${i.quantity}</td>
          <td>${i.subtotal}</td>
        </tr>
      `;
    });

    $("#viewDetails").html(`
      <h5>Customer: ${r.full_name}</h5>
      <p>Date: ${r.date_purchase}</p>

      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Item</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody>${rows}</tbody>
      </table>

      <h5 class="text-end">Grand Total: ₱${grand}</h5>
    `);

    $("#viewModal").modal("show");

  }, "json");
});



// Count purchased records
   function countc() {
    $.ajax({
        type: "POST",
        url: "php/count/count_purchased.php", // correct spelling
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


// Edit button
// Load data into edit modal
$(document).on("click", ".edit-btn", function() {
    let id = $(this).data("id");

    $.post("php/get/get_purchased.php", { id: id }, function(response) {
        if (response.status == 1) {
            let c = response.data;
            $("#edit_id").val(c.purchased_sid);
            $("#edit_fname").val(c.full_name);
            $("#edit_item_purchase").val(c.item_purchase);
            $("#edit_item_price").val(c.item_price);
            $("#edit_date_purchase").val(c.created_at_iso);
            $("#edit_quantity").val(c.quantity);
            $("#edit_total").val(c.total);
            $("#edit_establishment").val(c.establishment);

            $("#EditModal").modal("show");
        } else {
            alert(response.message);
        }
    }, "json");
});

// Save edited record
$("#edit_save").click(function() {
    $.post("php/update/update_purchased.php", {
        id: $("#edit_id").val(),
        full_name: $("#edit_fname").val(),
        item_purchase: $("#edit_item_purchase").val(),
        item_price: $("#edit_item_price").val(),
        date_purchase: $("#edit_date_purchase").val(),
        quantity: $("#edit_quantity").val(),
        total: $("#edit_total").val(),
        establishment: $("#edit_establishment").val()
    }, function(response) {
        if (response.status == 1) {
            Swal.fire({
                icon: "success",
                title: "Success!",
                text: "Record updated successfully",
                showConfirmButton: false,
                timer: 2000,
                position: "center"
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: "error",
                title: "Failed!",
                text: response.message,
                position: "center"
            });
        }
    }, "json");
});



//Failed: Database error: SQLSTATE[HY093]: Invalid parameter number: parameter was not defined

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
            $.post("php/delete/delete_purchased.php", { id: id }, function(response) {
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






$("#quantity, #item_price").on("input", function () {
    let qty = parseFloat($("#quantity").val()) || 0;
    let price = parseFloat($("#item_price").val()) || 0;
    $("#total").val(qty * price);
});

       
        //Create Purchase

   let cart = [];

$(function () {

  // ✅ LOAD INVENTORY TO DROPDOWN
  loadInventoryToSelect();

  function loadInventoryToSelect() {
    $.post("php/inventory/retrieve_inventory.php", function (res) {
      let options = `<option value="">-- Select Item --</option>`;
      res.forEach(i => {
        options += `
          <option 
            value="${i.inventory_id}" 
            data-name="${i.item_name}" 
            data-price="${i.price}" 
            data-stock="${i.quantity}">
            ${i.item_name} (Stock: ${i.quantity})
          </option>
        `;
      });
      $("#item_select").html(options);
    }, "json");
  }

  // ✅ AUTO FILL PRICE & STOCK
  $("#item_select").change(function () {
    let opt = $(this).find(":selected");
    $("#item_price").val(opt.data("price") || "");
    $("#item_stock").val(opt.data("stock") || "");
  });

  // ✅ ADD TO CART
  $("#addToCart").click(function () {
    let inventory_id = $("#item_select").val();
    let item_name = $("#item_select option:selected").data("name");
    let price = parseFloat($("#item_price").val());
    let stock = parseInt($("#item_stock").val());
    let quantity = parseInt($("#quantity").val());

    if (!inventory_id || !quantity || quantity <= 0) {
      Swal.fire("Invalid", "Please select item & valid quantity", "warning");
      return;
    }

    if (quantity > stock) {
      Swal.fire("Stock Error", "Not enough stock", "error");
      return;
    }

    let subtotal = price * quantity;

    cart.push({
      inventory_id,
      item_name,
      price,
      quantity,
      subtotal
    });

    renderCart();

    // reset qty only
    $("#quantity").val("");
  });

  // ✅ RENDER CART TABLE
  function renderCart() {
    let rows = "";
    let grandTotal = 0;

    cart.forEach((i, index) => {
      grandTotal += parseFloat(i.subtotal);

      rows += `
        <tr>
          <td>${i.item_name}</td>
          <td>${i.price}</td>
          <td>${i.quantity}</td>
          <td>${i.subtotal}</td>
          <td>
            <button class="btn btn-danger btn-sm" onclick="removeItem(${index})">X</button>
          </td>
        </tr>
      `;
    });

    $("#cartTable").html(rows);

    $("#grandTotal").text(grandTotal.toFixed(2));
  }

  // ✅ REMOVE ITEM
  window.removeItem = function (index) {
    cart.splice(index, 1);
    renderCart();
  };

  // ✅ CHECKOUT
  $("#checkout").click(function () {

    let fname = $("#fname").val();
    let date_purchase = $("#date_purchase").val();

    if (!fname || !date_purchase) {
      Swal.fire("Missing", "Customer & date required", "warning");
      return;
    }

    if (cart.length === 0) {
      Swal.fire("Empty", "Cart is empty", "warning");
      return;
    }

    $.ajax({
      type: "POST",
      url: "php/pos/checkout.php",
      dataType: "json",
      data: {
        fname: fname,
        date_purchase: date_purchase,
        cart: JSON.stringify(cart)
      },
      success: function (res) {
        if (res.status == 1) {
          Swal.fire("Success!", "Transaction completed", "success")
            .then(() => location.reload());
        } else {
          Swal.fire("Error", res.message, "error");
        }
      }
    });
  });

});
