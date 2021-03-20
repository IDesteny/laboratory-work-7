class Ball {
	constructor(pos, number) {
		this.pos = pos;
		this.number = number;
	}

	draw(ctx) {
		ctx.beginPath();
		ctx.arc(this.pos.x, this.pos.y, 20, 0, 2 * Math.PI);
		ctx.fillStyle = 'white';
		ctx.fill();
		ctx.stroke();

		ctx.fillStyle = 'black';
		ctx.font = '25px Helvetica';
		const pos = ctx.measureText(this.number).width >> 1;

		ctx.fillText(this.number, this.pos.x - pos, this.pos.y + 8);
	}
}