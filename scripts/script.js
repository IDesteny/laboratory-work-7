Map.init(document.getElementById('display'));

const ajax = data => $.ajax({
	url: 'server.php',
	type: 'post',
	data: data,
	dataType: 'json'
});

const display = data => {
	Map.clear();
	Map.show(this.contain = new Contain(data.contain.pos, data.contain.number));
	data.balls.forEach(ball => Map.show(new Ball({ x: ball.x, y: ball.y }, ball.number)));
};

$(() => ajax({ start: true }).then(data => {
	display(data);
	this.focus = false;
	this.c = 0;
}));

$(document).mouseup(() => this.focus = false);
$(document).mousedown(eventData => ajax({ mousedown: { x: eventData.pageX, y: eventData.pageY }}).then(data => this.focus = data.focus));
$(document).mousemove(eventData => {
	if (this.focus) ajax({ mousemove: { x: eventData.pageX, y: eventData.pageY }}).then(data => display(data))
});

$('button').click(() => ajax({ check: true }).then(data => {
	$('#counter').append('<div id="block">' + ++this.c + '</div>');
	$('#info').append('<div id="block">' + data.res + '</div>');
	display(data.data);
}));

setInterval(() => ajax({ time: true }).then(data => {
	if (data.time < 0) {
		data.time = 60;
		$('button').click();
	}
	$('#time').text(data.time);
}), 1000);
