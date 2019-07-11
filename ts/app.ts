import 'jquery';
import {
    Iproject
} from './data';
import BranchTree from './branchTree';

export default class App {

    public data: Iproject[];
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

    public branchCanvas: Element;
    public branchTree: BranchTree;

    //the start function goes here
    constructor() {
        
        window.applicationCache.addEventListener('updateready', () => {
            window.applicationCache.update();
            console.log("cache update!");

        });;
        
        if (window.applicationCache.status === window.applicationCache.UPDATEREADY) {
            console.log("cache update!");
            window.applicationCache.update();
        } else {
            console.log("no cache update!");
        }

        if (document.getElementById("branchCanvas") != null) {

            this.branchCanvas = document.getElementById("branchCanvas");
            this.branchTree = new BranchTree(this.branchCanvas, document.getElementById("HbranchCanvas"));
            console.log($('#branchCanvas'));
        }

        this.data = this.loadFile('./assets/projects.json');

        //new part
        this.showNewPartButton = document.getElementsByClassName('createnewPartButton')[0];
        this.createForm = document.getElementsByClassName('createWrapper')[0];

        //the current part
        this.chooseMessage = document.getElementsByClassName('chooseMessage')[0];
        this.questionPanel = document.getElementsByClassName('questionPanel')[0];
        this.consequenceImage = document.getElementsByClassName('consequenceImage')[0];

        //edit current part
        this.updateEndValueCheckbox = document.getElementsByClassName('update_end')[0];
        this.ShowEditButton = document.getElementsByClassName('editButton')[0];
        this.ShowEditButton.addEventListener('click', () => {
            this.ToggleEditForm(true)
        });
        this.hideEditButton = document.getElementsByClassName('hideButton')[0];
        this.hideEditButton.addEventListener('click', () => {
            this.ToggleEditForm(false)
        });
        this.editForm = document.getElementsByClassName('updateWrapper')[0];



        this.storyPage = document.getElementsByClassName('storywrapper')[0];

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

        document.title = "Our Adventure Story | " + OPTION;
        if (END == 1) {
            this.showNewPartButton.classList.add('hide');
            this.questionPanel.innerHTML = "this is the end of this branch.";
            this.updateEndValueCheckbox.checked = true;
        }
        if (START == 1) {
            this.chooseMessage.classList.add('hide');
            //this.chooseMessage.innerHTML = "Start of the story!";
        }

        if (OPTIONLIST == "" && START == "0") {
            this.ShowEditButton.classList.remove('hide');
        }
        this.showNewPartButton.addEventListener('click', () => {
            this.fromShowed = !this.fromShowed;
            this.ToggleShow();
        });
        document.onload = () => {
            window.alert("Image loaded: " + this.consequenceImage.complete);
        };

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

    public static cap(value: number, min: number, max: number): number {
        return Math.min(Math.max(value, min), max);
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
    private loadFile(url: string): any {
        let request: XMLHttpRequest = new XMLHttpRequest();
        request.open('GET', url, false);
        let data: any;
        request.onload = () => {
            if (request.status >= 200 && request.status < 400) {
                // Success!
                data = JSON.parse(request.responseText);
                console.log('data', data);
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
