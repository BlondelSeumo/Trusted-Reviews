<!-- chartJS & vueJS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>

<canvas id="myChart" width="400"></canvas>

<script>
window.chartColors = {
  red: 'rgb(255, 99, 132)',
  orange: 'rgb(255, 159, 64)',
  yellow: 'rgb(255, 205, 86)',
  green: 'rgb(75, 192, 192)',
  blue: 'rgb(54, 162, 235)',
  purple: 'rgb(153, 102, 255)',
  grey: 'rgb(201, 203, 207)'
};


var ctx = document.getElementById("myChart").getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [ @foreach( $stats as $date => $totalEarnings ) "{!! $date !!}", @endforeach ],
        datasets: [
            {
              label: 'Earnings',
              data: [ @foreach( $stats as $date => $totalEarnings ) {!! number_format($totalEarnings,2) !!}, @endforeach ],
              borderColor: window.chartColors.red,
              borderWidth: 3
            },
        ]
    },
    options: {
      responsive: true, 
      maintainAspectRatio: false,
      tooltips: {
        mode: 'x',
        callbacks: {
          label: function(tooltipItems, data) {
              if( tooltipItems.datasetIndex == 0 ) {
                return "Earnings: {{ setting( 'pricing.currencySymbol' ) }}" + tooltipItems.yLabel.toLocaleString();
              }
          }
        },
      },
    }
});
</script>