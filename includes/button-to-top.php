<head>
    <style>
        #button {
            display: inline-block;
            background-color: #FF9800;
            width: 50px;
            height: 50px;
            text-align: center;
            border-radius: 4px;
            position: fixed;
            bottom: 30px;
            right: 30px;
            transition: background-color .3s,
                opacity .5s, visibility .5s;
            opacity: 0;
            visibility: hidden;
            z-index: 1000;
        }

        #button::after {
            content: "\f077";
            font-family: FontAwesome;
            font-weight: normal;
            font-style: normal;
            font-size: 2em;
            line-height: 50px;
            color: #fff;
        }

        #button:hover {
            cursor: pointer;
            background-color: #333;
        }

        #button:active {
            background-color: #555;
        }

        #button.show {
            opacity: 1;
            visibility: visible;
        }

        /* Styles for the content section */

        .content {
            width: 77%;
            margin: 50px auto;
            font-family: 'Merriweather', serif;
            font-size: 17px;
            color: #6c767a;
            line-height: 1.9;
        }

        @media (min-width: 500px) {
            .content {
                width: 43%;
            }

            #button {
                margin: 30px;
            }
        }

        .content h1 {
            margin-bottom: -10px;
            color: #03a9f4;
            line-height: 1.5;
        }

        .content h3 {
            font-style: italic;
            color: #96a2a7;
        }
    </style>
</head>
<html>


<body>
    <a id="button"></a>
</body>

</html>
<script>
    var btn = $('#button');

    $(window).scroll(function() {
        if ($(window).scrollTop() > 300) {
            btn.addClass('show');
        } else {
            btn.removeClass('show');
        }
    });

    btn.on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({
            scrollTop: 0
        }, '300');
    });
</script>