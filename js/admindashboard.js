
        //establishment types 

  document.addEventListener("DOMContentLoaded", function () {
    fetch("php/get/get_establishment_type.php")
        .then(res => res.json())
        .then(data => {
            console.log("Type data:", data);

            let labels = [];
            let counts = [];

            data.forEach(item => {
                labels.push(item.business_type); //  fix here
                counts.push(item.count);
            });

            const ctx = document.getElementById('establishmentTypeChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: counts,
                        backgroundColor: [
                            '#36A2EB', // Students
                            '#4BC0C0', // Professionals
                            '#FFCE56', // Families
                            '#FF6384', // Others
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            align: 'center',
                            position: 'bottom',
                        }
                    }
                }
            });
        })
        .catch(err => console.error("Error loading chart:", err));
});









    //establishment growth
document.addEventListener("DOMContentLoaded", function () {
    fetch("php/get/get_establishment_growth.php")
        .then(res => res.json())
        .then(data => {
            console.log("Growth data:", data);

            if (data.status === 0) {
                alert("❌ " + data.message);
                return;
            }

            const ctx = document.getElementById('establishmentGrowthChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.months,
                    datasets: [{
                        label: 'Establishment Growth',
                        data: data.counts,
                        borderColor: '#4B49AC',
                        backgroundColor: 'rgba(75, 73, 172, 0.2)',
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: '#4B49AC'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });
        })
        .catch(err => console.error("Fetch error:", err));
});

function counta() {
    $.ajax({
        type: "POST",
        url: "php/count/count_admindashboard.php",
        dataType: "json",
        data: { type: "Total2" },
        success: function(result) {
            if (result.status == 1) {
                $("#a2").text(result.unid);
            }
        }
    });
}

function countb() {
    $.ajax({
        type: "POST",
        url: "php/count/count_admindashboard.php",
        dataType: "json",
        data: { type: "Total3" },
        success: function(result) {
            if (result.status == 1) {
                $("#a3").text(result.unid);
            }
        }
    });
}

function countc() {
    $.ajax({
        type: "POST",
        url: "php/count/count_admindashboard.php",
        dataType: "json",
        data: { type: "Total4" },
        success: function(result) {
            if (result.status == 1) {
                $("#syslogs").text(result.unid);
            }
        }
    });
}

function countd() {
    $.ajax({
        type: "POST",
        url: "php/count/count_admindashboard.php",
        dataType: "json",
        data: { type: "Total5" },
        success: function(result) {
            if (result.status == 1) {
                $("#loginlogs").text(result.unid);
            }   
        }
    });
}

// Run both when page loads
$(window).on("load", function() {
    counta();
    countb();
    countc();
    countd();
});
