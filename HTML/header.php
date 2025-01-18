<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css"/>
<style>

.icon-bar {
    width: 100%;
    background-color: black;
    height:70px;
    padding :15px 0px 10px 50px ;
    overflow-x: hidden;
    overflow-y: hidden;
    position: relative;
    display: flex;
    align-items: center;
    z-index: 3;
 }

.icon-bar ul {
    list-style-type: none;
    margin-left: 300px;
    overflow: hidden;
    display: block;
 }

.icon-bar li {
    margin-left: 30px;
    display: inline-block;
    color: white;
    font-size: 25px;
 }

.icon-bar li > a {
    color: white;
    text-decoration: none;
}

.icon-bar li a:hover:not(.active) {
    color: cyan;
  }

.icon-bar .active {
    color: orange;
}

.icon-bar li a:hover {
    color: cyan;
 }

 footer {
  position: relative;
  bottom: 0px;
  width: 100%;
  background-color: #111;
  color: white;
}

.main-content {
  display: flex;
}

.nav__actions {
    display: flex;
    align-items: center;
    column-gap: 1rem;
  }
  
  .nav__login {
    font-size: 2rem;
    color: white;
    cursor: pointer;
    transition: .4s;
    margin-left: 25rem;
  }
  
  :is(.nav__login):hover {
  color: yellow;
  }
  
.main-content .box {
  flex-basis: 50%;
  padding: 10px 20px;
}

.social a i {
  color: white;
  text-decoration: none;
}

.content li {
  color: white;
  text-decoration: none;
}

.box h2 {
  font-size: 1.125rem;
  font-weight: 600;
  text-transform: uppercase;
}

.box .content {
  margin: 20px 0 0 0;
}

.box .content p {
  text-align: justify;
}

</style>
<header>
    <div class="icon-bar">
        <a href="../HTML/Homepage.php"><img src="../Media/MainLogo.jpeg" alt="" width="110px" /></a>

            <ul>
                <li><a href="../HTML/Homepage.php">Home</a></li>
                <li><a href="../HTML/Games.php">Games</a></li>
                <li><a href="../HTML/Events.php">Events</a></li>
                <li><a href="../HTML/Community.php">Community</a></li>
                <li><a href="../HTML/AboutUs.php">AboutUS</a></li>
            </ul>

    <div class="nav_action">
       <i class="ri-user-line nav__login" id="login-btn"></i>
        <script>
            var loginBtn = document.getElementById('login-btn');
            loginBtn.addEventListener('click',() => {
        
            window.location.href = 'user.php';
        
    })
        </script>
    </div>
    </div>
</header>