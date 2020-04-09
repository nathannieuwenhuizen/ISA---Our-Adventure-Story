define(["require", "exports"], function (require, exports) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var Vector = /** @class */ (function () {
        function Vector(_x, _y) {
            this.x = 0;
            this.y = 0;
            this.x = _x;
            this.y = _y;
        }
        Vector.prototype.normalize = function (size) {
            if (size === void 0) { size = 1; }
            var distance = this.length();
            this.x = this.x / distance * size;
            this.y = this.y / distance * size;
        };
        Vector.prototype.length = function () {
            return Math.sqrt(this.x * this.x + this.y * this.y);
        };
        return Vector;
    }());
    exports.default = Vector;
});
