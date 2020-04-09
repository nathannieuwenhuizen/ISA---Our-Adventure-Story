define(["require", "exports", "./vector", "./branchPoint", "./dottedLine", "./app", "jquery"], function (require, exports, vector_1, branchPoint_1, dottedLine_1, app_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var ImportTree = /** @class */ (function () {
        //the start function goes here
        function ImportTree(_canvas, _hcanvas, _mergeID) {
            if (_mergeID === void 0) { _mergeID = 0; }
            var _this = this;
            //sizes
            this.width = 4000;
            this.xPos = 0;
            this.yPos = 0;
            this.hoverDottedLine = new dottedLine_1.default();
            this.focusDottedLine = new dottedLine_1.default();
            //colors
            this.circleColor = "#F6F976";
            this.lineColor = "#F1BF64";
            this.bg1 = "#1F1F5B";
            this.bg2 = "#000144";
            this.hoversOverButton = false;
            this.canvas = _canvas;
            this.Hcanvas = _hcanvas;
            this.pointID = app_1.default.getQueryVariable('storypart');
            this.pointData = this.getSQLData("assets/php/getBranch.php?ID=" + _mergeID);
            //amount of layers is defined
            this.amountOfLayers = this.pointData[this.pointData.length - 1].layer;
            this.chosenoptionButton = document.getElementById('startReadingButton');
            //this.chosenoptionButton.setAttribute('href', '');
            this.chosenoptionButton.addEventListener('click', function () {
                _this.MergePart();
            });
            this.width = Math.max(500, this.amountOfLayers * 150);
            this.canvas.width = this.Hcanvas.width = this.width;
            this.canvas.height = this.Hcanvas.height = this.width;
            this.canvasHolder = document.getElementById('canvasHolder');
            this.Show();
            this.xPos = this.width / 2;
            this.yPos = this.width / 2;
            this.ctx = this.canvas.getContext("2d");
            this.Hctx = this.Hcanvas.getContext("2d");
            this.CreateBranchTree();
            this.Draw();
            this.ScrollToMerge();
            $("#startReadingButton").mouseover(function () {
                _this.hoversOverButton = true;
            });
            $("#startReadingButton").mouseleave(function () {
                _this.hoversOverButton = false;
            });
            // this.canvas.onmousemove = event => this.MouseMove(event);
            addEventListener("mousemove", function (event) { return _this.MouseMove(event); });
            addEventListener("click", function () { return _this.MouseClick(); });
            requestAnimationFrame(this.DrawHoverCanvas.bind(this));
        }
        ImportTree.prototype.MouseMove = function (e) {
            var _this = this;
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
                    if (_this.AbleToMerge(point)) {
                        hoverPoint = point;
                        return;
                    }
                }
            });
            this.hoverPoint = hoverPoint;
            if (hoverPoint != null) {
                document.getElementById("canvasHolder").style.cursor = "pointer";
            }
            else {
                document.getElementById("canvasHolder").style.cursor = "default";
            }
            //this.DrawHoverCanvas();
            // this.Draw();
        };
        ImportTree.prototype.MouseClick = function () {
            if (this.hoversOverButton) {
                return;
            }
            if (this.hoverPoint != null) {
                if (this.AbleToMerge(this.hoverPoint)) {
                    this.focusPoint = this.hoverPoint;
                    $('#startReadingButton').css('display', 'block');
                    $('#startReadingButton').css('top', (this.focusPoint.pos.y - 60) + 'px');
                    $('#startReadingButton').css('left', (this.focusPoint.pos.x - 30) + 'px');
                    $('#startReadingButton').css('width', '150px');
                }
            }
            else {
                this.focusPoint = null;
                $('#startReadingButton').css('display', 'none');
            }
            // this.DrawHoverCanvas();
        };
        ImportTree.prototype.MergePart = function () {
            console.log(this.mergeFromPoint, this.focusPoint);
            console.log("./assets/php/mergePart.php?story=" + STORYID + "&mergeFrom=" + this.mergeFromPoint.data.id + "&mergeTo=" + this.focusPoint.data.id);
            var result = app_1.default.setSQLData("./assets/php/mergePart.php?story=" + STORYID + "&mergeFrom=" + this.mergeFromPoint.data.id + "&mergeTo=" + this.focusPoint.data.id);
            console.log(result);
            if (result.result != 0) {
                window.location.href = './story.php?storypart=' + this.mergeFromPoint.data.id;
            }
        };
        ImportTree.prototype.SetMergePoint = function (_id) {
            this.mergeFromPoint = this.points[this.GetPointIndexWithID(_id)];
            this.ScrollToMerge();
        };
        ImportTree.prototype.Show = function () {
            this.canvasHolder.style.display = "block";
        };
        ImportTree.prototype.Hide = function () {
            this.canvasHolder.style.display = "none";
        };
        ImportTree.prototype.ScrollToMerge = function () {
            var size = new vector_1.default(document.getElementById('canvasHolder').clientWidth, document.getElementById('canvasHolder').clientHeight);
            $('#canvasHolder').scrollTop(this.mergeFromPoint.pos.y - size.y / 2);
            $('#canvasHolder').scrollLeft(this.mergeFromPoint.pos.x - size.x / 2);
        };
        //returns whether a point can be merged, prevents loops and bugs on front-end
        ImportTree.prototype.AbleToMerge = function (point) {
            //check if its self.
            if (this.mergeFromPoint == point) {
                //this.chosenoptionButton.innerHTML ="It is self";
                return false;
            }
            //check parent/prevents direct loop
            var cPoint = this.points[this.mergeFromPoint.parentPoint];
            while (cPoint.parentPoint != -1) {
                if (cPoint == point) {
                    //this.chosenoptionButton.innerHTML ="It is parent";
                    return false;
                }
                cPoint = this.points[cPoint.parentPoint];
            }
            //check origin
            if (point.parentPoint == -1) {
                return false;
            }
            //check direct children 
            for (var i = 0; i < this.mergeFromPoint.childPoints.length; i++) {
                if (this.mergeFromPoint.childPoints[i] == this.points.indexOf(point)) {
                    //this.chosenoptionButton.innerHTML ="It is direct child";
                    return false;
                }
            }
            return true;
        };
        //retrieves the sql data as a JSON object
        ImportTree.prototype.getSQLData = function (url) {
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
        //creates the branchtree with all the points in it.
        ImportTree.prototype.CreateBranchTree = function () {
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
                if (Number(point.data.id) == _this.pointID) {
                    _this.mergeFromPoint = point;
                }
                point.pos.x = _this.xPos + (_this.width * .5) * ((point.data.layer - 1) / _this.amountOfLayers) * Math.cos(cAngle) + cOffset * Math.cos(cAngle);
                point.pos.y = _this.yPos + (_this.width * .5) * ((point.data.layer - 1) / _this.amountOfLayers) * Math.sin(cAngle) + cOffset * Math.sin(cAngle);
                //relations of the points are
                point.parentPoint = _this.GetPointIndexWithID(point.data.parentID);
                point.childPoints = _this.GetPointsIndexWithParentID(point.data.id);
                cOffset = cOffset == offset ? 0 : offset;
            });
        };
        ImportTree.prototype.DrawHoverCanvas = function () {
            this.Hctx.clearRect(0, 0, this.width, this.width);
            this.DrawPointWithLines(this.hoverPoint);
            this.DrawPointWithLines(this.focusPoint, 2, this.focusDottedLine);
            this.DrawPointWithLines(this.mergeFromPoint, 2);
            requestAnimationFrame(this.DrawHoverCanvas.bind(this));
        };
        ImportTree.prototype.DrawPointWithLines = function (point, lw, dottedLine) {
            if (lw === void 0) { lw = 1; }
            if (dottedLine === void 0) { dottedLine = null; }
            if (point != null) {
                this.Hctx.fillStyle = this.circleColor;
                this.Hctx.strokeStyle = this.lineColor;
                var cPoint = point;
                this.Hctx.lineWidth = lw;
                this.DrawLineToParent(cPoint, this.Hctx);
                cPoint.radius = 10;
                cPoint.Draw(this.Hctx, true);
                this.Hctx.fillStyle = this.circleColor;
                this.Hctx.strokeStyle = this.lineColor;
                for (var i = 0; i < cPoint.childPoints.length; i++) {
                    this.Hctx.lineWidth = lw;
                    this.DrawLineToParent(this.points[cPoint.childPoints[i]], this.Hctx);
                }
                while (cPoint.parentPoint != -1) {
                    this.Hctx.lineWidth = lw;
                    this.DrawLineToParent(cPoint, this.Hctx);
                    cPoint = this.points[cPoint.parentPoint];
                    cPoint.radius = 8;
                    cPoint.Draw(this.Hctx);
                    for (var i = 0; i < cPoint.childPoints.length; i++) {
                        this.Hctx.lineWidth = lw;
                        this.DrawLineToParent(this.points[cPoint.childPoints[i]], this.Hctx);
                    }
                    // cPoint.radius = 4;
                }
                //dottedline
                if (dottedLine != null) {
                    dottedLine.SetPos(this.mergeFromPoint.pos, point.pos);
                    dottedLine.Draw(this.Hctx);
                }
                cPoint = point;
                cPoint.radius = 10;
                cPoint.Draw(this.Hctx, true);
                this.Hctx.fillStyle = this.circleColor;
                this.Hctx.strokeStyle = this.lineColor;
                // this.DrawLineTOParent(point, this.Hctx);
            }
        };
        //draws the whole tree, can cause lag.
        ImportTree.prototype.Draw = function () {
            var _this = this;
            this.ctx.fillStyle = this.bg1;
            this.ctx.rect(0, 0, this.width, this.width);
            this.ctx.fill();
            this.ctx.fillStyle = this.circleColor;
            this.ctx.strokeStyle = this.bg2;
            // this.DrawLayerCircle();
            this.points.forEach(function (point) {
                // if (point.parentPoint != 1) { return;}
                _this.DrawLineToParent(point, _this.ctx);
            });
            this.ctx.strokeStyle = this.circleColor;
            this.points.forEach(function (point) {
                // if (point.parentPoint != 1) { return;}
                point.Draw(_this.ctx);
            });
        };
        ImportTree.prototype.DrawLineToParent = function (point, ctx) {
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
        //TODO: remove this. Not needed
        ImportTree.prototype.DrawLayerCircle = function () {
            for (var i = 0; i < this.amountOfLayers; i++) {
                this.ctx.beginPath();
                this.ctx.arc(this.xPos, this.yPos, this.width * .5 - ((i / this.amountOfLayers) * this.width * .5) -
                    /* the code right moves all the circles a hlaf width inwards so that thep oints are alligned between them */ ((.5 / this.amountOfLayers) * this.width * .5), 0, 2 * Math.PI);
                this.ctx.stroke();
                // this.ctx.fill();
            }
        };
        ImportTree.prototype.GetAmountOfCreatainLayer = function (layer) {
            var result = 0;
            this.points.forEach(function (point) {
                if (point.data.layer == layer) {
                    result++;
                }
            });
            return result;
        };
        //gets the index of array from the point that has the sql id i
        ImportTree.prototype.GetPointIndexWithID = function (id) {
            for (var i = 0; i < this.points.length; i++) {
                if (this.points[i].data.id == id) {
                    return i;
                }
            }
            return -1;
        };
        //returns the indexes of the points array that are child to the point that has the ID in paremeter.
        ImportTree.prototype.GetPointsIndexWithParentID = function (id) {
            var result = [];
            for (var i = 0; i < this.points.length; i++) {
                if (this.points[i].data.parentID == id) {
                    result.push(i);
                }
            }
            return result;
        };
        return ImportTree;
    }());
    exports.default = ImportTree;
});
