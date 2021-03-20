class Map {
	static init(canv) {
		this.canv = canv;

		this.canv.width = window.innerWidth;
		this.canv.height = window.innerHeight >> 1;

		this.ctx = this.canv.getContext('2d');
	}

	static clear() {
		this.ctx.clearRect(0, 0, this.canv.width, this.canv.height);
	}

	static show(obj) {
		obj.draw(this.ctx);
	}
}