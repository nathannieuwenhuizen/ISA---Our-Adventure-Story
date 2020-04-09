define(["require", "exports", "./branchTree", "./storyPartsHandeler", "jquery"], function (require, exports, branchTree_1, storyPartsHandeler_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var PARTID;
    var App = /** @class */ (function () {
        //the start function goes here
        function App() {
            this.fromShowed = false;
            this.checkCacheUpdate();
            this.checkBranchCanvasAndApply();
            this.editButtonEvents();
            this.checkStoryView();
            this.checkOrderBySelection();
            if (document.getElementsByClassName('createWrapper')[0]) {
                this.storyPartHandeler = new storyPartsHandeler_1.default();
            }
        }
        App.prototype.LoginEvents = function () {
            $('.form').find('input, textarea').on('keyup blur focus', function (e) {
                var $this = $(this), label = $this.prev('label');
                if (e.type === 'keyup') {
                    if ($this.val() === '') {
                        label.removeClass('active highlight');
                    }
                    else {
                        label.addClass('active highlight');
                    }
                }
                else if (e.type === 'blur') {
                    if ($this.val() === '') {
                        label.removeClass('active highlight');
                    }
                    else {
                        label.removeClass('highlight');
                    }
                }
                else if (e.type === 'focus') {
                    if ($this.val() === '') {
                        label.removeClass('highlight');
                    }
                    else if ($this.val() !== '') {
                        label.addClass('highlight');
                    }
                }
            });
            $('.tab a').on('click', function (e) {
                e.preventDefault();
                $(this).parent().addClass('active');
                $(this).parent().siblings().removeClass('active');
                var target = $(this).attr('href');
                $('.tab-content > div').not(target).hide();
                $(target).fadeIn(600);
            });
        };
        App.prototype.checkCacheUpdate = function () {
        };
        App.prototype.checkBranchCanvasAndApply = function () {
            if (document.getElementById("branchCanvas") != null) {
                this.branchCanvas = document.getElementById("branchCanvas");
                this.branchTree = new branchTree_1.default(this.branchCanvas, document.getElementById("HbranchCanvas"));
                console.log($('#branchCanvas'));
            }
            else if (document.getElementsByClassName("form")[0] != null) {
                this.LoginEvents();
            }
        };
        App.prototype.checkOrderBySelection = function () {
            if (document.getElementById('orderByOptions')) {
                var orderByObject_1 = document.getElementById('orderByOptions');
                orderByObject_1.onchange = function () {
                    window.location.href = './storyinfo.php?ID=' + STORYID + '&offset=0&orderby=' + orderByObject_1.value;
                };
            }
        };
        App.prototype.editButtonEvents = function () {
            var _this = this;
            if (document.getElementsByClassName('editButton')[0]) {
                this.storyPage = document.getElementsByClassName('storywrapper')[0];
                this.ShowEditButton = document.getElementsByClassName('editButton')[0];
                this.ShowEditButton.addEventListener('click', function () {
                    _this.ToggleEditForm(true);
                });
                this.hideEditButton = document.getElementsByClassName('hideButton')[0];
                this.hideEditButton.addEventListener('click', function () {
                    _this.ToggleEditForm(false);
                });
                this.editForm = document.getElementsByClassName('updateWrapper')[0];
            }
        };
        App.prototype.checkStoryView = function () {
            var _this = this;
            if (document.getElementsByClassName('createWrapper')[0]) {
                //new part
                this.showNewPartButton = document.getElementsByClassName('createnewPartButton')[0];
                this.createForm = document.getElementsByClassName('createWrapper')[0];
                //the current part
                this.chooseMessage = document.getElementsByClassName('chooseMessage')[0];
                this.questionPanel = document.getElementsByClassName('questionPanel')[0];
                this.consequenceImage = document.getElementsByClassName('consequenceImage')[0];
                //edit current part
                this.updateEndValueCheckbox = document.getElementsByClassName('update_end')[0];
                this.starIcon = document.getElementsByClassName('starIcon')[0];
                this.starMessage = document.getElementById('starMessage');
                this.starIcon.addEventListener('click', function () {
                    if (!LOGGEDIN) {
                        return;
                    }
                    var result;
                    if (_this.starIcon.getAttribute("src") == "assets/img/star_empty.png") {
                        console.log("./assets/php/like/addLike.php?id=" + PARTID + "&story=" + STORYID);
                        result = App.setSQLData("./assets/php/like/addLike.php?id=" + PARTID + "&story=" + STORYID);
                        if (result.result != 0) {
                            _this.starIcon.setAttribute("src", "assets/img/star_full.png");
                        }
                    }
                    else {
                        result = App.setSQLData("./assets/php/like/removeLike.php?id=" + PARTID);
                        if (result.result != 0) {
                            _this.starIcon.setAttribute("src", "assets/img/star_empty.png");
                        }
                    }
                    _this.starMessage.innerHTML = result.message;
                });
                var objImg = new Image();
                objImg.src = this.consequenceImage.src;
                //console.log(objImg.src);
                if (!this.ContainsAny(objImg.src, ['jpeg', 'png', 'gif', 'jpg'])) {
                    //document.getElementsByClassName('duoWrapper')[0].classList.add('duoWrapperWithoutImg');
                }
                if (objImg.complete) {
                    this.consequenceImage.classList.add('show');
                }
                else {
                    document.getElementsByClassName('duoWrapper')[0].classList.add('duoWrapperWithoutImg');
                }
                document.title = TITLE + " | " + (OPTION != "" ? OPTION : "start of story");
                if (END == 1) {
                    this.showNewPartButton.classList.add('hide');
                    this.questionPanel.innerHTML = "<br><h3>this is the end of this branch</h3><br><br>";
                    this.updateEndValueCheckbox.checked = true;
                }
                if (START == 1) {
                    // this.chooseMessage.classList.add('hide');
                    //this.chooseMessage.innerHTML = "Start of the story!";
                }
                this.showNewPartButton.addEventListener('click', function () {
                    _this.fromShowed = !_this.fromShowed;
                    _this.ToggleShow();
                });
                document.onload = function () {
                    window.alert("Image loaded: " + _this.consequenceImage.complete);
                };
            }
        };
        App.prototype.ToggleShow = function () {
            console.log("click!");
            if (this.fromShowed) {
                this.createForm.classList.add('show');
                this.createForm.scrollIntoView({
                    behavior: 'smooth'
                });
                this.showNewPartButton.innerHTML = 'hide';
            }
            else {
                this.createForm.classList.remove('show');
                this.showNewPartButton.innerHTML = 'Create your own path!';
            }
        };
        App.prototype.ContainsAny = function (str, items) {
            for (var i in items) {
                var item = items[i];
                if (str.indexOf(item) > -1) {
                    return true;
                }
            }
            return false;
        };
        App.prototype.ToggleEditForm = function (show) {
            if (!show) {
                this.editForm.classList.add('hide');
                this.storyPage.classList.remove('hide');
            }
            else {
                this.editForm.classList.remove('hide');
                this.storyPage.classList.add('hide');
                console.log("test");
                this.createForm.classList.add('hide');
            }
        };
        App.getQueryVariable = function (variable) {
            var query = window.location.href.substring(1);
            var startVar = query.split('?');
            if (startVar.length > 1) {
                var vars = startVar[1].split('&');
                console.log('q', query, startVar, vars);
                for (var i = 0; i < vars.length; i++) {
                    var pair = vars[i].split('=');
                    console.log('pair', pair);
                    if (pair[0] === variable) {
                        return pair[1];
                    }
                }
            }
            return -1;
        };
        App.setSQLData = function (url) {
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
        return App;
    }());
    exports.default = App;
    window.addEventListener('load', function () {
        new App();
    }, false);
});
