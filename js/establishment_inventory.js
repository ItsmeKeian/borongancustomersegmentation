$(function () {

    loadInventory();

    // ============================
    // LOAD INVENTORY
    // ============================
    function loadInventory() {
        $.post("php/inventory/retrieve_inventory.php", function (res) {

            if (!Array.isArray(res) || res.length === 0) {
                $("#inventoryTable tbody").html(`
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            No inventory items found
                        </td>
                    </tr>
                `);
                return;
            }

            let rows = "";

            res.forEach(i => {

                // ✅ Row color logic
                let stockClass = "";
                if (i.quantity <= 0) {
                    stockClass = "table-danger";
                } else if (i.quantity <= 5) {
                    stockClass = "table-warning";
                }

                // ✅ Yesterday stock display
                let yesterdayStock = i.yesterday_quantity !== null
                    ? i.yesterday_quantity
                    : "—";

                rows += `
                    <tr class="${stockClass}">
                        <td>${i.item_name}</td>
                        <td>₱${parseFloat(i.price).toFixed(2)}</td>
                        <td>${yesterdayStock}</td>
                        <td>${i.quantity}</td>
                        <td>${i.status}</td>
                        <td>
                            <button 
                                class="btn btn-sm btn-outline-success edit-btn"
                                data-id="${i.inventory_id}"
                                data-name="${i.item_name}"
                                data-price="${i.price}"
                                data-qty="${i.quantity}">
                                <i class="fas fa-edit"></i>
                            </button>

                            <button 
                                class="btn btn-sm btn-outline-warning restock-btn"
                                data-id="${i.inventory_id}">
                                <i class="fas fa-box-open"></i>
                            </button>

                            <button 
                                class="btn btn-sm btn-outline-danger delete-btn"
                                data-id="${i.inventory_id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });

            $("#inventoryTable tbody").html(rows);

        }, "json");
    }

    // ============================
    // ADD ITEM MODAL
    // ============================
    $("#addItemBtn").click(function () {
        $("#modalTitle").text("Add Inventory Item");
        $("#inv_id, #item_name, #price, #quantity, #restock").val("");
        $("#inventoryModal").modal("show");
    });

    // ============================
    // SAVE / UPDATE ITEM
    // ============================
    $("#saveItem").click(function () {

        let id = $("#inv_id").val();
        let url = id
            ? "php/inventory/update_inventory.php"
            : "php/inventory/create_inventory.php";

        $.post(url, {
            id: id,
            item_name: $("#item_name").val(),
            price: $("#price").val(),
            quantity: $("#quantity").val(),
            restock: $("#restock").val()
        }, function (res) {

            if (res.status == 1) {
                Swal.fire("Saved!", "Inventory updated successfully.", "success");
                $("#inventoryModal").modal("hide");
                $("#inv_id, #item_name, #price, #quantity, #restock").val("");
                loadInventory();
            } else {
                Swal.fire("Error", res.message || "Something went wrong.", "error");
            }

        }, "json");
    });

    // ============================
    // EDIT ITEM
    // ============================
    $(document).on("click", ".edit-btn", function () {

        $("#modalTitle").text("Edit Inventory Item");

        $("#inv_id").val($(this).data("id"));
        $("#item_name").val($(this).data("name"));
        $("#price").val($(this).data("price"));
        $("#quantity").val($(this).data("qty"));
        $("#restock").val(0);

        $("#inventoryModal").modal("show");
    });

    // ============================
    // QUICK RESTOCK
    // ============================
    $(document).on("click", ".restock-btn", function () {

        let id = $(this).data("id");

        Swal.fire({
            title: "Restock Item",
            input: "number",
            inputAttributes: { min: 1 },
            showCancelButton: true,
            confirmButtonText: "Add Stock",
            inputValidator: value => {
                if (!value || value <= 0) {
                    return "Enter a valid stock amount.";
                }
            }
        }).then(result => {

            if (!result.isConfirmed) return;

            $.post("php/inventory/update_inventory.php", {
                id: id,
                restock: result.value
            }, function (res) {

                if (res.status == 1) {
                    Swal.fire({
                        icon: "success",
                        title: "Stock Updated",
                        timer: 1200,
                        showConfirmButton: false
                    });
                    loadInventory();
                } else {
                    Swal.fire("Error", res.message || "Update failed.", "error");
                }

            }, "json");
        });
    });

    // ============================
    // DELETE ITEM
    // ============================
    $(document).on("click", ".delete-btn", function () {

        let id = $(this).data("id");

        Swal.fire({
            title: "Delete this item?",
            icon: "warning",
            showCancelButton: true
        }).then(result => {

            if (!result.isConfirmed) return;

            $.post("php/inventory/delete_inventory.php", { id }, function () {
                loadInventory();
            });
        });
    });

    // ============================
    // SEARCH FILTER
    // ============================
    $("#searchInventory").on("keyup", function () {
        let value = $(this).val().toLowerCase();
        $("#inventoryTable tbody tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

});
