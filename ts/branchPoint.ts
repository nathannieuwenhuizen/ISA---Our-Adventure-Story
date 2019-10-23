import Vector from './vector';

export default class BranchPoint {
    public pos: Vector = new Vector(0,0);
    public radius: number = 5;
    public data: IBranch;

    public childPoints: number[];
    public parentPoint: number;

    constructor(_data: IBranch) {
        this.data = _data;
    }
    public Draw(ctx: CanvasRenderingContext2D, showText: boolean = false) {
        ctx.beginPath();
        if (this.data.end != 1) {
            ctx.arc(this.pos.x, this.pos.y, this.radius, 0, 2 * Math.PI);
            ctx.fill();

        } else {
            ctx.arc(this.pos.x, this.pos.y, 4, 0, 2 * Math.PI);

            ctx.lineWidth = this.radius / 2;
            ctx.stroke();
            ctx.lineWidth = 1;
        }
        if (showText) {

            ctx.fillStyle = "#fff";
            ctx.strokeStyle = "#000";
            ctx.font = "15px Arial";
            ctx.lineWidth = 2;
            ctx.strokeText(this.data.option, this.pos.x + 10, this.pos.y);
            ctx.lineWidth = 1;
            ctx.fillText(this.data.option, this.pos.x + 10, this.pos.y);

        }
    }
}
export interface IBranch {
    id: string;
    parentID: string;
    option: string;
    end: number;
    layer: number;
}
