define(["require", "exports"], function (require, exports) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var subjects;
    (function (subjects) {
        subjects["ToolsForThinking"] = "Tools for Thinking";
        subjects["ECTTP"] = "Empowering Creative Thinking through Programming";
        subjects["Tekenen"] = "Tekenen";
        subjects["Anim"] = "Animatie";
        subjects["PersoonlijkeEffectiviteit"] = "Persoonlijke Effectiviteit";
        subjects["ArtMediaMe"] = "Art, Media & Me";
        subjects["LookandInpsire"] = "Look & Inspire";
        subjects["GravischeVormgeving"] = "Grafische Vormgeving";
        subjects["Usability"] = "Usability";
        subjects["PrinciplesofInteractionDesign"] = "Principles of Interaction Design";
        subjects["research"] = "Research";
    })(subjects = exports.subjects || (exports.subjects = {}));
    var teachers;
    (function (teachers) {
        teachers["stephan"] = "Stephan Duquesnoy";
        teachers["raf"] = "Raf Croonen";
        teachers["corne"] = "Corn\u00E9 van Delft";
        teachers["norbert"] = "Norbert";
        teachers["ton"] = "Ton Markus";
        teachers["chris"] = "Christian Roth";
        teachers["valentijn"] = "";
    })(teachers = exports.teachers || (exports.teachers = {}));
    var HomeWork = /** @class */ (function () {
        function HomeWork() {
            this.messages = [
                'Howdy Parthner!',
                'Hello world',
                'Sup brother!',
                '010101000101... just kidding!',
                'Click on the left for flocking algorithm',
                'Do I look good?',
                'Good moring! or... midday? ...evening?',
                'Welcome!',
                'Hey! :)',
                'Hello!',
                'Hello visitor',
                'Greetings traveler!',
                'Hey.........BROTHER! (rip avicii)',
                'I\'m Nathan!',
                'Cheers!',
                'Zzzzz...',
                'This is a message',
                'Wow, I didn\'t saw you there!',
                'Uhmmm... hello?',
                'Wanna hear a joke?\n Me neither!',
                'I\'m a programmer!',
                'Wanna make games?',
                '*type... type... type*',
                'Good day!',
                '8 bytes walk into a bar, the bartenders asks “What will it be?” One of them says, “Make us a double.”',
                'Chuck Norris writes code that optimizes itself.',
                '99 little bugs in the code, \n 99 little bugs,\n Take one down, Patch it around, \n 117 little bugs in the code.',
                'In order to understand recursion, you must first understand recursion.',
                'error log 101... wait! You did fount out!',
                '0, 1, 2, 3, 4, 5!',
                'Did you know you are a guest?',
                'Heeeeeeeeeey',
                'Aloha!',
                'Goedendag meneer! (dutch)',
                'Can you give me comany?',
                'Whalla!',
                'Hmm... interesting',
                'B...E...A...utiful!',
                '1337',
                'LEEROY JENKINS!',
                'Us gamers must unite!',
                'Programmer = A machine that turns coffee into code.',
                '<a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ"> Important code tutorial! </a>',
                'Did you already get rick rolled? If not, then refresh until you do!',
                'Do you wanna see awesome works?',
                'Sup!',
                'Whats up!',
                'Wahtsaaaap!',
                'Konichua, visitor-chan!',
                'I\m a seak bubble!'
            ];
            this.data = [
                //tekenen!
                {
                    subject: subjects.Tekenen,
                    teachers: teachers.stephan,
                    year: 1,
                    link: 'https://drive.google.com/open?id=1VCD4DWhc0QU0v3X3uBlV7ayw2pUm9qc4',
                    link_name: 'Teken les 1',
                    description: '20-9-2018'
                },
                {
                    subject: subjects.Tekenen,
                    teachers: teachers.stephan,
                    year: 1,
                    link: 'https://drive.google.com/open?id=1C-fQ5E5wbz4tpvDdzyJZ8ZbuXxzVJ1u-',
                    link_name: 'Teken les 2',
                    description: '27-9-2018'
                },
                {
                    subject: subjects.Tekenen,
                    teachers: teachers.stephan,
                    year: 1,
                    link: 'https://drive.google.com/open?id=1PLXliwgRvGTDfXcv2QBBszqS9_yx2_oB',
                    link_name: 'Teken les 3',
                    description: '4-10-2018'
                },
                {
                    subject: subjects.Tekenen,
                    teachers: teachers.stephan,
                    year: 1,
                    link: 'https://drive.google.com/open?id=1UYy5v4fCID002I4_Hkj2VZf-a0qnvuAP',
                    link_name: 'Teken les 4',
                    description: '11-10-2018'
                },
                //animation
                {
                    subject: subjects.Anim,
                    teachers: teachers.raf,
                    year: 1,
                    link: 'https://drive.google.com/open?id=1oLHaEW_e6-ty3taGVrAE-60Wk2eWRTeW',
                    link_name: 'keyframing and inbetweens',
                    description: '23-9-2018'
                },
                {
                    subject: subjects.Anim,
                    teachers: teachers.raf,
                    year: 1,
                    link: 'https://drive.google.com/open?id=1-T40Ky6FeS-45NzgJIiA9Np8n_pTpFK_',
                    link_name: 'Stopmotion',
                    description: '4-10-2108'
                },
                {
                    subject: subjects.Anim,
                    teachers: teachers.raf,
                    year: 1,
                    link: 'https://drive.google.com/open?id=1A3MKwjnCq1MG-g9oZJMoMDAYkA1DXG0d',
                    link_name: 'Pixelation',
                    description: '9-10-2018'
                },
                {
                    subject: subjects.Anim,
                    teachers: teachers.raf,
                    year: 1,
                    link: 'https://drive.google.com/open?id=1IYWY6GIOjyb02KebuAaZILhBAv1XRQTo',
                    link_name: 'Beeld voor beeld in de computer',
                    description: '16-10-2018'
                },
                //look and inpire
                {
                    subject: subjects.LookandInpsire,
                    teachers: teachers.corne,
                    year: 1,
                    link: 'https://drive.google.com/open?id=1xkP07L8wSYTvEuUukZ-dsykYFTroBIJn',
                    link_name: 'Poster',
                    description: '15-10-2018'
                },
                //tools for thinking
                {
                    subject: subjects.ToolsForThinking,
                    teachers: teachers.corne,
                    year: 1,
                    link: 'https://drive.google.com/open?id=1iLONOT3rD0MKFZr7_gFsLvVB3Y-AzeoV',
                    link_name: 'PMI',
                    description: '23-9-2018'
                },
                //research
                {
                    subject: subjects.research,
                    teachers: teachers.chris,
                    year: 1,
                    link: 'https://drive.google.com/open?id=1iSe7zUbne5pbJHRuZ2zjhGMOblz7N3mQ',
                    link_name: 'Research persona document',
                    description: '15-10-2018'
                }
            ];
        }
        return HomeWork;
    }());
    exports.HomeWork = HomeWork;
});
/*
{
    'subjects': 0,
    year: 1,
    link: '',
    link_name: '',
    description: ''
}
*/
