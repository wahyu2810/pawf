<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<title>Calculator</title>
<link rel="stylesheet" href="css/style.css">

</head>

<body>

<div class="calculator">

<div class="lcd">
<input type="text" id="display" disabled>
</div>

<div class="buttons">

<button class="func">GT</button>
<button class="func">MRC</button>
<button class="func">M-</button>
<button class="func">M+</button>
<button class="func">MU</button>

<button class="func">%</button>
<button onclick="appendValue('7')">7</button>
<button onclick="appendValue('8')">8</button>
<button onclick="appendValue('9')">9</button>
<button class="op" onclick="appendValue('/')">÷</button>

<button class="func">▶</button>
<button onclick="appendValue('4')">4</button>
<button onclick="appendValue('5')">5</button>
<button onclick="appendValue('6')">6</button>
<button class="op" onclick="appendValue('*')">×</button>

<button class="red" onclick="clearDisplay()">AC</button>
<button onclick="appendValue('1')">1</button>
<button onclick="appendValue('2')">2</button>
<button onclick="appendValue('3')">3</button>
<button class="op big" onclick="appendValue('+')">+</button>

<button class="red">CE</button>
<button onclick="appendValue('0')">0</button>
<button onclick="appendValue('00')">00</button>
<button onclick="appendValue('.')">.</button>

<button class="equal" onclick="calculate()">=</button>

</div>

</div>

<script src="js/script.js"></script>

</body>
</html>