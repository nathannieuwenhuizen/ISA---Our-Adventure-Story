define(["require", "exports", "./app", "./vector", "./branchPoint", "jquery"], function (require, exports, app_1, vector_1, branchPoint_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var BranchTree = /** @class */ (function () {
        //the start function goes here
        function BranchTree(_canvas, _hcanvas) {
            var _this = this;
            //sizes
            this.width = 4000;
            this.xPos = 0;
            this.yPos = 0;
            //colors
            this.circleColor = "#F6F976";
            this.lineColor = "#F1BF64";
            this.bg1 = "#1F1F5B";
            this.bg2 = "#000144";
            this.hoversOverButton = false;
            this.canvas = _canvas;
            this.Hcanvas = _hcanvas;
            console.log("id", app_1.default.getQueryVariable("ID"));
            this.pointData = this.getSQLData("assets/php/getBranch.php?ID=" + app_1.default.getQueryVariable("ID"));
            //amount of layers is defined
            this.amountOfLayers = this.pointData[this.pointData.length - 1].layer;
            this.chosenoptionButton = document.getElementById('startReadingButton');
            this.width = Math.max(500, this.amountOfLayers * 150);
            this.canvas.width = this.Hcanvas.width = this.width;
            this.canvas.height = this.Hcanvas.height = this.width;
            var size = new vector_1.default(document.getElementById('canvasHolder').clientWidth, document.getElementById('canvasHolder').clientHeight);
            $('#canvasHolder').scrollTop(this.width / 2 - size.y / 2);
            $('#canvasHolder').scrollLeft(this.width / 2 - size.x / 2);
            this.xPos = this.width / 2;
            this.yPos = this.width / 2;
            this.ctx = this.canvas.getContext("2d");
            this.Hctx = this.Hcanvas.getContext("2d");
            this.CreateBranchTree();
            this.Draw();
            $("#startReadingButton").mouseover(function () {
                console.log("mouse over button");
                _this.hoversOverButton = true;
            });
            $("#startReadingButton").mouseleave(function () {
                console.log("mouse leave button");
                _this.hoversOverButton = false;
            });
            // this.canvas.onmousemove = event => this.MouseMove(event);
            addEventListener("mousemove", function (event) { return _this.MouseMove(event); });
            addEventListener("click", function () { return _this.MouseClick(); });
        }
        BranchTree.prototype.MouseMove = function (e) {
            if (this.hoversOverButton) {
                return;
            }
            var bound = this.canvas.getBoundingClientRect();
            var x = e.clientX - bound.left - this.canvas.clientLeft;
            var y = e.clientY - bound.top - this.canvas.clientTop;
            var hoverPoint = null;
            // this.ctx.fillRect(x, y, 16, 16);
            this.points.forEach(function (point) {
                if (Math.abs(point.pos.x - x) < point.radius && Math.abs(point.pos.y - y) < point.radius) {
                    hoverPoint = point;
                    return;
                }
            });
            this.hoverPoint = hoverPoint;
            if (hoverPoint != null) {
                document.getElementById("canvasHolder").style.cursor = "pointer";
            }
            else {
                document.getElementById("canvasHolder").style.cursor = "default";
            }
            this.DrawHoveredPoint();
            // this.Draw();
        };
        BranchTree.prototype.MouseClick = function () {
            if (this.hoversOverButton) {
                return;
            }
            if (this.hoverPoint != null) {
                this.chosenoptionButton.setAttribute('href', './story.php?storypart=' + this.hoverPoint.data.id);
                this.focusPoint = this.hoverPoint;
                $('#startReadingButton').css('display', 'block');
                $('#startReadingButton').css('top', (this.focusPoint.pos.y - 60) + 'px');
                $('#startReadingButton').css('left', (this.focusPoint.pos.x - 30) + 'px');
                $('#startReadingButton').css('width', '150px');
                //window.location.href = './story.php?storypart=' + this.hoverPoint.data.id;
            }
            else {
                this.focusPoint = null;
                $('#startReadingButton').css('display', 'none');
            }
            this.DrawHoveredPoint();
        };
        BranchTree.prototype.getSQLData = function (url) {
            var request = new XMLHttpRequest();
            request.open('GET', url, false);
            var data;
            request.onload = function () {
                if (request.status >= 200 && request.status < 400) {
                    // Success!
                    data = JSON.parse(request.responseText);
                }
                else {
                    console.log('We reached our target server, but it returned an error');
                }
            };
            request.onerror = function () {
                console.log('There was a connection error of some sort');
                // There was a connection error of some sort
            };
            request.send();
            return data;
        };
        BranchTree.prototype.CreateBranchTree = function () {
            var _this = this;
            //all point objects are declared
            this.points = [];
            for (var i = 0; i < this.pointData.length; i++) {
                this.points.push(new branchPoint_1.default(this.pointData[i]));
            }
            //points are positioned.
            var cAngle = 0;
            var cOffset = 0;
            var offset = 10;
            this.points.forEach(function (point) {
                var angle = Math.PI * 2 / _this.GetAmountOfCreatainLayer(point.data.layer);
                cAngle += angle;
                point.pos.x = _this.xPos + (_this.width * .5) * ((point.data.layer - 1) / _this.amountOfLayers) * Math.cos(cAngle) + cOffset * Math.cos(cAngle);
                point.pos.y = _this.yPos + (_this.width * .5) * ((point.data.layer - 1) / _this.amountOfLayers) * Math.sin(cAngle) + cOffset * Math.sin(cAngle);
                //relations of the points are
                point.parentPoint = _this.GetPointIndexWithID(point.data.parentID);
                point.childPoints = _this.GetPointsIndexWithParentID(point.data.id);
                cOffset = cOffset == offset ? 0 : offset;
            });
        };
        BranchTree.prototype.DrawHoveredPoint = function () {
            this.Hctx.clearRect(0, 0, this.width, this.width);
            this.DrawPointWithLines(this.hoverPoint);
            this.DrawPointWithLines(this.focusPoint, 2);
        };
        BranchTree.prototype.DrawPointWithLines = function (point, lw) {
            if (lw === void 0) { lw = 1; }
            if (point != null) {
                this.Hctx.fillStyle = this.circleColor;
                this.Hctx.strokeStyle = this.lineColor;
                var cPoint = point;
                this.Hctx.lineWidth = lw;
                this.DrawLineTOParent(cPoint, this.Hctx);
                cPoint.radius = 10;
                cPoint.Draw(this.Hctx, true);
                this.Hctx.fillStyle = this.circleColor;
                this.Hctx.strokeStyle = this.lineColor;
                for (var i = 0; i < cPoint.childPoints.length; i++) {
                    this.Hctx.lineWidth = lw;
                    this.DrawLineTOParent(this.points[cPoint.childPoints[i]], this.Hctx);
                }
                while (cPoint.parentPoint != -1) {
                    this.Hctx.lineWidth = lw;
                    this.DrawLineTOParent(cPoint, this.Hctx);
                    cPoint = this.points[cPoint.parentPoint];
                    cPoint.radius = 8;
                    cPoint.Draw(this.Hctx);
                    for (var i = 0; i < cPoint.childPoints.length; i++) {
                        this.Hctx.lineWidth = lw;
                        this.DrawLineTOParent(this.points[cPoint.childPoints[i]], this.Hctx);
                    }
                    // cPoint.radius = 4;
                }
                cPoint = point;
                cPoint.radius = 10;
                cPoint.Draw(this.Hctx, true);
                this.Hctx.fillStyle = this.circleColor;
                this.Hctx.strokeStyle = this.lineColor;
                // this.DrawLineTOParent(point, this.Hctx);
            }
        };
        BranchTree.prototype.Draw = function () {
            var _this = this;
            this.ctx.fillStyle = this.bg1;
            this.ctx.rect(0, 0, this.width, this.width);
            this.ctx.fill();
            this.ctx.fillStyle = this.circleColor;
            this.ctx.strokeStyle = this.bg2;
            // this.DrawLayerCircle();
            this.points.forEach(function (point) {
                // if (point.parentPoint != 1) { return;}
                _this.DrawLineTOParent(point, _this.ctx);
            });
            this.ctx.strokeStyle = this.circleColor;
            this.points.forEach(function (point) {
                // if (point.parentPoint != 1) { return;}
                point.Draw(_this.ctx);
            });
        };
        BranchTree.prototype.DrawLineTOParent = function (point, ctx) {
            //lines are drawn between the points
            ctx.beginPath();
            ctx.moveTo(point.pos.x, point.pos.y);
            if (point.parentPoint != -1) {
                // if (point.parentPoint != 4) { return;}
                //straight to parent
                // ctx.lineTo(this.points[point.parentPoint].pos.x, this.points[point.parentPoint].pos.y);
                //with arc
                //draws from child to mid cricle
                var ownDistance = new vector_1.default(point.pos.x - this.xPos, point.pos.y - this.yPos);
                var ownAngle = Math.atan2(ownDistance.y, ownDistance.x);
                var parentDistance = new vector_1.default(this.points[point.parentPoint].pos.x - this.xPos, this.points[point.parentPoint].pos.y - this.yPos);
                var angle = Math.atan2(parentDistance.y, parentDistance.x);
                var anglePrecentage = .3 + ((angle + Math.PI) / (Math.PI * 2)) * .4;
                var normalizedDistance = ownDistance;
                normalizedDistance.normalize(((anglePrecentage / this.amountOfLayers) * this.width * .5));
                ctx.lineTo(point.pos.x - normalizedDistance.x, point.pos.y - normalizedDistance.y);
                //draws arc to the coordinate of parent
                var clockwise = false;
                if (angle > ownAngle) {
                    clockwise = (angle - ownAngle > Math.PI);
                }
                else {
                    clockwise = (angle - ownAngle < Math.PI);
                }
                ctx.arc(this.xPos, this.yPos, (((point.data.layer - (1 + anglePrecentage)) / this.amountOfLayers) * this.width * .5), ownAngle, angle, clockwise);
                ctx.lineTo(this.points[point.parentPoint].pos.x, this.points[point.parentPoint].pos.y);
            }
            ctx.stroke();
        };
        BranchTree.prototype.DrawLayerCircle = function () {
            for (var i = 0; i < this.amountOfLayers; i++) {
                this.ctx.beginPath();
                this.ctx.arc(this.xPos, this.yPos, this.width * .5 - ((i / this.amountOfLayers) * this.width * .5) -
                    /* the code right moves all the circles a hlaf width inwards so that thep oints are alligned between them */ ((.5 / this.amountOfLayers) * this.width * .5), 0, 2 * Math.PI);
                this.ctx.stroke();
                // this.ctx.fill();
            }
        };
        BranchTree.prototype.GetAmountOfCreatainLayer = function (layer) {
            var result = 0;
            this.points.forEach(function (point) {
                if (point.data.layer == layer) {
                    result++;
                }
            });
            return result;
        };
        BranchTree.prototype.GetPointIndexWithID = function (id) {
            for (var i = 0; i < this.points.length; i++) {
                if (this.points[i].data.id == id) {
                    return i;
                }
            }
            return -1;
        };
        BranchTree.prototype.GetPointsIndexWithParentID = function (id) {
            var result = [];
            for (var i = 0; i < this.points.length; i++) {
                if (this.points[i].data.parentID == id) {
                    result.push(i);
                }
            }
            return result;
        };
        return BranchTree;
    }());
    exports.default = BranchTree;
});
