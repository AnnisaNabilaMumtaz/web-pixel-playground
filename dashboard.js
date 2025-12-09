const ctx = document.getElementById('areaChart').getContext('2d');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [
            {
                label: 'Mobile Apps',
                data: [100, 200, 150, 300, 250, 200, 400, 350, 450],
                borderColor: '#4caf50',
                backgroundColor: 'rgba(76, 175, 80, 0.2)',
                fill: true,
                tension: 0.4, // Smooth curve
                pointBackgroundColor: '#4caf50',
                pointRadius: 4,
            }
        ],
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false, // Hide legend
            },
            tooltip: {
                enabled: true,
            },
        },
        scales: {
            x: {
                grid: {
                    display: false,
                },
            },
            y: {
                beginAtZero: true,
            },
        },
    },
});
