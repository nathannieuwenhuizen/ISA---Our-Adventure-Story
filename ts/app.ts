import { Iproject } from './data';

export default class App {

    public data: Iproject[];
    public showButton: Element;
    public createForm: Element;
    public fromShowed: Boolean = false;
    public consequenceImage: Element;
    public chooseMessage: Element;
    public questionPanel: Element;

    public aboutButton: Element;
    public storyButton: Element;
    public aboutPage: Element;
    public storyPage: Element;

    //the start function goes here
    constructor() {
        this.data = this.loadFile('./assets/projects.json');
        this.showButton = document.getElementsByClassName('createnewPartButton')[0];
        this.createForm = document.getElementsByClassName('createWrapper')[0];
        this.chooseMessage = document.getElementsByClassName('chooseMessage')[0];
        this.questionPanel = document.getElementsByClassName('questionPanel')[0];
        this.consequenceImage = document.getElementsByClassName('consequenceImage')[0];
        
        this.aboutPage = document.getElementsByClassName('aboutwrapper')[0];
        this.aboutButton = document.getElementsByClassName("about")[0];
        this.aboutButton.addEventListener('click', () => { this.ToggleAboutPage(false)});
        this.storyPage = document.getElementsByClassName('storywrapper')[0];
        this.storyButton = document.getElementsByClassName("story")[0];
        this.storyButton.addEventListener('click', () => {this.ToggleAboutPage(true)});

        let objImg = new Image();
        objImg.src = this.consequenceImage.src;
        if(objImg.complete) { 
            this.consequenceImage.classList.add('show');
        }

        if (END == 1) {
            this.showButton.classList.add('hide');
            this.questionPanel.innerHTML = "this is the end of this branch.";
        }
        if (START == 1) {
            this.chooseMessage.classList.add('hide');
            //this.chooseMessage.innerHTML = "Start of the story!";
        }


        this.ToggleAboutPage(true);
        this.showButton.addEventListener('click', () => {
            this.fromShowed = !this.fromShowed;
            this.ToggleShow();
        });
        document.onload = () => {
            //window.alert("Image loaded: " + this.consequenceImage.complete);
        };
    }

    public ToggleShow() {
        if (this.fromShowed) {
            this.createForm.classList.add('show');
            this.createForm.scrollIntoView({
                behavior: 'smooth'
            });
            this.showButton.innerHTML = 'hide';
        } else {
            this.createForm.classList.remove('show');
            this.showButton.innerHTML = 'Create your own path!';

        }
    }

    public ToggleAboutPage(about: boolean) {
        if (about) {
            this.aboutPage.classList.add('hide');
            this.storyPage.classList.remove('hide');
        } else {
            this.aboutPage.classList.remove('hide');
            this.storyPage.classList.add('hide');
        }

    }

    public static cap(value: number, min: number, max: number): number {
        return Math.min(Math.max( value, min), max);

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
