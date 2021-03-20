class Contain {
	constructor(pos, number) {
		this.number = number;
		this.pos = pos;
	}

	draw(ctx) {
		//left rect
		ctx.beginPath();
		ctx.rect(this.pos.x, this.pos.y, 60, 120);
		ctx.fillStyle = '#d7d7d7';
		ctx.fill();
		ctx.stroke();

		//number
		ctx.fillStyle = '#000000';
		ctx.font = '40px Helvetica';
		const pos = ctx.measureText(this.number).width / 2;
		ctx.fillText(this.number, this.pos.x + 30 - pos, this.pos.y + 70);

		//right rect
		ctx.beginPath();
		ctx.rect(this.pos.x + 70, this.pos.y, 250, 120);
		ctx.stroke();

		//name
		ctx.font = '20px Helvetica';
		ctx.fillText('Контейнер', this.pos.x + 145, this.pos.y + 20);
	}
}