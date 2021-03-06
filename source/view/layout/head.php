<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Farligt Godt</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css?family=Playfair+Display|Roboto:300" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/main.css">
</head>

<body>


<nav class="nav nav--fixed">
    <div class="container">
        <ul class="nav--left ">
            <li class="nav__item nav__item--burger">
                <a href="#"><i class="icon-burger--white icon--small icon--text"></i></a>
            </li>

            <li class="nav__item @active_page('')"><a href="/">Hjem</a></li>
            <li class="nav__item @active_page('recipie')"><a href="/recipie">Oppskrifter</a></li>
            <li class="nav__item @active_page('nearby')"><a href="/nearby">Kart</a></li>
            <li class="nav__item @active_page('species')"><a href="/species">Arter</a></li>
            <li class="nav__item @active_page('about')"><a href="/about">Visjon</a></li>
        </ul>



       @if(Account::isLoggedIn())

            <ul class="nav--right">
              @if($user->getRank() == 1)<!-- If admin -->
              <li class="nav__item @active_page('admin')">
                  <a href="/admin">Admin</a>
              </li>
              @endif
                <li class="nav__item @active_page('profile')">
                    <a href="/profile"><div class="nav__item--image" style="background-image: url('{{ $user->avatar_thumb }}');"></div>{{ $user->username }}</a>
                </li>
                <li class="nav__item"><a href="/logout">Logg ut</a></li>
            </ul>

       @else

            <ul class="nav--right">
                <li class="nav__item"><a href="/login">Logg inn</a></li>
            </ul>

       @endif




    </div>
</nav>
