import 'jquery';
// import App from './app';
export default class StoryPartsHandeler {

    public storyParts: StoryPartObject[];
    public parentDOMObject: Element;
    public wrapper: Element;
    public static instance: StoryPartsHandeler;
    //the start function goes here
    constructor() {
        if (!StoryPartsHandeler.instance) {
            StoryPartsHandeler.instance = this;
        }
        this.storyParts = [];


        this.wrapper = document.getElementsByClassName('wrapper')[0];
        this.parentDOMObject = document.createElement('div');
        this.wrapper.appendChild(this.parentDOMObject);
        
        // this.loadPart(PARTID);
        this.loadHistory(PARTID);
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
    public loadPart(id:number, parentObject: StoryPartObject = null, scrollTo: boolean = true, data: IPart = null) {
        let newPart: StoryPartObject;
        if (data == null) {
             newPart = new StoryPartObject(this.getSQLData("assets/php/parthandeler/getPartObject.php?storypart=" + id), this.wrapper);
        } else {
            newPart = new StoryPartObject(data, this.wrapper);
        }
        if (parentObject != null) {
            if (this.storyParts.indexOf(parentObject) + 1 < this.storyParts.length) {
                for (let i = this.storyParts.length - 1; i > this.storyParts.indexOf(parentObject); i--) {
                    console.log("index of: ", i, "length: ", this.storyParts.length);
                    this.removePart(i);
                }
            }
        }
        this.storyParts.push(newPart);
        if (scrollTo) {
            this.scrollTo(newPart.domObject);
        }
    }
    public scrollTo(element: Element) {
        $([document.documentElement, document.body]).animate({
            scrollTop: $(element).offset().top
        }, 500);
    }
    private loadHistory(startID: number) {
        console.log("start load history");

        let dataArray: IPart[] = [];
        dataArray.push(this.getSQLData("assets/php/parthandeler/getPartObject.php?storypart=" + startID));
        let currentDataStart = dataArray[0].start;

        let index = 0; 
        while (currentDataStart == 0 && index < 5) {
            index++;
            dataArray.push(this.getSQLData("assets/php/parthandeler/getPartObject.php?storypart=" + dataArray[dataArray.length - 1].parentID));
            currentDataStart = dataArray[dataArray.length - 1].start;
        }

        for (let i = dataArray.length- 1; i >= 0; i--) {
            this.loadPart(-1, null, false, dataArray[i]);
        }
        
        // console.log(dataArray);
    }
    private removePart(index: number) {
        this.storyParts[index].removePart();
        this.storyParts.splice(index, 1);
    }
    
}
class StoryPartObject {
    public domObject: Element;
    public data: IPart;
    public consequenceImage: Element;
    public starIcon: Element;
    public starMessage: Element;
    public optionButtons: HTMLCollectionOf<HTMLAnchorElement>;
    public contentPanel: Element;
    constructor(_data: IPart, parentElement: Element) {

        this.data = _data;

        console.log(this.data);
        this.data.content_text = this.data.content_text.split('ABC').join( '<br>');
        this.constructPart(_data, parentElement);
        this.consequenceImage = this.domObject.getElementsByClassName('consequenceImage')[0];
        this.starIcon = this.domObject.getElementsByClassName('starIcon')[0];
        this.starMessage = this.domObject.getElementsByClassName('starMessage')[0];
        this.contentPanel = this.domObject.getElementsByClassName('contentPanel')[0];

        // this.contentPanel.innerHTML = this.data.content_text.split('ABC').join( '<br>'); 
        console.log( _data.content_text, "and....      ",  this.contentPanel.innerHTML, this.data.content_text);

        this.optionButtons = this.domObject.getElementsByClassName('optionsList')[0].getElementsByTagName('a');
        for (let i = 0; i < this.optionButtons.length; i++) {
            let button: HTMLAnchorElement = this.optionButtons.item(i);
            button.removeAttribute('href');
            button.addEventListener('click', () => {
                // console.log(button.id, StoryPartsHandeler.instance.storyParts);
                let test = location.protocol + '//' + location.host + location.pathname;
                console.log("urls:", window.location.href, test);

                this.processAjaxData("option |" + button.id, test + "?storypart=" + button.id);

                StoryPartsHandeler.instance.loadPart(Number(button.id), this);
            });
        }
        // console.log(this.optionButtons.length);
        this.starIcon.addEventListener('click', () => { this.starButtonCLick() });

        this.checkImageURL();

    }

    private processAjaxData(pageTitle: any, urlPath: string){ 
        document.title = pageTitle;
        window.history.pushState({"pageTitle":pageTitle},"", urlPath);
    }

    public checkImageURL() {
        let objImg = new Image();
        objImg.src = this.data.image;
        // console.log(objImg.src);
        if (objImg.complete) {
            this.consequenceImage.classList.add('show');
        } else {
            this.domObject.getElementsByClassName('duoWrapper')[0].classList.add('duoWrapperWithoutImg');
        }
    }
    public starButtonCLick() {
        if (!LOGGEDIN) { return; }
        // console.log("your are logged in");
        // console.log(this.starIcon.getAttribute("src"));
        let result;
        if (this.starIcon.getAttribute("src") == "assets/img/star_empty.png") {
            result = this.setSQLData("./assets/php/like/addLike.php?id=" + this.data.ID +  "&story=" + this.data.storyID);
            if (result != null) {
                this.starIcon.setAttribute("src", "assets/img/star_full.png");
            }
        } else {
            result = this.setSQLData("./assets/php/like/removeLike.php?id=" + this.data.ID);
            if (result != null) {
                this.starIcon.setAttribute("src", "assets/img/star_empty.png");
            }
        }
        console.log(result.message);
        this.starMessage.innerHTML = result.message;
    }
    private setSQLData(url: string): any {
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
          '  <div id="starMessage" class ="starMessage">'+ data.likeMessage+'</div>' +
       ' </div>' +
      '  <div class="chooseMessage">' +
         '  <a style="display:'+(((data.startID == data.parentID) || data.start == 1) ? "none" : "inline-block")+ '" ' +
         '       href="?storypart='+data.startID+'">Go to beginning</a>' +
        '    <a href="?storypart='+data.parentID+'" style="display:'+(data.start == 1 ? "none" : "")+' ">Go back</a>' +
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
  '              '+(data.end == 1 ? '<br><h3>this is the end of this branch</h3><br><br>' :'<p>'+data.question_text+'</p>') +
  '          </div>' +
  '      </i>' +
  '      <div class="optionsList">' +
  '          <ul>' + data.optionList +
  '          </ul>' +
  '          <div class="createnewPartButton '+(data.status == 0 || data.end == 1? "hide" : "")+'">Create your own Path! </div>' + (data.status == 0 ? "This story is closed, you cant add any more parts" : "")+
  '      </div>' +
  '  </div>';

  this.domObject =   document.createElement('div');
  this.domObject.innerHTML = content;
  el.appendChild(this.domObject);
//   console.log("do you work?");

    }
    public removePart() {
        this.starIcon.removeEventListener('click', () => { this.starButtonCLick() });
        // document.removeChild(this.domObject);

        this.domObject.remove();
        this.domObject.innerHTML = "";
    }
}

export interface IPart {
    ID: string;
    start: number;
    end: number;
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
  
