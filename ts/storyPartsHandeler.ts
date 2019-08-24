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
        
        this.loadPart(PARTID);

        // this.loadHistory(PARTID);
    }

    //retrieves the sql data from the databse.
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

    public removeLoadButton() {
        for( let i = 1; i < this.storyParts.length; i++) {
            this.storyParts[i].goToBeginningButton.setAttribute('style', 'display: none;')
        }
    }

    //returns a new part based on the id.
    public loadPart(id:number, parentObject: StoryPartObject = null, scrollTo: boolean = true, before: boolean = false): StoryPartObject {
        let newPart: StoryPartObject;
        newPart = new StoryPartObject(this.getSQLData("assets/php/parthandeler/getPartObject.php?storypart=" + id), this.parentDOMObject, before);

        if (parentObject != null) {
            if (this.storyParts.indexOf(parentObject) + 1 < this.storyParts.length) {
                for (let i = this.storyParts.length - 1; i > this.storyParts.indexOf(parentObject); i--) {
                    this.removePart(i);
                }
            }
        }
        if (before) {
            this.storyParts.unshift(newPart);

        } else {
            this.storyParts.push(newPart);
        }

        if (scrollTo) {
            this.scrollTo(newPart.domObject);
        }
        this.removeLoadButton();
        return newPart;

    }

    //shows a hidden story part from the edit form
    public showHiddenPart() {
        this.storyParts.forEach((part: StoryPartObject) => {
            if (part.domObject.classList.contains('hide')) {
                console.log("hide", part.domObject);
                part.domObject.classList.remove('hide');
            }
        }); 
    }

    //shows the edit part and hides the current editable part.
    public showEditPart(data: StoryPartObject, show: boolean) {

        if (!show) {
            this.editForm.classList.add('hide');
            this.showHiddenPart();
        } else {
            this.showHiddenPart();
            data.domObject.classList.add('hide');

            let element: any = this.insertAfter(this.editForm, data.domObject); 
            element.classList.remove('hide');

            element.getElementsByTagName("input")[0].value = data.data.option_text;
            element.getElementsByTagName("input")[1].checked = data.data.end == 1;
            element.getElementsByTagName("input")[2].value = data.data.question_text;
            element.getElementsByTagName("input")[3].value = data.data.image;
            element.getElementsByTagName("input")[4].value = data.data.ID;
            element.getElementsByTagName("input")[5].value = STORYID;
            element.getElementsByTagName("input")[6].value = data.data.optionList;
            element.getElementsByTagName('textarea')[0].innerHTML = data.data.content_text;

        }
    }

    //toggles the visvility of the create form.
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

    //places a dom object before the referencenode.
    public insertAfter(newNode: Element, referenceNode: Element): Element {
        return referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
    }

    //jquery call to scroll to the element
    public scrollTo(element: Element, time: number = 500) {
        $([document.documentElement, document.body]).animate({
            scrollTop: $(element).offset().top
        }, time);
    }

    //loads the history of the previous parts.
    public loadHistory(currentPart: StoryPartObject) {

        console.log("start load history", this.storyParts.indexOf(currentPart));

        if (this.storyParts.indexOf(currentPart) != 0) {
            return;
        }

        console.log("start load history");

        let currentDataStart = 0;
        let parentID = this.storyParts[0].data.parentID;
        let chosenID = this.storyParts[0].data.ID;
        let index = 0; 
        while (currentDataStart == 0 && index < 50) {
            index++;

            let part: StoryPartObject = this.loadPart(parentID, null, false, true);
            part.selectButtonForPart(chosenID);
            console.log(parentID);
            currentDataStart = part.data.start;
            parentID =  part.data.parentID;
            chosenID = part.data.ID;
        }
            this.scrollTo(this.storyParts[this.storyParts.length-1].domObject, 1);
            // this.scrollTo(this.storyParts[this.storyParts.length-1].domObject, 500);
    }

    //removes a story part from the view and list.
    private removePart(index: number) {
        this.storyParts[index].removePart();
        this.storyParts.splice(index, 1);
    }
    
}



//--------------------------------------------------------------------------------------------------------------
//--------------------------------------Part object-------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------

class StoryPartObject {
    //global objects and data
    public domObject: Element; 
    public data: IPart;

    //induvidual story part elements
    public consequenceImage: Element;
    public starIcon: Element;
    public starMessage: Element;
    public optionButtons: HTMLCollectionOf<HTMLAnchorElement>;
    public contentPanel: Element;
    public editButton: Element;
    public showNewPartButton: Element;
    public selectedButton: Element;

    public goToBeginningButton: Element;
    //start function of the object.
    constructor(_data: IPart, parentElement: Element, before: boolean) {
        this.data = _data;

        // console.log(this.data.content_text,  this.data.content_text.split('#039;').join("'"));
        this.data.content_text = this.data.content_text.split('#039;').join("'");
        this.data.content_text = this.data.content_text.split("&").join("");
        this.data.content_text = this.data.content_text.split("amp;").join("");
        this.data.content_text = this.data.content_text.split("quot;").join('"');
        this.data.option_text = this.data.option_text.split("/[&quot;]/").join('"');

        this.data.content_text = this.data.content_text.split('ABC').join( '<br>');
        
        this.constructPart(_data, parentElement, before);

        this.consequenceImage = this.domObject.getElementsByClassName('consequenceImage')[0];
        this.starIcon = this.domObject.getElementsByClassName('starIcon')[0];
        this.starMessage = this.domObject.getElementsByClassName('starMessage')[0];
        this.contentPanel = this.domObject.getElementsByClassName('contentPanel')[0];
        this.goToBeginningButton = this.domObject.getElementsByClassName('goToBeginning')[0];


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
        this.goToBeginningButton.addEventListener('click', () => { StoryPartsHandeler.instance.loadHistory(this); });
        this.showNewPartButton.addEventListener('click', () => {
            console.log("click");
            StoryPartsHandeler.instance.toggleCfreateForm(this, this.showNewPartButton.innerHTML != 'hide');
            
        });


        this.checkImageURL();

    }

    //changes the url header and title.
    private processAjaxData(pageTitle: any, urlPath: string){ 
        document.title = pageTitle;
        window.history.pushState({"pageTitle":pageTitle},"", urlPath);
    }

    //checks wether the image url is valid by loading it in an image object.
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

    //fires when the star button is clicked, adds/removes the part to the likes table.
    public starButtonCLick() {
        if (!LOGGEDIN) { return; }
        // console.log("your are logged in");
        // console.log(this.starIcon.getAttribute("src"));
        let result;
        if (this.starIcon.getAttribute("src") == "assets/img/star_empty.png") {
            result = this.setSQLData("./assets/php/like/addLike.php?id=" + this.data.ID +  "&story=" + STORYID);
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

    //an sql call, currently used to add/remove likes from the database.
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


    //constructs the actual DOM element
    public constructPart(data: IPart, el: Element, before) {

        if (ISCREATOR == 1) {
            data.canEdit = 1;
        }
        let content = '<div class="storywrapper">' +
        '<div class="storyHeader"> ' +
            '<div class="layerNumber">page '+data.layer+' <div class="author">' +
                    '<i>'+ (data.authorName != "anonymous" ? "written by <b>" + data.authorName : "") + '</b></i></div>' +
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
         '  <a style="display:'+(( data.start == 1) ? "none" : "inline-block")+ '" ' +
         '     class="goToBeginning">Load previous parts</a>' +
       '     '+ (data.start == 0 ? "<i><p> You chose...<br> <b>"+data.option_text+" </b>  </p></i>" : "<i><h3>This is the start of the story</h3></i>") +
      '  </div>' +
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

  if (before) {
      el.insertBefore(this.domObject, el.childNodes[0]);
} else {
    el.appendChild(this.domObject);
}
//   console.log("do you work?");

    }

    //highlights a selected button (called from loadhistory function)
    public selectButtonForPart(id: any) {
        for (let i = 0; i < this.optionButtons.length; i++) {
            let button: any = this.optionButtons.item(i);
            // console.log(button.id);
            if (button.id == id) {
                console.log("button found", id);
                this.selectedButton = button;
                this.selectedButton.classList.add('selected');

            }
        }

    }

    //destroy function 
    public removePart() {
        this.starIcon.removeEventListener('click', () => { this.starButtonCLick() });
        // document.removeChild(this.domObject);

        this.domObject.remove();
        this.domObject.innerHTML = "";
    }
}


//interface for the data object.
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
  
