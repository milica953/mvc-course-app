document.addEventListener("DOMContentLoaded", () => {
    // --- Doughnut chart: Aktivni / Deaktivirani korisnici ---
    const canvas = document.getElementById('usersChart');
    const active = parseInt(canvas.dataset.active);
    const deactivated = parseInt(canvas.dataset.deactivated);
    const ctxUsers = canvas.getContext('2d');

    new Chart(ctxUsers, {
        type: 'doughnut',
        data: {
            labels: ['Aktivni', 'Deaktivirani'],
            datasets: [{
                data: [active, deactivated],
                backgroundColor: ['#28a745', '#e0e0e0'],
                borderWidth: 0
            }]
        },
        options: {
            cutout: '60%',
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        pointStyle: 'circle',  
                        padding: 20
                    }
                },
                title: {
                    display: true,
                    text: 'Ukupan broj aktivnih i deaktiviranih korisnika',
                    font: {
                        weight: 'bold',
                        size: 18
                    },
                    padding: {
                        top: 10,
                        bottom: 20
                    }
                }
            },
            responsive: false,
            maintainAspectRatio: false
        },
        plugins: [{
            id: 'centerText',
            beforeDraw(chart) {
                const {ctx, width, height} = chart;
                ctx.save();
                ctx.font = 'bold 30px Arial';
                ctx.fillStyle = '#28a745';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText(active, width / 2, height / 2 );
            }
        }]
    });

  // --- Horizontal Bar Chart: Prodate po godinama ---
const canvasSales = document.getElementById('salesBarChart');
const years = JSON.parse(canvasSales.dataset.years);
const sales = JSON.parse(canvasSales.dataset.sales);
const ctxSales = canvasSales.getContext('2d');

new Chart(ctxSales, {
    type: 'bar',
    data: {
        labels: years,
        datasets: [{
            label: 'Prodate kopije po godinama',
            data: sales,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 205, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(201, 203, 207, 0.2)'
            ],
            borderColor: [
                'rgb(255, 99, 132)',
                'rgb(255, 159, 64)',
                'rgb(255, 205, 86)',
                'rgb(75, 192, 192)',
                'rgb(54, 162, 235)',
                'rgb(153, 102, 255)',
                'rgb(201, 203, 207)'
            ],
            borderWidth: 1,
            borderRadius: 6
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        plugins: {
            legend: { display: false },
            title: {
                display: true,
                text: 'Broj prodatih kurseva (zadnje 3 godine)'
            },
            datalabels: {             // <- dodat plugin za brojeve
                anchor: 'end',        // pozicija broja
                align: 'right',       // poravnanje u odnosu na bar
                color: '#000',
                font: { weight: 'bold' },
                formatter: (value) => value // prikazuje vrednost iz data
            }
        },
        scales: {
            x: {
                beginAtZero: true,
                ticks: { precision: 0 }
            },
            y: {
                ticks: { precision: 0 }
            }
        }
    },
    plugins: [ChartDataLabels] 
});

// --- Bar chart: prihod po mesecima ---
const canvasRevenue = document.getElementById('monthlyRevenueChart');
const months = JSON.parse(canvasRevenue.dataset.months);
const revenues = JSON.parse(canvasRevenue.dataset.revenues);
const ctxRevenue = canvasRevenue.getContext('2d');

new Chart(ctxRevenue, {
    type: 'bar',
    data: {
        labels: months,
        datasets: [{
            label: 'Prihod (RSD)',
            data: revenues,
            backgroundColor: '#ff9800',
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            title: {
                display: true,
                text: 'Prihod po mesecima (ova godina)'
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.dataset.label + ': ' + context.parsed.y.toLocaleString() + ' RSD';
                    }
                }
            },
            datalabels: { 
                anchor: 'end',
                align: 'end',
                formatter: function(value) {
                    return value.toLocaleString();
                },
                color: '#000',
                font: {
                    weight: 'bold',
                    size: 12
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString();
                    }
                }
            }
        }
    },
    plugins: [ChartDataLabels] 
});

});
