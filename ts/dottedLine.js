define(["require", "exports", "./vector"], function (require, exports, vector_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var DottedLine = /** @class */ (function () {
        function DottedLine() {
            this.beginPos = new vector_1.default(0, 0);
            this.endPos = new vector_1.default(0, 0);
            this.gapLength = 5;
            this.dotLength = 10;
            this.offset = 0;
            this.lineWidth = 3;
        }
        DottedLine.prototype.SetPos = function (_begin, _end) {
            this.beginPos = _begin;
            this.endPos = _end;
        };
        DottedLine.prototype.Draw = function (ctx) {
            ctx.save();
            ctx.beginPath();
            ctx.strokeStyle = "#fff";
            ctx.lineWidth = this.lineWidth;
            this.offset = (this.offset - 1);
            if (this.offset < 0) {
                this.offset = this.dotLength + this.gapLength;
            }
            ctx.setLineDash([this.dotLength, this.gapLength]);
            ctx.lineDashOffset = this.offset;
            ctx.moveTo(this.beginPos.x, this.beginPos.y);
            ctx.lineTo(this.endPos.x, this.endPos.y);
            ctx.stroke();
            ctx.restore();
        };
        return DottedLine;
    }());
    exports.default = DottedLine;
});
