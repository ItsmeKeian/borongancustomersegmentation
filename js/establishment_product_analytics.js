$(document).ready(function () {

    /* ======================================================
     *  MONTHLY ITEM BUYERS (MODAL)
     * ====================================================== */
    function loadMonthlyItemBuyers(itemName) {

        $.ajax({
            url: "php/get_monthly_item_buyers.php",
            type: "POST",
            data: { item: itemName },
            dataType: "json",
            success: function (res) {

                $("#dynamicItemModalLabel").text(itemName.toUpperCase());
                $("#dynamicItemTable tbody").html("");

                let rows = "";
                res.forEach(r => {
                    rows += `
                        <tr>
                            <td>${r.full_name}</td>
                            <td>${r.age}</td>
                            <td>${r.gender}</td>
                            <td>${r.location}</td>
                            <td>${r.segment}</td>
                            <td>${r.quantity}</td>
                            <td>${r.date_purchase}</td>
                            <td>₱${Number(r.total_spent).toFixed(2)}</td>
                        </tr>
                    `;
                });

                $("#dynamicItemTable tbody").html(rows);

                new bootstrap.Modal(document.getElementById("dynamicItemModal")).show();
            }
        });
    }

    /* ======================================================
     *  WEEKLY ITEM BUYERS (MODAL)
     * ====================================================== */
    $(document).on("click", ".weekly-item-row", function () {

        let item = $(this).data("item");

        $("#weeklyItemTitle").text(item.toUpperCase());

        $.ajax({
            url: "php/get_weekly_item_buyers.php",
            type: "POST",
            data: { item: item },
            dataType: "json",
            success: function (res) {

                let rows = "";
                res.forEach(r => {
                    rows += `
                        <tr>
                            <td>${r.full_name}</td>
                            <td>${r.age ?? "-"}</td>
                            <td>${r.gender ?? "-"}</td>
                            <td>${r.location ?? "-"}</td>
                            <td>${r.segment ?? "-"}</td>
                            <td>${r.quantity}</td>
                            <td>${r.date_purchase}</td>
                            <td>₱${Number(r.total_spent).toFixed(2)}</td>
                        </tr>
                    `;
                });

                $("#weeklyItemTable tbody").html(rows);
                new bootstrap.Modal(document.getElementById("weeklyItemModal")).show();
            }
        });
    });

    /* ======================================================
     *  MONTHLY ITEM CLICK
     * ====================================================== */
    $(document).on("click", ".monthly-item-row", function () {
        const item = $(this).data("item");
        loadMonthlyItemBuyers(item);
    });

    /* ======================================================
     *  OPEN MONTH PICKER
     * ====================================================== */
    $(document).on("click", "#openMonthPicker", function () {
        $("#monthPickerModal").modal("show");
    });

    /* ======================================================
     *  APPLY MONTH FILTER
     * ====================================================== */
    $(document).on("click", "#applyMonthFilter", function () {

        let year = $("#pickerYear").val();
        let month = $("#pickerMonth").val();

        if (!year || year < 2000 || year > 2100) {
            alert("Please enter a valid year between 2000–2100.");
            return;
        }

        $("#monthPickerModal").modal("hide");

        loadMonthlySales(`${year}-${month}`);
    });

    /* ======================================================
     *  UNITS SOLD MODAL
     * ====================================================== */
    function loadMonthlySales(selectedMonth) {

        $("#unitsSoldTable tbody").html("");
        $("#selectedMonthLabel").hide().html("");

        if (!selectedMonth) return;

        $.ajax({
            url: "php/get_monthly_sales.php",
            type: "POST",
            data: { month: selectedMonth },
            dataType: "json",
            success: function (res) {

                let [year, month] = selectedMonth.split("-");
                let formatted = new Date(year, month - 1).toLocaleString("en-US", {
                    month: "long",
                    year: "numeric"
                });

                $("#selectedMonthLabel")
                    .html(`Showing results for: <strong>${formatted}</strong>`)
                    .fadeIn(200);

                let rows = "";
                res.forEach(r => {
                    rows += `
                        <tr>
                            <td>${r.item_purchase}</td>
                            <td>${r.total_sold}</td>
                            <td>₱${Number(r.total_income).toFixed(2)}</td>
                        </tr>
                    `;
                });

                $("#unitsSoldTable tbody").html(rows);
            }
        });
    }

    /* ======================================================
     *  LOAD PRODUCT ANALYTICS
     * ====================================================== */
    $.ajax({
        url: "php/product_analytics_data.php",
        method: "GET",
        dataType: "json",
        success: function (res) {

            if (!res.success) return;

            window.analyticsData = res;

            /* ---------------- CARDS ---------------- */
            let cards = `
                <div class="col-md-3 mb-3">
                    <div class="card stat-card">
                        <i class="fas fa-fire"></i>
                        <div class="number">${res.bestWeek?.item_purchase ?? '-'}</div>
                        <div class="label">Top Seller This Week</div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="card stat-card" id="uniqueBuyerCard">
                        <i class="fas fa-users"></i>
                        <div class="number">${res.uniqueBuyersCount ?? 0}</div>
                        <div class="label"> Buyers This Week</div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="card stat-card">
                        <i class="fas fa-calendar"></i>
                        <div class="number">${res.bestMonth?.item_purchase ?? '-'}</div>
                        <div class="label">Top Item Last Month</div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="card stat-card" id="unitsSoldCard">
                        <i class="fas fa-shopping-basket"></i>
                        <div class="number">${res.currentMonthTotal ?? 0}</div>
                        <div class="label">Units Sold Monthly</div>
                    </div>
                </div>
            `;
            $("#analyticsCards").html(cards);

            /* ---------------- WEEKLY TABLE ---------------- */
            let weeklyHTML = `
                <thead><tr>
                    <th>Product</th>
                    <th>Total Sold</th>
                    <th>Customers</th>
                </tr></thead>
                <tbody>
            `;

            res.weekly.forEach(row => {
                weeklyHTML += `
                    <tr class="weekly-item-row" data-item="${row.item_purchase}">
                        <td>${row.item_purchase}</td>
                        <td>${row.total_sold}</td>
                        <td>${row.unique_customers}</td>
                    </tr>
                `;
            });

            weeklyHTML += "</tbody>";
            $("#weeklyTable").html(weeklyHTML);

            /* ---------------- MONTHLY TABLE ---------------- */
            let monthlyHTML = `
                <thead><tr>
                    <th>Product</th>
                    <th>Total Sold</th>
                    <th>Customers</th>
                </tr></thead>
                <tbody>
            `;

            res.monthly.forEach(row => {
                monthlyHTML += `
                    <tr class="monthly-item-row" data-item="${row.item_purchase}">
                        <td>${row.item_purchase}</td>
                        <td>${row.total_sold}</td>
                        <td>${row.unique_customers}</td>
                    </tr>
                `;
            });

            monthlyHTML += "</tbody>";
            $("#monthlyTable").html(monthlyHTML);

            /* ---------------- FAST MOVING ---------------- */
            let fastHTML = `
                <thead><tr>
                    <th>Product</th>
                    <th>Last 7 Days</th>
                    <th>Prev 7 Days</th>
                </tr></thead>
                <tbody>
            `;

            res.fast.forEach(row => {
                fastHTML += `
                    <tr>
                        <td>${row.item_purchase}</td>
                        <td>${row.week1}</td>
                        <td>${row.week2}</td>
                    </tr>
                `;
            });

            fastHTML += "</tbody>";
            $("#fastTable").html(fastHTML);

            /* ---------------- SLOW MOVING ---------------- */
            let slowHTML = `
                <thead><tr>
                    <th>Product</th>
                    <th>Last 7 Days</th>
                    <th>Prev 7 Days</th>
                </tr></thead>
                <tbody>
            `;

            res.slow.forEach(row => {
                slowHTML += `
                    <tr>
                        <td>${row.item_purchase}</td>
                        <td>${row.week1}</td>
                        <td>${row.week2}</td>
                    </tr>
                `;
            });

            slowHTML += "</tbody>";
            $("#slowTable").html(slowHTML);
        }
    });

    /* ======================================================
     *  UNIQUE BUYERS THIS WEEK MODAL
     * ====================================================== */
    $(document).on("click", "#uniqueBuyerCard", function () {

        let buyers = window.analyticsData.uniqueBuyers;
        let rows = "";

        buyers.forEach(b => {
            rows += `
                <tr>
                    <td>${b.full_name}</td>
                    <td>${b.age ?? "-"}</td>
                    <td>${b.gender ?? "-"}</td>
                    <td>${b.location ?? "-"}</td>
                    <td>${b.segment ?? "-"}</td>
                    <td>${b.items_bought ?? "-"}</td>
                    <td>${b.total_items}</td>
                    <td>₱${Number(b.total_spent).toFixed(2)}</td>
                </tr>
            `;
        });

        $("#uniqueBuyersTable tbody").html(rows);

        new bootstrap.Modal(document.getElementById("uniqueBuyersModal")).show();
    });

    /* ======================================================
     *  UNITS SOLD CARD CLICK
     * ====================================================== */
    $(document).on("click", "#unitsSoldCard", function () {
        $("#unitsSoldTable tbody").html("");
        $("#selectedMonthLabel").hide().text("");
        new bootstrap.Modal(document.getElementById("unitsSoldModal")).show();
    });

});
