define(["require", "exports", "./vector"], function (require, exports, vector_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var BranchPoint = /** @class */ (function () {
        function BranchPoint(_data) {
            this.pos = new vector_1.default(0, 0);
            this.radius = 5;
            this.data = _data;
        }
        BranchPoint.prototype.Draw = function (ctx, showText) {
            if (showText === void 0) { showText = false; }
            ctx.beginPath();
            if (this.data.end != 1) {
                ctx.arc(this.pos.x, this.pos.y, this.radius, 0, 2 * Math.PI);
                ctx.fill();
            }
            else {
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
        };
        return BranchPoint;
    }());
    exports.default = BranchPoint;
});
