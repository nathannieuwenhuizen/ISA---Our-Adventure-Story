import 'jquery';
import App from './app';
import Vector from './vector';
import BranchPoint from './branchPoint';
import IBranch from './branchPoint';
export default class BranchTree {

    //canvases and contextes
    public canvas: HTMLCanvasElement;
    private ctx: CanvasRenderingContext2D;
    public Hcanvas: HTMLCanvasElement;
    private Hctx: CanvasRenderingContext2D;

    //sizes
    private width: number = 4000;
    private xPos: number = 0;
    private yPos: number = 0;

    //all the points and data
    private points: BranchPoint[];
    private pointData: IBranch[];
    private amountOfLayers: number;

    private hoverPoint: BranchPoint;
    private focusPoint: BranchPoint;

    //colors
    private circleColor: string = "#F6F976";
    private lineColor: string = "#F1BF64";
    private bg1: string = "#1F1F5B";
    private bg2: string = "#000144";

    private hoversOverButton: boolean = false;

    //other dom elements
    private chosenoptionButton: Element;

    //the start function goes here
    constructor(_canvas: any, _hcanvas: any) {
        this.canvas = _canvas;
        this.Hcanvas = _hcanvas;
        console.log("id",  App.getQueryVariable("ID"));
        this.pointData = this.getSQLData("assets/php/getBranch.php?ID=" + App.getQueryVariable("ID"));

        //amount of layers is defined
        this.amountOfLayers = this.pointData[this.pointData.length-1].layer;


        this.chosenoptionButton = document.getElementById('startReadingButton');

        this.width = Math.max(500,  this.amountOfLayers * 150);
        this.canvas.width = this.Hcanvas.width = this.width; 
        this.canvas.height = this.Hcanvas.height = this.width;
        
        let size: Vector = new Vector(document.getElementById('canvasHolder').clientWidth,document.getElementById('canvasHolder').clientHeight);
        $('#canvasHolder').scrollTop(this.width / 2 - size.y / 2);
        $('#canvasHolder').scrollLeft(this.width / 2 - size.x / 2);
        
        this.xPos = this.width / 2;
        this.yPos = this.width / 2;
        this.ctx = this.canvas.getContext("2d");
        this.Hctx = this.Hcanvas.getContext("2d");

        this.CreateBranchTree();
        this.Draw();
    
        $("#startReadingButton").mouseover(() =>{
            console.log("mouse over button")
            this.hoversOverButton = true;
            
        });
          
        $("#startReadingButton").mouseleave(() => {
            console.log("mouse leave button");
            this.hoversOverButton = false;
        });
          
        // this.canvas.onmousemove = event => this.MouseMove(event);
        addEventListener("mousemove", event => this.MouseMove(event));
        addEventListener("click", () => this.MouseClick());
    }
    private MouseMove(e: MouseEvent) {
        if (this.hoversOverButton) {
            return;
        }
        let bound = this.canvas.getBoundingClientRect();
        let x = e.clientX - bound.left - this.canvas.clientLeft;
        let y = e.clientY - bound.top - this.canvas.clientTop;
        let hoverPoint: BranchPoint = null;
        // this.ctx.fillRect(x, y, 16, 16);
        this.points.forEach(point => {
            if ( Math.abs(point.pos.x - x) < point.radius && Math.abs(point.pos.y - y) < point.radius){
                hoverPoint = point;
                return;
            }
        });
        this.hoverPoint = hoverPoint;
        if (hoverPoint != null) {
            document.getElementById("canvasHolder").style.cursor = "pointer";
        } else {
            document.getElementById("canvasHolder").style.cursor = "default";
        }
        this.DrawHoveredPoint();
        // this.Draw();
    }
    private MouseClick() {
        if (this.hoversOverButton) {
            return;
        }

        if (this.hoverPoint != null) {
            this.chosenoptionButton.setAttribute('href', './story.php?storypart=' + this.hoverPoint.data.id);
            this.focusPoint = this.hoverPoint;

            $('#startReadingButton').css('display', 'block');
            $('#startReadingButton').css('top', (this.focusPoint.pos.y - 60) + 'px');
            $('#startReadingButton').css('left', (this.focusPoint.pos.x -30) + 'px');
            $('#startReadingButton').css('width', '150px');
            
             //window.location.href = './story.php?storypart=' + this.hoverPoint.data.id;
        } else {
            this.focusPoint = null;
            $('#startReadingButton').css('display', 'none');

        }
        this.DrawHoveredPoint();

    }

    private getSQLData(url: string): any {
        let request: XMLHttpRequest = new XMLHttpRequest();
        request.open('GET', url, false);
        let data: any;
        request.onload = () => {
            if (request.status >= 200 && request.status < 400) {
                // Success!
                data = JSON.parse(request.responseText);
            } else {
                console.log('We reached our target server, but it returned an error');

            }
        };

        request.onerror = () => {
            console.log('There was a connection error of some sort');

            // There was a connection error of some sort
        };

        request.send();
        return data;
    }

    
    public CreateBranchTree() {
        //all point objects are declared
        this.points = [];
        for (let i = 0; i < this.pointData.length; i++) {
            this.points.push(new BranchPoint(this.pointData[i]));
        }

        //points are positioned.
        let cAngle = 0;
        let cOffset = 0;
        let offset = 10;
        this.points.forEach(point => {
            let angle = Math.PI * 2 / this.GetAmountOfCreatainLayer(point.data.layer);
            cAngle += angle;

            point.pos.x = this.xPos + (this.width * .5) * ((point.data.layer - 1) / this.amountOfLayers) * Math.cos(cAngle) + cOffset * Math.cos(cAngle);
            point.pos.y = this.yPos + (this.width * .5) * ((point.data.layer - 1) / this.amountOfLayers) * Math.sin(cAngle) + cOffset * Math.sin(cAngle);

            //relations of the points are
            point.parentPoint = this.GetPointIndexWithID(point.data.parentID);
            point.childPoints = this.GetPointsIndexWithParentID(point.data.id);

            cOffset = cOffset == offset ? 0 : offset;
        });
    }

    public DrawHoveredPoint() {
        this.Hctx.clearRect(0,0, this.width, this.width);
        this.DrawPointWithLines(this.hoverPoint);
        this.DrawPointWithLines(this.focusPoint, 2);
    }
    public DrawPointWithLines(point: BranchPoint, lw: number = 1) {
        if (point != null) { 
    
            this.Hctx.fillStyle = this.circleColor;
            this.Hctx.strokeStyle = this.lineColor;

            let cPoint = point;
            this.Hctx.lineWidth = lw; 
            this.DrawLineTOParent(cPoint, this.Hctx);
            cPoint.radius = 10;
            cPoint.Draw(this.Hctx, true);
            this.Hctx.fillStyle = this.circleColor;
            this.Hctx.strokeStyle = this.lineColor;

            for (let i = 0; i < cPoint.childPoints.length; i++) {
                this.Hctx.lineWidth = lw; 
                this.DrawLineTOParent(this.points[cPoint.childPoints[i]], this.Hctx);
            }
            while (cPoint.parentPoint != -1) {
                this.Hctx.lineWidth = lw; 
                this.DrawLineTOParent(cPoint, this.Hctx);
                cPoint = this.points[cPoint.parentPoint];
                cPoint.radius = 8;
                cPoint.Draw(this.Hctx);
                for (let i = 0; i < cPoint.childPoints.length; i++) {
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
    }
    

    public Draw() {
        this.ctx.fillStyle = this.bg1;
        this.ctx.rect(0,0, this.width, this.width);
        this.ctx.fill();
        this.ctx.fillStyle = this.circleColor;
        this.ctx.strokeStyle = this.bg2;
        // this.DrawLayerCircle();
        this.points.forEach(point => {
            // if (point.parentPoint != 1) { return;}
            this.DrawLineTOParent(point, this.ctx);
        });

        this.ctx.strokeStyle = this.circleColor;

        this.points.forEach(point => {
            // if (point.parentPoint != 1) { return;}
            point.Draw(this.ctx);

    
        });
    }

    public DrawLineTOParent(point: BranchPoint, ctx: CanvasRenderingContext2D) {
        //lines are drawn between the points
        ctx.beginPath();
        ctx.moveTo(point.pos.x, point.pos.y);
        if (point.parentPoint != -1) {
            // if (point.parentPoint != 4) { return;}
            //straight to parent
            // ctx.lineTo(this.points[point.parentPoint].pos.x, this.points[point.parentPoint].pos.y);

            //with arc
            //draws from child to mid cricle
            let ownDistance: Vector = new Vector(point.pos.x - this.xPos, point.pos.y - this.yPos);
            let ownAngle: number = Math.atan2(ownDistance.y, ownDistance.x);

            let parentDistance: Vector = new Vector(this.points[point.parentPoint].pos.x - this.xPos, this.points[point.parentPoint].pos.y - this.yPos);
            let angle: number = Math.atan2(parentDistance.y, parentDistance.x);
            let anglePrecentage: number = .3 + ((angle + Math.PI) / (Math.PI * 2)) * .4;

            let normalizedDistance = ownDistance;
            normalizedDistance.normalize((( anglePrecentage / this.amountOfLayers) * this.width * .5 ))

            ctx.lineTo(point.pos.x - normalizedDistance.x, point.pos.y - normalizedDistance.y);
            
            //draws arc to the coordinate of parent
            let clockwise: boolean = false;
            if (angle > ownAngle) {
                clockwise = (angle - ownAngle > Math.PI);

            } else {
                clockwise = (angle - ownAngle < Math.PI);
            }

            ctx.arc(this.xPos, this.yPos, (((point.data.layer - (1 + anglePrecentage)) / this.amountOfLayers) * this.width * .5 ), ownAngle, angle, clockwise);

            ctx.lineTo(this.points[point.parentPoint].pos.x, this.points[point.parentPoint].pos.y);
        }
        ctx.stroke();
        
    }
    public DrawLayerCircle() {

        for (let i = 0; i < this.amountOfLayers; i++) {
            this.ctx.beginPath();
            this.ctx.arc(this.xPos, this.yPos, 
                this.width * .5 - ((i / this.amountOfLayers) * this.width * .5 ) - 
            /* the code right moves all the circles a hlaf width inwards so that thep oints are alligned between them */ ((.5 / this.amountOfLayers) * this.width * .5 ), 
            0, 2 * Math.PI);
            this.ctx.stroke();
            // this.ctx.fill();
        }
    }
    public GetAmountOfCreatainLayer(layer: number): number {
        let result = 0;
        this.points.forEach(point => {
            if (point.data.layer == layer) {
                result++;
            }
        });
        return result;
    }
    public GetPointIndexWithID(id: string): number {
        for (let i = 0; i < this.points.length; i++) {
            if (this.points[i].data.id == id) {
                return i;
            }
        }
        return -1;
    }

    public GetPointsIndexWithParentID(id: string): number[] {
        let result: number[] = [];
        for (let i: number = 0; i < this.points.length; i++) {
            if (this.points[i].data.parentID == id) {
                result.push(i);
            }
        }
        return result;
    }

}
