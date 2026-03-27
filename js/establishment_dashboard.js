



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



async function loadRevenueBySegmentChart() {
    try {
        const response = await fetch('php/get/get_revenue_chart.php');
        const result = await response.json();

        console.log("API Response:", result); // Debugging

        if (!result || result.status !== 1 || !result.data || result.data.length === 0) {
            console.error("No data or error:", result.message || result.error);
            return;
        }

        const data = result.data;

        // Unique months for X-axis
        const months = [...new Set(data.map(item => item.month))];

        // Unique segments
        const segments = [...new Set(data.map(item => item.segment))];

        // Fixed color palette
        const colorPalette = [
            '#4e73df', // Blue
            '#1cc88a', // Green
            '#f6c23e', // Yellow
            '#e74a3b', // Red
            '#36b9cc', // Teal
            '#858796', // Gray
            '#fd7e14', // Orange
            '#20c997', // Aqua Green
            '#6610f2', // Purple
            '#17a2b8'  // Cyan
        ];

        // Build datasets for Chart.js
        const datasets = segments.map((segment, index) => {
            return {
                label: segment,
                data: months.map(month => {
                    const found = data.find(d => d.month === month && d.segment === segment);
                    return found ? parseFloat(found.total_revenue) : 0;
                }),
                backgroundColor: colorPalette[index % colorPalette.length]
            };
        });

        // Initialize Chart.js
        const ctx = document.getElementById('revenueBySegmentChart').getContext('2d');
const chart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: months,
    datasets: datasets
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    devicePixelRatio: window.devicePixelRatio || 1, // 🩹 Prevents blur
    plugins: {
      title: {
        display: true,
        text: 'Revenue by Segment'
      },
      legend: {
        position: 'top'
      }
    },
    scales: {
      x: {
        stacked: true,
        title: {
          display: true,
          text: 'Month'
        }
      },
      y: {
        stacked: true,
        beginAtZero: true,
        title: {
          display: true,
          text: 'Revenue'
        }
      }
    }
  }
});

    } catch (error) {
        console.error("Error loading chart:", error);
    }
}

document.addEventListener('DOMContentLoaded', loadRevenueBySegmentChart);






// Total Revenue
fetch('php/get/get_dashboard_total_income.php')
    .then(response => response.json())
    .then(data => {
        if (data.status === 1) {
            // Format as currency
            const formattedIncome = new Intl.NumberFormat('en-PH', {
                style: 'currency',
                currency: 'PHP'
            }).format(data.total_income);

            document.getElementById('totalRevenue').textContent = formattedIncome;
        } else {
            document.getElementById('totalRevenue').textContent = "₱0.00";
            console.error(data.message || data.error);
        }
    })
    .catch(error => console.error('Error:', error));







  document.addEventListener("DOMContentLoaded", function () {
    const establishment = "ABC Store"; // test value muna

    fetch("php/get/get_customer_growth.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "establishment=" + encodeURIComponent(establishment)
    })
    .then(res => res.json())
    .then(data => {
        console.log("Growth data:", data); // ✅ Check kung may laman

        if (data.error) {
            alert("Error: " + data.error);
            return;
        }

        const ctx = document.getElementById('growthChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.months,
                datasets: [{
                    label: 'Customer Growth',
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
    .catch(err => console.error(err));
});





    
        // Customer Growth Chart

    document.addEventListener("DOMContentLoaded", function () {
    fetch("php/get/get_segment_distribution.php")
    
        .then(res => res.json())
        
        .then(data => {
            console.log("Growth data:", data);
            
            let labels = [];
            let counts = [];

            data.forEach(item => {
                labels.push(item.segment);
                counts.push(item.count);
            });

            const ctx = document.getElementById('segmentChart').getContext('2d');
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



    function loadDashboardCounts() {
    $.ajax({
        type: "POST",
        url: "php/count/count.php", // the PHP file above
        dataType: "json",
        success: function(result) {
            if (result.status == 1) {
                $("#count_segment").text(result.segments);
                $("#countsentcampaign").text(result.campaigns);
                $("#a").text(result.customer);
            } else {
                console.error(result.message);
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", error);
        }
    });
}








$(window).on("load", function() {
    loadDashboardCounts();
    
    
});



// Utility function to get cookie by name
function getCookie(name) {
    let value = "; " + document.cookie;
    let parts = value.split("; " + name + "=");
    if (parts.length === 2) return parts.pop().split(";").shift();
    return null;
}

// Check user and fetch business name
function checkUser() {
    let user = getCookie("user"); // <-- replace "user" with your actual cookie name
    if (!user) {
        console.log("No logged-in user found in cookies.");
        return;
    }

    $.ajax({
        type: "POST",
        url: "php/retrieve/retrieve_user.php",
        dataType: "json",
        data: { username: user },  // sending cookie value
        success: function(result) {
            if(result.status == 1){
                $("#name1").text(result.business_name);
            } else {
                console.log("User not found in database.");
            }
        },
        error: function(err) {
            console.error("AJAX Error:", err);
        }
    });
}

// Run on page load
$(document).ready(function() {
    checkUser();
});