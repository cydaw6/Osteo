<html>

<head>

  <style>
    body {
      background: #fff;
      padding: 10px;

      color: #000;
      padding: 0px;
      margin: 0px;
      background-color: #00868a4a;
      background-image: url("./img/b1.png");
    }

    aside {
      background: #fff;
      height: 443px;
      width: 166px;
      background: #262626;
    }

    #nav2 ul li div form {
      z-index: 2 !important;
    }


    #nav2 ul {
      padding: 0px;
      margin: 0px;
      border-bottom: 1px solid #1c1f21;
    }

    #nav2 ul li {
      list-style-type: none;
      color: #5a5b5b;
      padding: 5px;
      border-bottom: 1px solid #272a2c;
      border-top: 1px solid #1c1f21;
      overflow: auto;
      cursor: pointer;
    }

    #nav2 ul li:first-child {
      border-top: none;
    }

    .fa {
      color: #18a3fc;
      display: block;
    }

    #nav2 ul li:hover .fa {
      color: #0f82da;
    }

    #nav2 ul li:hover .linktitle {
      color: #6c6d6e;
    }

    #nav2 ul li div {
      float: left;
    }

    .icons {
      padding: 10px;
    }

    .linktitle {
      line-height: 35px;
      padding-left: 10px;
    }


    /* Reponsive menu code */
    @media all and (max-width: 600px) {
      aside {
        width: 60px;
        padding: 0px 10px;
      }

      #nav2 ul li {
        overflow: hidden;
        padding: 0px 10px;
      }

      .linktitle {
        position: absolute;
        z-index: -1;
        left: -50px;
        padding: 29px 15px;
        line-height: 0px;
        background: #222527;
      }

      #nav2 ul li:hover .linktitle {
        left: 80px;
      }

      .icons {
        padding: 20px 10px;
      }

      #nav2 ul li:hover .icons {
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
      }
    }



    #nav2 form input[type=submit] {
      border: none !important;
      color: grey;
      max-width: 100px;
      max-height: 100px;


    }


    #nav2 input[type=submit]:hover {
      color: none !important;
      background-color: #262626 !important;
      transition: none !important;
      border-radius: none !important;
      border: #262626 0px solid !important;
      outline: none !important;

    }

    #nav2 button {
      border: none;
      padding: 0;
      background: none;

    }

    #nav2 div {
      color: grey;
    }
  </style>

</head>

<body>

  <aside id="nav2">
    <ul>
      <form id="form" action="./index.php" method="post">
        <li>
          <div class="icons"><button type="submit" name="profil"><i class="fa fa-heart"></i></div></button>
          <div class="linktitle">Récapitulatif</div>

        </li>
        <li>
          <div class="icons"><a href="./consultations.php"><i class="fa fa-align-justify"></i></div></a><a href="./consultations.php">
            <div class="linktitle">Consultations</div>
          </a>

        </li>
        <li>
          <div class="icons"><a href="./animaux.php"><i class="fa fa-paw"></i></div></a><a href="./animaux.php">
            <div class="linktitle">Animaux</div>
          </a>


        </li>
        <li>
          <div class="icons"><a href="./proprietaires.php"><i class="fa fa-group"></i></div></a><a href="./proprietaires.php">
            <div class="linktitle">Propriétaires</div>
          </a>
          <!-- <input class="subm" type="submit" name="proprietaires" value="Propriétaires">
                -->


        </li>
        <li>
          <div class="icons"><button type="submit" name="medicaments"><i class="fa fa-medkit"></i></div></button>
          <div class="linktitle">
            <input class="subm" type="submit" name="animaux" value="Médicaments">

        </li>
        <li>
          <div class="icons"><button type="submit" name="profil"><i class="fa fa-user"></i></div></button>
          <div class="linktitle">
            <input class="subm" type="submit" name="profil" value="Profil             ">
        </li>

        <li>
          <div class="icons"><a style="text-decoration: none;color: grey;" href="./logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i></i></i></div>
          <div style="color: grey;" class="linktitle">Déconnexion</div></a>
        </li>
      </form>
    </ul>
  </aside>




</body>

</html>