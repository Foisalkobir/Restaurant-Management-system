<?php
function renderBottomBar()
{
    echo '
    <style>
        /* Styling for the bottom bar */
        .bottom-bar {
            width: 100%;
            background-color:#0067ce; /* Primary blue color */
            color: white;
            text-align: center;
            margin: 50px 0 0 0;
            padding: 10px 0;
            font-family: Arial, sans-serif;
            position: relative;
        }

        .bottom-bar a {
            color: white;
            text-decoration: none;
            margin: 0 5px;
        }

        .bottom-bar a:hover {
            text-decoration: underline;
        }

        /* Ensure footer is at the bottom of the page */
        body {
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        main {
            flex: 1; /* Pushes footer to the bottom when content is short */
        }
    </style>

    <div class="bottom-bar">
        <p><b>AdVerse Studio</b></p>
        <a href="about.html">About Us</a> | 
        <a href="terms.html">Terms and Conditions</a> | 
        <a href="privacy.html">Privacy Policy</a>
        <p>contact@adverse.com | Helpline: +880 1712-345678</p>
        <p>© 2025 AdVerse Studio</p>
    </div>
    ';
}
?>
