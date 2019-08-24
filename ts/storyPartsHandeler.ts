import 'jquery';
// import App from './app';
export default class StoryPartsHandeler {

    public storyParts: StoryPartObject[];
    public parentDOMObject: Element;
    public wrapper: Element;
    public static instance: StoryPartsHandeler;

    public editForm: Element;
    public hideEditButton: Element;
    public createForm: Element;
    public createParentObject: StoryPartObject;

    //the start function goes here
    constructor() {
        if (!StoryPartsHandeler.instance) {
            StoryPartsHandeler.instance = this;
        }
        this.storyParts = [];


        this.createForm = document.getElementsByClassName('createWrapper')[0];
        this.editForm = document.getElementsByClassName('updateWrapper')[0];
        this.hideEditButton = document.getElementsByClassName('hideButton')[0];
        this.hideEditButton.addEventListener('click', () => {
            this.showEditPart(null, false);
        });

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
    public showHiddenPart() {
        this.storyParts.forEach((part: StoryPartObject) => {
            if (part.domObject.classList.contains('hide')) {
                console.log("hide", part.domObject);
                part.domObject.classList.remove('hide');
            }
        }); 
    }
    public showEditPart(data: StoryPartObject, show: boolean) {

        if (!show) {
            this.editForm.classList.add('hide');
            this.showHiddenPart();
        } else {
            this.showHiddenPart();
            data.domObject.classList.add('hide');

            let element: Element = this.wrapper.insertBefore(this.editForm, data.domObject);
            element.classList.remove('hide');

            element.getElementsByTagName("input")[0].value = data.data.option_text;
            element.getElementsByTagName("input")[1].checked = data.data.end == 1;
            element.getElementsByTagName("input")[2].value = data.data.question_text;
            element.getElementsByTagName("input")[3].value = data.data.image;
            element.getElementsByTagName("input")[4].value = data.data.ID;
            element.getElementsByTagName("input")[5].value = data.data.storyID;
            element.getElementsByTagName("input")[6].value = data.data.optionList;
            element.getElementsByTagName('textarea')[0].innerHTML = data.data.content_text;

        }
    }
    public toggleCfreateForm(object: StoryPartObject, show: boolean) {
        console.log("show", show);

        if (show) {
            this.createForm = this.insertAfter(this.createForm, object.domObject);
            this.createForm.classList.add('show');
            this.createForm.scrollIntoView({
                behavior: 'smooth'
            });
            if (this.createParentObject != null) {
                this.createParentObject.showNewPartButton.innerHTML = 'Create your own path!';
            }

            this.createParentObject = object;
            this.createParentObject.showNewPartButton.innerHTML = 'hide';
            console.log(this.createForm.getElementsByTagName('input'));
            this.createForm.getElementsByTagName('input')[4].value = this.createParentObject.data.layer;
            this.createForm.getElementsByTagName('input')[5].value = this.createParentObject.data.ID;
            // this.createForm.getElementsByTagName('input')[6].value = this.createParentObject.data.storyID;
            this.createForm.getElementsByTagName('input')[7].value = this.createParentObject.data.optionIDs;
            this.createForm.getElementsByTagName('input')[8].value = this.createParentObject.data.end + "";
        } else {
            this.createForm.classList.remove('show');
            if (this.createParentObject != null) {
                this.createParentObject.showNewPartButton.innerHTML = 'Create your own path!';
            }
        }
    }
    public insertAfter(newNode: Element, referenceNode: Element): Element {
        return referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
    }

    public scrollTo(element: Element) {
        $([document.documentElement, document.body]).animate({
            scrollTop: $(element).offset().top
        }, 500);
    }
    private loadHistory(currentID: number) {
        console.log("start load history");

        let dataArray: IPart[] = [];
        dataArray.push(this.getSQLData("assets/php/parthandeler/getPartObject.php?storypart=" + currentID));
        let currentDataStart = dataArray[0].start;

        let index = 0; 
        while (currentDataStart == 0 && index < 50) {
            index++;
            dataArray.push(this.getSQLData("assets/php/parthandeler/getPartObject.php?storypart=" + dataArray[dataArray.length - 1].parentID));
            currentDataStart = dataArray[dataArray.length - 1].start;
        }

        for (let i = dataArray.length- 1; i >= 0; i--) {
            this.loadPart(-1, null, i == 0, dataArray[i]);
        }
        
        // console.log(dataArray);
    }
    private removePart(index: number) {
        this.storyParts[index].removePart();
        this.storyParts.splice(index, 1);
    }
    
}



//--------------------------------------------------------------------------------------------------------------
//--------------------------------------Part object-------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------


class StoryPartObject {
    public domObject: Element; 
    public data: IPart;
    public consequenceImage: Element;
    public starIcon: Element;
    public starMessage: Element;
    public optionButtons: HTMLCollectionOf<HTMLAnchorElement>;
    public contentPanel: Element;
    public editButton: Element;
    public showNewPartButton: Element;
    public selectedButton: Element;
    constructor(_data: IPart, parentElement: Element) {

        this.data = _data;

        // console.log(this.data.content_text,  this.data.content_text.split('#039;').join("'"));
        this.data.content_text = this.data.content_text.split('#039;').join("'");
        this.data.content_text = this.data.content_text.split("&").join("");
        this.data.content_text = this.data.content_text.split("amp;").join("");
        this.data.content_text = this.data.content_text.split("quot;").join('"');
        this.data.option_text = this.data.option_text.split("/[&quot;]/").join('"');

        this.data.content_text = this.data.content_text.split('ABC').join( '<br>');
        this.constructPart(_data, parentElement);
        this.consequenceImage = this.domObject.getElementsByClassName('consequenceImage')[0];
        this.starIcon = this.domObject.getElementsByClassName('starIcon')[0];
        this.starMessage = this.domObject.getElementsByClassName('starMessage')[0];
        this.contentPanel = this.domObject.getElementsByClassName('contentPanel')[0];

        this.showNewPartButton = this.domObject.getElementsByClassName('createnewPartButton')[0];
        this.editButton = this.domObject.getElementsByClassName('editButton')[0];


        console.log(this.showNewPartButton);
        // this.contentPanel.innerHTML = this.data.content_text.split('ABC').join( '<br>'); 
        // console.log( _data.content_text, "and....      ",  this.contentPanel.innerHTML, this.data.content_text);

        this.optionButtons = this.domObject.getElementsByClassName('optionsList')[0].getElementsByTagName('a');
        for (let i = 0; i < this.optionButtons.length; i++) {
            let button: HTMLAnchorElement = this.optionButtons.item(i);
            button.addEventListener('click', () => {

                //this prevents href to reload the page but still show which pages you have visited.
                let href: string = button.getAttribute('href');
                button.removeAttribute('href');
                requestAnimationFrame(() =>{
                    button.setAttribute('href', href);
                });

                if (this.selectedButton != null) {
                    this.selectedButton.classList.remove('selected');
                }
                this.selectedButton = button;
                this.selectedButton.classList.add('selected');


                // console.log(button.id, StoryPartsHandeler.instance.storyParts);
                let test = location.protocol + '//' + location.host + location.pathname;
                console.log("urls:", window.location.href, test);

                this.processAjaxData(TITLE + " | " + button.innerHTML, test + "?storypart=" + button.id);
                StoryPartsHandeler.instance.showEditPart(null, false);
                StoryPartsHandeler.instance.toggleCfreateForm(null, false);
                StoryPartsHandeler.instance.loadPart(Number(button.id), this);
            });
        }

        // console.log(this.optionButtons.length);
        this.starIcon.addEventListener('click', () => { this.starButtonCLick() });
        this.editButton.addEventListener('click', () => { StoryPartsHandeler.instance.showEditPart(this, true); });
        this.showNewPartButton.addEventListener('click', () => {
            console.log("click");
            StoryPartsHandeler.instance.toggleCfreateForm(this, this.showNewPartButton.innerHTML != 'hide');
            
        });


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
        objImg.onerror = () => {
            this.domObject.getElementsByClassName('duoWrapper')[0].classList.add('duoWrapperWithoutImg');

        };
        if (objImg.complete) {
            // this.consequenceImage.classList.add('show');
        } else {
            // this.domObject.getElementsByClassName('duoWrapper')[0].classList.add('duoWrapperWithoutImg');
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

        if (ISCREATOR == 1) {
            data.canEdit = 1;
        }
        let content = '<div class="storywrapper">' +
        '<div class="storyHeader"> ' +
            '<div class="layerNumber">page '+data.layer+' <div class="author">' +
                    '<i>'+ (data.authorName != "anonymous" ? "written by" + data.authorName : "") + '</i></div>' +
            '</div>' +
            '<div class="storyTitle">' +
                '<a href="storyinfo.php?ID='+STORYID+'&offset=0">' +
                    '<h2>'+TITLE+'</h2>' +
                '</a>' +
                '<a href="branchtree.php?ID='+STORYID+'">Branch tree</a>' +
           ' </div>' +
      '  </div>' +
       ' <img class="editButton '+ ((data.canEdit == 1 && STATUS == 1) ? "" : "hide") + '" src="assets/img/edit_icon.png">' +
     '   <div id="starElement">' +
      '      <p>'+data.amountOfLikes+'</p>' +
        '    <img class="starIcon" src= '+(data.like == 1 ? "assets/img/star_full.png" : "assets/img/star_empty.png")+'>' +
          '  <div id="starMessage" class ="starMessage">'+ data.likeMessage+'</div>' +
       ' </div>' +
      '  <div class="chooseMessage">' +
         '  <a style="display:'+(((STARTID == data.parentID) || data.start == 1) ? "none" : "inline-block")+ '" ' +
         '       href="?storypart='+STARTID+'">Go to beginning</a>' +
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
  '          <div class="createnewPartButton '+(STATUS == 0 || data.end == 1? "hide" : "")+'">Create your own Path! </div>' + (data.status == 0 ? "<div class='closeMessage'>This story is closed, you cant add any more parts</div>" : "")+
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
    likeMessage: string;
    like: number;
    amountOfLikes: string;
    storyTitle: string;
    startID: number;
  }
  
