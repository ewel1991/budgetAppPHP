document.addEventListener('DOMContentLoaded', () => {
  const chartDataElem = document.getElementById('chartData');
  const totalIncome = parseFloat(chartDataElem.dataset.totalIncome) || 0;
  const totalExpense = parseFloat(chartDataElem.dataset.totalExpense) || 0;

  const ctx = document.getElementById('incomeExpenseChart').getContext('2d');

  const data = {
    labels: ['Przychody', 'Wydatki'],
    datasets: [{
      data: [totalIncome, totalExpense],
      backgroundColor: ['#4CAF50', '#F44336'],
      hoverOffset: 30
    }]
  };

  const config = {
    type: 'pie',
    data: data,
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'bottom',
          labels: { color: 'white' }
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              let label = context.label || '';
              let value = context.parsed || 0;
              let data = context.dataset.data;
              let total = data.reduce((a, b) => a + b, 0);
              let percentage = total ? (value / total * 100).toFixed(2) : 0;
              return `${label}: ${value.toFixed(2)} zł (${percentage}%)`;
            }
          }
        }
      }
    }
  };

  new Chart(ctx, config);
});