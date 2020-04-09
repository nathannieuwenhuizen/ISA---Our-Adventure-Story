define(["require", "exports", "./importTree", "jquery"], function (require, exports, importTree_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    // import App from './app';
    var StoryPartsHandeler = /** @class */ (function () {
        //the start function goes here
        function StoryPartsHandeler() {
            var _this = this;
            if (!StoryPartsHandeler.instance) {
                StoryPartsHandeler.instance = this;
            }
            this.storyParts = [];
            this.createForm = document.getElementsByClassName('createWrapper')[0];
            this.editForm = document.getElementsByClassName('updateWrapper')[0];
            this.hideEditButton = document.getElementsByClassName('hideButton')[0];
            this.hideEditButton.addEventListener('click', function () {
                _this.showEditPart(null, false);
            });
            this.importButton = document.getElementsByClassName("importButton")[0];
            this.importButton.addEventListener('click', this.toggleImportMenu.bind(this));
            this.wrapper = document.getElementsByClassName('wrapper')[0];
            this.parentDOMObject = document.createElement('div');
            this.wrapper.appendChild(this.parentDOMObject);
            this.loadPart(PARTID);
            // this.loadHistory(PARTID);
        }
        //retrieves the sql data from the databse.
        StoryPartsHandeler.prototype.getSQLData = function (url) {
            var request = new XMLHttpRequest();
            request.open('GET', url, false);
            var data;
            request.onload = function () {
                if (request.status >= 200 && request.status < 400) {
                    // Success!
                    console.log('Succes!');
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
        StoryPartsHandeler.prototype.toggleImportMenu = function () {
            console.log("import toggle: ", this.importButton.innerHTML);
            if (this.importButton.innerHTML == "hide") {
                //hide menu
                this.importButton.innerHTML = "import";
                if (this.importTree != null) {
                    this.importTree.Hide();
                    console.log("it is now hiding");
                }
            }
            else {
                console.log("it is opening");
                //open menu
                this.importButton.innerHTML = "hide";
                if (this.importTree == null) {
                    this.importCanvas = document.getElementById("importCanvas");
                    this.importTree = new importTree_1.default(document.getElementById("importCanvas"), document.getElementById("HbranchCanvas"), Number(this.importButton.id));
                }
                this.importTree.Show();
                if (this.importTree != null && this.createParentObject != null) {
                    this.importTree.SetMergePoint(this.createParentObject.data.ID);
                }
                this.importButton.scrollIntoView({
                    behavior: 'smooth'
                });
                console.log("it is opening");
            }
        };
        StoryPartsHandeler.prototype.removeLoadButton = function () {
            for (var i = 1; i < this.storyParts.length; i++) {
                this.storyParts[i].goToBeginningButton.setAttribute('style', 'display: none;');
            }
        };
        //returns a new part based on the id.
        StoryPartsHandeler.prototype.loadPart = function (id, parentObject, scrollTo, before) {
            if (parentObject === void 0) { parentObject = null; }
            if (scrollTo === void 0) { scrollTo = true; }
            if (before === void 0) { before = false; }
            var newPart;
            newPart = new StoryPartObject(this.getSQLData("assets/php/parthandeler/getPartObject.php?storypart=" + id), this.parentDOMObject, before);
            if (parentObject != null) {
                if (this.storyParts.indexOf(parentObject) + 1 < this.storyParts.length) {
                    for (var i = this.storyParts.length - 1; i > this.storyParts.indexOf(parentObject); i--) {
                        this.removePart(i);
                    }
                }
            }
            if (before) {
                this.storyParts.unshift(newPart);
            }
            else {
                this.storyParts.push(newPart);
            }
            if (scrollTo) {
                this.scrollTo(newPart.domObject);
            }
            this.removeLoadButton();
            return newPart;
        };
        //shows a hidden story part from the edit form
        StoryPartsHandeler.prototype.showHiddenPart = function () {
            this.storyParts.forEach(function (part) {
                if (part.domObject.classList.contains('hide')) {
                    console.log("hide", part.domObject);
                    part.domObject.classList.remove('hide');
                }
            });
        };
        //shows the edit part and hides the current editable part.
        StoryPartsHandeler.prototype.showEditPart = function (partObject, show) {
            if (!show) {
                this.editForm.classList.add('hide');
                this.showHiddenPart();
            }
            else {
                this.showHiddenPart();
                partObject.domObject.classList.add('hide');
                var element = this.insertAfter(this.editForm, partObject.domObject);
                element.classList.remove('hide');
                element.getElementsByTagName("input")[0].value = partObject.data.option_text;
                if (partObject.data.start == 1) {
                    element.getElementsByTagName("input")[0].required = "";
                }
                else {
                    console.log("toggle must be required");
                    element.getElementsByTagName("input")[0].required = "required";
                }
                element.getElementsByTagName("input")[1].checked = partObject.data.end == 1;
                element.getElementsByTagName("input")[2].value = partObject.data.question_text;
                element.getElementsByTagName("input")[3].value = partObject.data.image;
                element.getElementsByTagName("input")[4].value = partObject.data.ID;
                element.getElementsByTagName("input")[5].value = STORYID; // every part has the same story ofcourse!
                element.getElementsByTagName("input")[6].value = partObject.data.optionList;
                element.getElementsByTagName('textarea')[0].innerHTML = partObject.data.content_text;
            }
        };
        //toggles the visvility of the create form.
        StoryPartsHandeler.prototype.toggleCfreateForm = function (object, show) {
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
                this.importButton.innerHTML = "hide";
                this.toggleImportMenu();
                this.createParentObject = object;
                this.createParentObject.showNewPartButton.innerHTML = 'hide';
                console.log(this.createForm.getElementsByTagName('input'));
                this.createForm.getElementsByTagName('input')[4].value = this.createParentObject.data.layer;
                this.createForm.getElementsByTagName('input')[5].value = this.createParentObject.data.ID;
                // this.createForm.getElementsByTagName('input')[6].value = this.createParentObject.data.storyID;
                this.createForm.getElementsByTagName('input')[7].value = this.createParentObject.data.optionIDs;
                this.createForm.getElementsByTagName('input')[8].value = this.createParentObject.data.end + "";
            }
            else {
                this.createForm.classList.remove('show');
                if (this.createParentObject != null) {
                    this.createParentObject.showNewPartButton.innerHTML = 'Create your own path!';
                }
            }
        };
        //places a dom object before the referencenode.
        StoryPartsHandeler.prototype.insertAfter = function (newNode, referenceNode) {
            return referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
        };
        //jquery call to scroll to the element
        StoryPartsHandeler.prototype.scrollTo = function (element, time) {
            if (time === void 0) { time = 500; }
            $([document.documentElement, document.body]).animate({
                scrollTop: $(element).offset().top
            }, time);
        };
        //loads the history of the previous parts.
        StoryPartsHandeler.prototype.loadHistory = function (currentPart) {
            var _this = this;
            console.log("start load history", this.storyParts.indexOf(currentPart));
            if (this.storyParts.indexOf(currentPart) != 0) {
                return;
            }
            currentPart.goToBeginningButton.innerHTML = "loading...";
            console.log("start load history");
            setTimeout(function () {
                var currentDataStart = 0;
                var parentID = _this.storyParts[0].data.parentID;
                var chosenID = _this.storyParts[0].data.ID;
                var index = 0;
                while (currentDataStart == 0 && index < 5) {
                    index++;
                    var part = _this.loadPart(parentID, null, false, true);
                    part.selectButtonForPart(chosenID);
                    console.log(parentID);
                    currentDataStart = part.data.start;
                    parentID = part.data.parentID;
                    chosenID = part.data.ID;
                }
                _this.scrollTo(currentPart.domObject, 1);
                // this.scrollTo(this.storyParts[this.storyParts.length-1].domObject, 500);
            }, 1);
        };
        //removes a story part from the view and list.
        StoryPartsHandeler.prototype.removePart = function (index) {
            this.storyParts[index].removePart();
            this.storyParts.splice(index, 1);
        };
        return StoryPartsHandeler;
    }());
    exports.default = StoryPartsHandeler;
    //--------------------------------------------------------------------------------------------------------------
    //--------------------------------------Part object-------------------------------------------------------------
    //--------------------------------------------------------------------------------------------------------------
    var StoryPartObject = /** @class */ (function () {
        //start function of the object.
        function StoryPartObject(_data, parentElement, before) {
            var _this = this;
            this.data = _data;
            // console.log(this.data.content_text,  this.data.content_text.split('#039;').join("'"));
            this.data.content_text = this.filterSpecialChars(this.data.content_text);
            this.data.option_text = this.filterSpecialChars(this.data.option_text);
            this.data.question_text = this.filterSpecialChars(this.data.question_text);
            this.data.content_text = this.data.content_text.split('ABC').join('<br>');
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
            var _loop_1 = function (i) {
                var button = this_1.optionButtons.item(i);
                button.addEventListener('click', function () {
                    //this prevents href to reload the page but still show which pages you have visited.
                    var href = button.getAttribute('href');
                    button.removeAttribute('href');
                    requestAnimationFrame(function () {
                        button.setAttribute('href', href);
                    });
                    if (_this.selectedButton != null) {
                        _this.selectedButton.classList.remove('selected');
                    }
                    _this.selectedButton = button;
                    _this.selectedButton.classList.add('selected');
                    // console.log(button.id, StoryPartsHandeler.instance.storyParts);
                    var test = location.protocol + '//' + location.host + location.pathname;
                    console.log("urls:", window.location.href, test);
                    _this.processAjaxData(TITLE + " | " + button.innerHTML, test + "?storypart=" + button.id);
                    StoryPartsHandeler.instance.showEditPart(null, false);
                    StoryPartsHandeler.instance.toggleCfreateForm(null, false);
                    StoryPartsHandeler.instance.loadPart(Number(button.id), _this);
                });
            };
            var this_1 = this;
            for (var i = 0; i < this.optionButtons.length; i++) {
                _loop_1(i);
            }
            // console.log(this.optionButtons.length);
            this.starIcon.addEventListener('click', function () {
                _this.starButtonCLick();
            });
            this.editButton.addEventListener('click', function () {
                StoryPartsHandeler.instance.showEditPart(_this, true);
            });
            this.goToBeginningButton.addEventListener('click', function () {
                StoryPartsHandeler.instance.loadHistory(_this);
            });
            this.showNewPartButton.addEventListener('click', function () {
                console.log("click", StoryPartsHandeler.instance);
                StoryPartsHandeler.instance.toggleCfreateForm(_this, _this.showNewPartButton.innerHTML != 'hide');
            });
            this.checkImageURL();
        }
        StoryPartObject.prototype.filterSpecialChars = function (value) {
            var result = value;
            result = result.split('#039;').join("'");
            result = result.split("&").join("");
            result = result.split("amp;").join("");
            result = result.split("quot;").join('"');
            result = result.split("/[&quot;]/").join('"');
            return result;
        };
        //changes the url header and title.
        StoryPartObject.prototype.processAjaxData = function (pageTitle, urlPath) {
            document.title = pageTitle;
            window.history.pushState({
                "pageTitle": pageTitle
            }, "", urlPath);
        };
        //checks wether the image url is valid by loading it in an image object.
        StoryPartObject.prototype.checkImageURL = function () {
            var _this = this;
            var objImg = new Image();
            objImg.src = this.data.image;
            // console.log(objImg.src);
            objImg.onerror = function () {
                _this.domObject.getElementsByClassName('duoWrapper')[0].classList.add('duoWrapperWithoutImg');
            };
            if (objImg.complete) {
                // this.consequenceImage.classList.add('show');
            }
            else {
                // this.domObject.getElementsByClassName('duoWrapper')[0].classList.add('duoWrapperWithoutImg');
            }
        };
        //fires when the star button is clicked, adds/removes the part to the likes table.
        StoryPartObject.prototype.starButtonCLick = function () {
            if (!LOGGEDIN) {
                return;
            }
            // console.log("your are logged in");
            // console.log(this.starIcon.getAttribute("src"));
            var result;
            if (this.starIcon.getAttribute("src") == "assets/img/star_empty.png") {
                result = this.setSQLData("./assets/php/like/addLike.php?id=" + this.data.ID + "&story=" + STORYID);
                if (result != null) {
                    this.starIcon.setAttribute("src", "assets/img/star_full.png");
                }
            }
            else {
                result = this.setSQLData("./assets/php/like/removeLike.php?id=" + this.data.ID);
                if (result != null) {
                    this.starIcon.setAttribute("src", "assets/img/star_empty.png");
                }
            }
            console.log(result.message);
            this.starMessage.innerHTML = result.message;
        };
        //an sql call, currently used to add/remove likes from the database.
        StoryPartObject.prototype.setSQLData = function (url) {
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
        //constructs the actual DOM element
        StoryPartObject.prototype.constructPart = function (data, el, before) {
            if (ISCREATOR == 1) {
                data.canEdit = 1;
            }
            var content = '<div class="storywrapper">' +
                '<div class="storyHeader"> ' +
                '<div class="layerNumber">page ' + data.layer + ' <div class="author">' +
                '<i>' + (data.authorName != "anonymous" ? "written by... <b> <a href ='./users/profile.php?user="+ data.authorID +"'>" + data.authorName : "") + '</a></b></i></div>' +
                '</div>' +
                '<div class="storyTitle">' +
                '<a href="storyinfo.php?ID=' + STORYID + '&offset=0&orderby=0">' + 
                '<h2>' + TITLE + '</h2>' +
                '</a>' +
                '<a href="branchtree.php?ID=' + STORYID + '">Branch tree</a>' +
                ' </div>' +
                '  </div>' +
                ' <img class="editButton ' + ((data.canEdit == 1 && STATUS == 1) ? "" : "hide") + '" src="assets/img/edit_icon.png">' +
                '   <div id="starElement">' +
                '      <p>' + data.amountOfLikes + '</p>' +
                '    <img class="starIcon" src= ' + (data.like == 1 ? "assets/img/star_full.png" : "assets/img/star_empty.png") + '>' +
                '  <div id="starMessage" class ="starMessage">' + data.likeMessage + '</div>' +
                ' </div>' +
                '  <div class="chooseMessage">' +
                '  <a style="display:' + ((data.start == 1) ? "none" : "inline-block") + '" ' +
                '     class="goToBeginning">Load previous 5 parts</a>' +
                '     ' + (data.start == 0 ? "<i><p> You chose...<br> <b>" + data.option_text + " </b>  </p></i>" : "<i><h3>This is the start of the story</h3></i>") +
                '  </div>' +
                '  <hr>' +
                ' <div class="duoWrapper">' +
                '      <div class="leftside">' +
                '          <img class="consequenceImage show" src="' + data.image + '" />' +
                '          </div>' +
                '          <div class="rightside">' +
                '              <div class="contentPanel">' + data.content_text +
                '              </div>' +
                '          </div>' +
                '      </div>' +
                '      <hr>' +
                '      <i>' +
                '          <div class="questionPanel">' +
                '              ' + (data.end == 1 ? '<br><h3>this is the end of this branch</h3><br><br>' : '<p>' + data.question_text + '</p>') +
                '          </div>' +
                '      </i>' +
                '      <div class="optionsList">' +
                '          <ul>' + data.optionList +
                '          </ul>' +
                '          <div class="createnewPartButton ' + (STATUS == 0 || data.end == 1 ? "hide" : "") + '">Create your own path!</div>' + (STATUS == 0 ? "<div class='closeMessage'>This story is closed, you can't add/edit any parts :(</div>" : "") +
                '      </div>' +
                '  </div>';
            this.domObject = document.createElement('div');
            this.domObject.innerHTML = content;
            if (before) {
                el.insertBefore(this.domObject, el.childNodes[0]);
            }
            else {
                el.appendChild(this.domObject);
            }
            //   console.log("do you work?");
        };
        //highlights a selected button (called from loadhistory function)
        StoryPartObject.prototype.selectButtonForPart = function (id) {
            for (var i = 0; i < this.optionButtons.length; i++) {
                var button = this.optionButtons.item(i);
                // console.log(button.id);
                if (button.id == id) {
                    console.log("button found", id);
                    this.selectedButton = button;
                    this.selectedButton.classList.add('selected');
                }
            }
        };
        //destroy function 
        StoryPartObject.prototype.removePart = function () {
            var _this = this;
            this.starIcon.removeEventListener('click', function () {
                _this.starButtonCLick();
            });
            // document.removeChild(this.domObject);
            this.domObject.remove();
            this.domObject.innerHTML = "";
        };
        return StoryPartObject;
    }());
});
