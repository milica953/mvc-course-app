document.addEventListener("DOMContentLoaded", () => {

    // ===============================
    // BAR CHART: Prihod po mesecima
const canvas = document.getElementById('monthlyRevenueChart');
if (!canvas) return;

const labels = JSON.parse(canvas.dataset.labels || '[]');
const revenues = JSON.parse(canvas.dataset.revenues || '[]');
const title = canvas.dataset.title || 'Prihod';

new Chart(canvas.getContext('2d'), {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Prihod (RSD)',
            data: revenues,
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            title: {
                display: true,
                text: title
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: value => value.toLocaleString('sr-RS')
                }
            }
        }
    }
});

});
