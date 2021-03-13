
            <script src="https://cdn.fusioncharts.com/fusioncharts/latest/fusioncharts.js"></script>
            <div id="chart-container">FusionCharts will render here</div>

            <script>
            FusionCharts.ready(function() {
              var cSatScoreChart = new FusionCharts({
                type: 'angulargauge',
                renderAt: 'chart-container',
                width: '400',
                height: '250',
                dataFormat: 'json',
                dataSource: {
                  "chart": {
                    "caption": "<?= $essentialometer_desc ?>",
                    "subcaption": "",
                    "lowerLimit": "0",
                    "upperLimit": "100",
                    "theme": "fusion"
                  },
                  "colorRange": {
                    "color": [{
                        "minValue": "0",
                        "maxValue": "50",
                        "code": "#e44a00"
                      },
                      {
                        "minValue": "50",
                        "maxValue": "75",
                        "code": "#f8bd19"
                      },
                      {
                        "minValue": "75",
                        "maxValue": "100",
                        "code": "#6baa01"
                      }
                    ]
                  },
                  "dials": {
                    "dial": [{
                      "value": "<?= $essentialometer ?>"
                    }]
                  }
                }
              });
              cSatScoreChart.render();
            });
            </script>