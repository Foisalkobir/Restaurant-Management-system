<?php
require_once('../model/adModel.php');

if (!isset($_SESSION['status'])) {
    header('location: login.html');
    exit();
}

function renderTopBar()
{
    if (isset($_SESSION['user'])) {
        $username = $_SESSION['user']['username'];
        $id = $_SESSION['user']['id'];
        $balance = getUserBalance($id); 
        echo '
        <style>
            /* Styling for the top bar */
            .top-bar {
                width: 100%;
                background-color:#0067ce; /* Primary blue color */
                color: white;
                padding: 10px 20px 10px 20px;
                position: fixed;
                top: 0;
                left: 0;
                z-index: 1000;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .top-bar-title {
                font-size: 1.5em;
                font-weight: bold;
                font-family: Georgia, serif;
            }

            .top-bar-links {
                margin-right: 30px;
                font-size: 1em;
                display: flex;
                gap: 15px;
                align-items: center;
            }

            .top-bar a {
                color: white;
                text-decoration: none;
                font-weight: bold;
                transition: color 0.3s ease;
            }

            .top-bar a:hover {
                color: #ffdd57; /* Soft yellow hover effect */
            }

            /* Prevent content from hiding behind the top bar */
            body {
                padding-top: 60px; /* Adjust based on top-bar height */
                font-family: Arial, sans-serif;
            }
        </style>

        <div class="top-bar">
            <div class="top-bar-title">
                <a href="home.php">AdVerse Studio</a>
            </div>
            <div class="top-bar-links">
                <a href="addBalance.php">Add Balance: ৳'. number_format($balance, 2) .'</a> |
                <a href="profile.php?id=' . htmlspecialchars($id) . '"> Profile: ' . htmlspecialchars($username) . '</a>
            </div>
        </div>
        ';
    } else {
        echo 'User not logged in';
    }
}

?>