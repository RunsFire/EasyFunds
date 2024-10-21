<!DOCTYPE html>
<html>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
<body>
<meta charset="utf-8">
<div style="width:50%;">
<canvas id="myChart"></canvas>
</div>

<script>
var xValues = ["Opérations contestée par le débiteur", "Fraude à la carte", "Titulaire décédé", "Compte à découvert", "Inconnu"];
var yValues = [55, 49, 44, 24, 15];
var barColors = [
  "#9E00FF",
  "#7823AC",
  "#9357B8",
  "#593A6D",
  "#C264FC"
];

new Chart("myChart", {
  type: "doughnut",
  data: {
    labels: xValues,
    datasets: [{
      backgroundColor: barColors,
      data: yValues
    }]
  },
  options: {
    title: {
      display: true,
      text: "Raison des impayés"
    }
  }
});
</script>

</body>
</html>
