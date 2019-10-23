import 'jquery';
import {
    Iproject
} from './data';
import BranchTree from './branchTree';
import PatreonObject from './patreon';
import StoryPartsHandeler from './storyPartsHandeler';
import ImportTree from './importTree';
let PARTID: any;
export default class App {

    //test
    public data: Iproject[];

    //story view
    public showNewPartButton: Element;
    public createForm: Element;
    public fromShowed: Boolean = false;
    public consequenceImage: Element;
    public chooseMessage: Element;
    public questionPanel: Element;

    public storyPage: Element;

    public updateEndValueCheckbox: Element;
    public ShowEditButton: Element;
    public hideEditButton: Element;
    public editForm: Element;

    public starIcon: Element;
    public starMessage: Element;

    public patronObj: PatreonObject;

    //branch tree
    public branchCanvas: Element;
    public branchTree: BranchTree;

    public storyPartHandeler: StoryPartsHandeler;
    //the start function goes here
    constructor() {

        this.checkCacheUpdate();
        this.checkBranchCanvasAndApply();

        this.editButtonEvents();
        this.checkStoryView();

        if (document.getElementsByClassName('createWrapper')[0]) {
            this.storyPartHandeler = new StoryPartsHandeler();
        }        
    }

    public LoginEvents() {
        $('.form').find('input, textarea').on('keyup blur focus', function (e) {

            let $this = $(this),
                label = $this.prev('label');

            if (e.type === 'keyup') {
                if ($this.val() === '') {
                    label.removeClass('active highlight');
                } else {
                    label.addClass('active highlight');
                }
            } else if (e.type === 'blur') {
                if ($this.val() === '') {
                    label.removeClass('active highlight');
                } else {
                    label.removeClass('highlight');
                }
            } else if (e.type === 'focus') {

                if ($this.val() === '') {
                    label.removeClass('highlight');
                } else if ($this.val() !== '') {
                    label.addClass('highlight');
                }
            }

        });

        $('.tab a').on('click', function (e) {

            e.preventDefault();

            $(this).parent().addClass('active');
            $(this).parent().siblings().removeClass('active');

            let target = $(this).attr('href');

            $('.tab-content > div').not(target).hide();

            $(target).fadeIn(600);

        });
    }

    public checkCacheUpdate() {

    }

    public checkBranchCanvasAndApply() {
        if (document.getElementById("branchCanvas") != null) {

            this.branchCanvas = document.getElementById("branchCanvas");
            this.branchTree = new BranchTree(this.branchCanvas, document.getElementById("HbranchCanvas"));
            console.log($('#branchCanvas'));
        } else if (document.getElementsByClassName("form")[0] != null) {
            this.LoginEvents();
        }

    }

    public editButtonEvents() {
        if (document.getElementsByClassName('editButton')[0]) {
            this.storyPage = document.getElementsByClassName('storywrapper')[0];

            this.ShowEditButton = document.getElementsByClassName('editButton')[0];
            this.ShowEditButton.addEventListener('click', () => {
                this.ToggleEditForm(true)
            });
            this.hideEditButton = document.getElementsByClassName('hideButton')[0];
            this.hideEditButton.addEventListener('click', () => {
                this.ToggleEditForm(false)
            });
            this.editForm = document.getElementsByClassName('updateWrapper')[0];
        }
    }

    public checkStoryView() {
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

            this.starIcon.addEventListener('click', () => { 
                if (!LOGGEDIN) { return; }
                let result;
                if (this.starIcon.getAttribute("src") == "assets/img/star_empty.png") {
                    console.log("./assets/php/like/addLike.php?id=" + PARTID +  "&story=" + STORYID);
                    result = App.setSQLData("./assets/php/like/addLike.php?id=" + PARTID +  "&story=" + STORYID);
                    if (result.result != 0) {
                        this.starIcon.setAttribute("src", "assets/img/star_full.png");
                    }
                } else {
                    result = App.setSQLData("./assets/php/like/removeLike.php?id=" + PARTID);
                    if (result.result != 0) {
                        this.starIcon.setAttribute("src", "assets/img/star_empty.png");
                    }
                }
                this.starMessage.innerHTML = result.message;

            });


            let objImg = new Image();
            objImg.src = this.consequenceImage.src;
            //console.log(objImg.src);
            if (!this.ContainsAny(objImg.src, ['jpeg', 'png', 'gif', 'jpg'])) {
                //document.getElementsByClassName('duoWrapper')[0].classList.add('duoWrapperWithoutImg');
            }
            if (objImg.complete) {
                this.consequenceImage.classList.add('show');
            } else {
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

            this.showNewPartButton.addEventListener('click', () => {
                this.fromShowed = !this.fromShowed;
                this.ToggleShow();
            });
            document.onload = () => {
                window.alert("Image loaded: " + this.consequenceImage.complete);
            };
        }
    }
    public ToggleShow() {
        console.log("click!");
        if (this.fromShowed) {
            this.createForm.classList.add('show');
            this.createForm.scrollIntoView({
                behavior: 'smooth'
            });
            this.showNewPartButton.innerHTML = 'hide';
        } else {
            this.createForm.classList.remove('show');
            this.showNewPartButton.innerHTML = 'Create your own path!';
        }
    }

    public ContainsAny(str: string, items: any) {
        for (var i in items) {
            var item = items[i];
            if (str.indexOf(item) > -1) {
                return true;
            }
        }
        return false;
    }

    public ToggleEditForm(show: boolean) {
        if (!show) {
            this.editForm.classList.add('hide');
            this.storyPage.classList.remove('hide');

        } else {
            this.editForm.classList.remove('hide');
            this.storyPage.classList.add('hide');
            console.log("test");
            this.createForm.classList.add('hide');
        }
    }

    public static getQueryVariable(variable: string): any {
        let query: any = window.location.href.substring(1);
        let startVar: any = query.split('?');
        if (startVar.length > 1) {
            let vars: any = startVar[1].split('&');
            console.log('q', query, startVar, vars);

            for (let i: number = 0; i < vars.length; i++) {
                let pair: any = vars[i].split('=');
                console.log('pair', pair);
                if (pair[0] === variable) {
                    return pair[1];
                }
            }
        }
        return -1;
    }

    public static setSQLData(url: string): any {
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
}

window.addEventListener('load', () => {
    new App();
}, false);
