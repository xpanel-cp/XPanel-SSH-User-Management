'use strict';
document.addEventListener('DOMContentLoaded', function () {
  setTimeout(function () {
    floatchart();
  }, 500);
});

function floatchart() {
  (function () {
    var options = {
      chart: { type: 'bar', height: 80, sparkline: { enabled: true } },
      colors: ['#4680FF'],
      plotOptions: { bar: { columnWidth: '80%' } },
      series: [
        {
          data: [
            10, 30, 40, 20, 60, 50, 20, 15, 20, 25, 30, 25
          ]
        }
      ],
      xaxis: { crosshairs: { width: 1 } },
      tooltip: {
        fixed: { enabled: false },
        x: { show: false },
        y: {
          title: {
            formatter: function (seriesName) {
              return '';
            }
          }
        },
        marker: { show: false }
      }
    };
    var chart = new ApexCharts(document.querySelector("#total-earning-graph-1"), options);
    chart.render();
  })();
  (function () {
    var options = {
      series: [30],
      chart: {
        height: 150,
        type: 'radialBar',
      },
      plotOptions: {
        radialBar: {
          hollow: {
            margin: 0,
            size: '60%',
            background: 'transparent',
            imageOffsetX: 0,
            imageOffsetY: 0,
            position: 'front',
          },
          track: {
            background: '#DC262650',
            strokeWidth: '50%',
          },

          dataLabels: {
            show: true,
            name: {
              show: false,
            },
            value: {
              formatter: function (val) {
                return parseInt(val);
              },
              offsetY: 7,
              color: '#DC2626',
              fontSize: '20px',
              fontWeight: '700',
              show: true,
            }
          }
        }
      },
      colors: ['#DC2626'],
      fill: {
        type: 'solid',
      },
      stroke: {
        lineCap: 'round'
      },
    };
    var chart = new ApexCharts(document.querySelector("#total-earning-graph-2"), options);
    chart.render();
  })();
}
