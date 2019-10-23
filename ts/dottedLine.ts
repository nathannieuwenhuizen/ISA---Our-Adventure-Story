import Vector from './vector';

export default class DottedLine {
    public beginPos: Vector = new Vector(0,0);
    public endPos: Vector = new Vector(0,0);

    private gapLength: number = 5;
    private dotLength: number = 10;
    private offset: number= 0;
    private lineWidth = 3;
    constructor() {
    }
    public SetPos(_begin: Vector, _end: Vector){
        this.beginPos = _begin;
        this.endPos = _end;
    }
    public Draw(ctx: CanvasRenderingContext2D) {
        ctx.save();

        ctx.beginPath();
        ctx.strokeStyle = "#fff";

        ctx.lineWidth = this.lineWidth;
        this.offset = (this.offset - 1);
        if (this.offset < 0) 
        {
            this.offset = this.dotLength + this.gapLength;
        }

        ctx.setLineDash([this.dotLength, this.gapLength]);
        ctx.lineDashOffset = this.offset;
        ctx.moveTo(this.beginPos.x, this.beginPos.y);
        ctx.lineTo(this.endPos.x, this.endPos.y);

        ctx.stroke();
        ctx.restore();
    }
}
