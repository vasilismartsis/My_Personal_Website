<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>sleepydevs.xyz</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v5.15.3/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
</head>
<body id="page-top">
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="/">Martsis Vasileios</a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false"
                aria-label="Toggle navigation">
                Menu
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#projects">Projects</a></li>
                    <li class="nav-item"><a class="nav-link" href="#signup">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Masthead-->
    <header class="masthead">
        <div class="container px-4 px-lg-5 d-flex h-100 align-items-center justify-content-center">
            <div class="d-flex justify-content-center">
                <div class="text-center">
                    <h1 class="mx-auto text-uppercase my-5">Welcome to my site</h1>
                    <h2 class="text-white-50 mx-auto mt-2 mb-5">Through this site you can learn more about me and my work</h2>
                    <a class="btn btn-primary" href="#about">Get Started</a>
                </div>
            </div>
        </div>
    </header>
    <!-- About-->
    <section class="about-section text-center" id="about">
        <div class="container px-4 px-lg-5">
            <img src="assets\img\myPicture.png" style="margin-bottom: 5rem; margin-top: -4rem;">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-lg-8">
                    <h2 class="text-white mb-4">My name is Martsis Vasileios</h2>
                    <p class="text-white-50">
                        I am an IT student at International Hellenic University. My current passion is creating games using the Unity game engine. I also enjoy creating full stack websites as well as desktop and mobile applications.
                        <br>
                        <br>
                        My native language is Greek but I also hold a C2 Proficiency Certificate of Competency in English (University of Michigan), while in the meantime I try to lean German.
                        <br>
                        <br> 
                        In my free time I enjoy playing music, cycling, traveling and of course playing videogames!
                    </p>
                                
                    <div class="myChart" style="margin-bottom: 4rem;">
                        <?php include 'templates/skillbar.html' ?>
                    </div>
                </div>
            </div>
            <img class="img-fluid" src="assets/img/ipad.png"/>
        </div>
    </section>
    <!-- Projects-->
    <section class="projects-section bg-light" id="projects">
    
        <div class="myGithub">
            <div onmouseover="ChangeColor(this)" onmouseout="ChangeColorBack(this)">
            <a href="https://github.com/vasilismartsis" style="color: rgb(0, 42, 110);">
                    <i class="fab fa-github" style="width: 5rem; height: 5rem; color: rgb(0, 42, 110);"></i>
                    <h1 style="height: 2rem; margin-top: -0.5rem; color: rgb(0, 42, 110);"><b>My Github Page</b></h1>
                    </a>
            </div>
        </div>

        <div class="container px-4 px-lg-5">
            <!-- Featured Project Row-->
            <?php
                $projectTypes = array_map('basename', glob('projectTypes/*', GLOB_ONLYDIR));
                //If I want to reverse array
                $projectTypes = array_reverse($projectTypes);
                //If I want to order my list of project types
                //$projectTypes = array("Unity Projects", "PHP Projects");

                foreach($projectTypes as &$projectType)
                {
                    $myfile = fopen("projectTypes/$projectType/description.txt", "r") or die("Unable to open file!");
                    $description = fread($myfile,filesize("projectTypes/$projectType/description.txt"));
                    fclose($myfile);

                    echo 
                    '
                        <div class="row gx-0 mb-4 mb-lg-5 align-items-center">
                            <div class="col-xl-8 col-lg-7"><a href="projectType.php?type='.$projectType.'"><img
                                        class="img-fluid mb-3 mb-lg-0" src="projectTypes/'.$projectType.'/image.jpg"/></a></div>
                            <div class="col-xl-4 col-lg-5">
                                <div class="featured-text text-center text-lg-left">
                                <a href="projectType.php?type='.$projectType.'"><h4>'.$projectType.'</h4></a>
                                    <p class="text-black-50 mb-0">'.$description.'</p>
                                </div>
                            </div>
                        </div>
                    ';
                }
            ?>
            <!-- Project One Row-->
            <!-- <div class="row gx-0 mb-5 mb-lg-0 justify-content-center">
                <div class="col-lg-6"><a href="test.html"><img class="img-fluid" src="assets/img/demo-image-01.jpg"
                            alt="..." /></a></div>
                <div class="col-lg-6">
                    <div class="bg-black text-center h-100 project">
                        <div class="d-flex h-100">
                            <div class="project-text w-100 my-auto text-center text-lg-left">
                                <h4 class="text-white">Misty</h4>
                                <p class="mb-0 text-white-50">An example of where you can put an image of a project, or
                                    anything else, along with a description.</p>
                                <hr class="d-none d-lg-block mb-0 ms-0" />
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
            <!-- Project Two Row-->
            <!-- <div class="row gx-0 justify-content-center">
                <div class="col-lg-6"><a href="unityProject.php?param=1"><img class="img-fluid" src="assets/img/demo-image-02.jpg"
                            alt="..." /></a></div>
                <div class="col-lg-6 order-lg-first">
                    <div class="bg-black text-center h-100 project">
                        <div class="d-flex h-100">
                            <div class="project-text w-100 my-auto text-center text-lg-right">
                                <h4 class="text-white">Mountains</h4>
                                <p class="mb-0 text-white-50">Another example of a project with its respective
                                    description. These sections work well responsively as well, try this theme on a
                                    small screen!</p>
                                <hr class="d-none d-lg-block mb-0 me-0" />
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    </section>
    <!-- Contact-->
    <section class="contact-section bg-black" id="signup" style="background: linear-gradient(to bottom, rgba(0, 0, 0, 0.1) 0%, rgba(0, 0, 0, 0.5) 75%, #000 100%), url('../assets/img/bg-signup.jpg');">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5">
                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="card py-4 h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-map-marked-alt text-primary mb-2"></i>
                            <h4 class="text-uppercase m-0">Address</h4>
                            <hr class="my-4 mx-auto" />
                            <div class="small text-black-50">Thessaloniki, Greece</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="card py-4 h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-envelope text-primary mb-2"></i>
                            <h4 class="text-uppercase m-0">Email</h4>
                            <hr class="my-4 mx-auto" />
                            <div class="small text-black-50"><a href="#!">vasilismartsis@yahoo.gr</a></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="card py-4 h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-mobile-alt text-primary mb-2"></i>
                            <h4 class="text-uppercase m-0">Phone</h4>
                            <hr class="my-4 mx-auto" />
                            <div class="small text-black-50">Better keep that a secret for now!</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="social d-flex justify-content-center">
                <a class="mx-2" href="https://twitter.com/VMartsis"><i class="fab fa-twitter"></i></a>
                <a class="mx-2" href="https://www.facebook.com/vasilismartsis/"><i class="fab fa-facebook-f"></i></a>
                <a class="mx-2" href="https://github.com/vasilismartsis"><i class="fab fa-github"></i></a>
            </div>
        </div>
    </section>
    <!-- Footer-->
    <footer class="footer bg-black small text-center text-white-50">
        <div class="container px-4 px-lg-5">Copyright &copy; My Website 2021</div>
    </footer>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
    <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
    <!-- * *                               SB Forms JS                               * *-->
    <!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
    <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
    <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>

    <script>
        function ChangeColor(o){
            o.style.background = "yellow";
        }
        function ChangeColorBack(o){
            o.style.background = "rgb(179, 170, 255)";
        }
    </script>
</body>

</html>