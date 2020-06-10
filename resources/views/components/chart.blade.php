<!-- chartJS & vueJS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>

<canvas id="myChart" width="400" height="280"></canvas>

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
        labels: [ @foreach( $stats as $s ) "{!! $s->date !!}", @endforeach ],
        datasets: [
            {
              label: 'Followers Change',
              data: [ @foreach( $stats as $s ) {!! intval(preg_replace('/([^0-9\\.\-])/', '', $s->followersChange)) !!}, @endforeach ],
              borderColor: window.chartColors.red,
              borderWidth: 3
            },
            {
              label: 'Media Uploads',
              borderColor:  window.chartColors.green,
              borderWidth: 3, 
              data: [ @foreach( $stats as $s ) {!! intval(preg_replace('/([^0-9\\.\-])/', '', $s->mediaChange)) !!}, @endforeach ],
            },
            {
              label: 'Following Change',
              borderColor:  window.chartColors.blue,
              borderWidth: 3, 
              data: [ @foreach( $stats as $s ) {!! intval(preg_replace('/([^0-9\\.])/', '', $s->followingChange)) !!}, @endforeach ],
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
                return "New Followers: " + tooltipItems.yLabel.toLocaleString();
              }else if(tooltipItems.datasetIndex == 1) {
                return "Media Uploads: " + tooltipItems.yLabel.toLocaleString();
              }
              else if(tooltipItems.datasetIndex == 2) {
                return "New Following: " + tooltipItems.yLabel.toLocaleString();
              }
          }
        },
      },
    }
});
</script>