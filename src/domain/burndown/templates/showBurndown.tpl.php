<?php
$actualArray = $this->get('actualArray'); //кол-во текущих storypoints
$maxTickets = $this->get('countStoryPoints');
$idealArray = $this->get('idealArray'); //идеальное решение задач
$dateArray = range(0, 30, 1); //текущие даты
$dateArray = $this->get('dateArray')

?>
<script src="https://code.highcharts.com/highcharts.js"></script>

<script type="text/javascript">
    (function ($) {
        jQuery(document).ready(function () {
            var doc = $(document);
            $('#container-burndown').highcharts({
                title: {
                    text: 'Burndown Chart of Project',
                    x: -10 //center
                },
                scrollbar: {
                    barBackgroundColor: 'gray',
                    barBorderRadius: 7,
                    barBorderWidth: 0,
                    buttonBackgroundColor: 'gray',
                    buttonBorderWidth: 0,
                    buttonBorderRadius: 7,
                    trackBackgroundColor: 'none',
                    trackBorderWidth: 1,
                    trackBorderRadius: 8,
                    trackBorderColor: '#CCC'
                },
                colors: ['blue', 'red'],
                plotOptions: {
                    line: {
                        lineWidth: 3
                    },
                    tooltip: {
                        hideDelay: 200
                    }
                },
                subtitle: {
                    text: 'All Project Team Summary',
                    x: -10
                },
                xAxis: {
                    categories: <?php echo json_encode($dateArray);?>
                },
                yAxis: {
                    title: {
                        text: 'Remaining work (Minutes)'

                    },
                    type: 'linear',
                    max: <?php echo $maxTickets;?>,
                    min: 0,
                    tickInterval: 1

                },

                tooltip: {
                    valueSuffix: ' Minutes',
                    crosshairs: true,
                    shared: true
                },
                legend: {
                    layout: 'horizontal',
                    align: 'center',
                    verticalAlign: 'bottom',
                    borderWidth: 0
                },
                series: [{
                    name: 'Plan burn',
                    color: 'rgba(255,0,0,0.25)',
                    lineWidth: 2,

                    data: <?php echo json_encode($idealArray);?>
                }, {
                    name: 'Actual Burn',
                    color: 'rgba(0,120,200,0.75)',
                    marker: {
                        radius: 6
                    },
                    data: <?php echo json_encode($actualArray);?>
                }]
            });
        });
    })(jQuery);
</script>

<div id="container-burndown" ></div>
