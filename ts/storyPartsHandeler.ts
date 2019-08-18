import 'jquery';
// import App from './app';
export default class StoryPartsHandeler {

    public storyParts: StoryPartObject[];
    public parentDOMObject: Element;
    public wrapper: Element;
    //the start function goes here
    constructor() {
        this.storyParts = [];


        this.wrapper = document.getElementsByClassName('wrapper')[0];
        this.parentDOMObject = document.createElement('div');
        this.wrapper.appendChild(this.parentDOMObject);
        console.log(PARTID);
        console.log(this.getSQLData("assets/php/parthandeler/getPartObject.php?storypart=" + PARTID));
        new StoryPartObject(this.getSQLData("assets/php/parthandeler/getPartObject.php?storypart=" + PARTID), this.wrapper);
        // this.canvas.onmousemove = event => this.MouseMove(event);
        // addEventListener("mousemove", event => this.MouseMove(event));
        // addEventListener("click", () => this.MouseClick());
    }
    

    private getSQLData(url: string): any {
        let request: XMLHttpRequest = new XMLHttpRequest();
        request.open('GET', url, false);
        let data: any;
        request.onload = () => {
            if (request.status >= 200 && request.status < 400) {
                // Success!
                console.log('Succes!');
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
    private loadPart() {

    }
    private loadHistory() {

    }
    private removeAllForwardPartsFrom() {

    }
    
}
class StoryPartObject {
    public domObject: Element;

    constructor(_data: IPart, parentElement: Element) {

        console.log( _data.optionList, parentElement);
        this.constructPart(_data, parentElement);
    }
    public constructPart(data: IPart, el: Element) {

        let content = '<div class="storywrapper">' +
        '<div class="storyHeader"> ' +
            '<div class="layerNumber">page '+data.layer+' <div class="author">' +
                    '<i>'+ (data.authorName != "anonymous" ? "written by" + data.authorName : "") + '</i></div>' +
            '</div>' +
            '<div class="storyTitle">' +
                '<a href="storyinfo.php?ID='+data.storyID+'&offset=0">' +
                    '<h2>'+data.storyTitle+'</h2>' +
                '</a>' +
                '<a href="branchtree.php?ID='+data.storyID+'">Branch tree</a>' +
           ' </div>' +
      '  </div>' +
       ' <img class="editButton '+ (data.canEdit == 0 ? "hide" : "") + '" src="assets/img/edit_icon.png">' +
     '   <div id="starElement">' +
      '      <p>'+data.amountOfLikes+'</p>' +
        '    <img class="starIcon" src= '+(data.like == 1 ? "assets/img/star_full.png" : "assets/img/star_empty.png")+'>' +
          '  <div id="starMessage">'+ data.likeMessage+'</div>' +
       ' </div>' +
      '  <div class="chooseMessage">' +
         '  <a style="display:'+(((data.startID == data.parentID) || data.start) ? "none" : "inline-block")+ '" ' +
         '       href="?storypart='+data.startID+'">Go to beginning</a>' +
        '    <a href="?storypart='+data.parentID+'" style="display:'+(data.start ? "none" : "")+' ">Go back</a>' +
       '     '+ (data.start == 0 ? "<i><p> You chose...<br> <b>"+data.option_text+" </b>  </p></i>" : "<i><h3>This is the start of the story</h3></i>") +
      '  </div>' +
      '  <br>' +
      '  <hr>' +
       ' <div class="duoWrapper">' +
      '      <div class="leftside">' +
      '          <img class="consequenceImage show" src="'+data.image+'" />' +
  '          </div>' +
  '          <div class="rightside">' +
  '              <div class="contentPanel">' + data.content_text +
  '              </div>' +
  '          </div>' +
  '      </div>' +
  '      <hr>' +
  '      <i>' +
  '          <div class="questionPanel">' +
  '              <p>'+data.question_text+'</p>' +
  '          </div>' +
  '      </i>' +
  '      <div class="optionsList">' +
  '          <ul>' + data.optionList +
  '          </ul>' +
  '          <div class="createnewPartButton '+(data.status == 0 ? "hide" : "")+'">Create your own Path! </div>' + (data.status == 0 ? "This story is closed, you cant add any more parts" : "")+
  '      </div>' +
  '  </div>';

  this.domObject =   document.createElement('div');
  this.domObject.innerHTML = content;
  el.appendChild(this.domObject);
  console.log("do you work?");

    }
    public removePart() {

    }
}

export interface IPart {
    ID: string;
    start: number;
    end: string;
    option_text: string;
    content_text: string;
    question_text: string;
    optionIDs: string;
    layer: string;
    image: string;
    parentID: number;
    storyID: string;
    authorName: string;
    optionList: string;
    canEdit: number;
    status: number;
    likeMessage: string;
    like: number;
    amountOfLikes: string;
    storyTitle: string;
    startID: number;
  }
  
