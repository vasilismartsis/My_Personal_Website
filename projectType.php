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

<style>
    .gradientBackground
    {
        background: linear-gradient(to bottom, rgba(0, 0, 0, 0.1) 0%, rgba(0, 0, 0, 0.1) 75%, rgba(0, 0, 0, 0.7) 100%), linear-gradient(to top, rgba(0, 0, 0, 0.1) 0%, rgba(0, 0, 0, 0.1) 75%, rgba(0, 0, 0, 0.7) 100%);
    }
</style>

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
                    <li class="nav-item"><a class="nav-link" href="/#about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="/#projects">Projects</a></li>
                    <li class="nav-item"><a class="nav-link" href="/#signup">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Projects-->
    <section class="projects-section bg-light gradientBackground" id="projects">
        <div class="container px-4 px-lg-5">
            <!-- Featured Project Row-->
            <?php
                $projectPath = 'projectTypes/'.$_GET['type'].'/projects';
                $projects = array_map('basename', glob($projectPath.'/*', GLOB_ONLYDIR));
                //If I want to reverse array
                $projects = array_reverse($projects);
                //If I want to order my list of projects
                //$projects = array("Rolling Coin", "OriginalSnake.io");

                foreach($projects as &$project)
                {
                    $myfile = fopen("$projectPath/$project/description.txt", "r") or die("Unable to open file!");
                    $description = fread($myfile,filesize("$projectPath/$project/description.txt"));
                    fclose($myfile);

                    $externalSite = null;
                    if (file_exists("$projectPath/$project/externalSite.txt"))
                    {
                        $myfile = fopen("$projectPath/$project/externalSite.txt", "r") or die("Unable to open file!");
                        $externalSite = fread($myfile,filesize("$projectPath/$project/externalSite.txt"));
                        fclose($myfile);
                    }

                    $githubPage = null;
                    if (file_exists("$projectPath/$project/githubPage.txt"))
                    {
                        $myfile = fopen("$projectPath/$project/githubPage.txt", "r") or die("Unable to open file!");
                        $githubPage = fread($myfile,filesize("$projectPath/$project/githubPage.txt"));
                        fclose($myfile);
                    }

                    echo 
                    '
                        <div class="row gx-0 mb-4 mb-lg-5 align-items-center">
                            <div class="col-xl-8 col-lg-7"><a href="project.php?type='.$_GET['type'].'&project='.$project.'&externalSite='.$externalSite.'"><img
                                        class="img-fluid mb-3 mb-lg-0" src="'.$projectPath.'/'.$project.'/image.jpg" alt="..." /></a></div>
                            <div class="col-xl-4 col-lg-5">
                                <div class="featured-text text-center text-lg-left">
                                <a href="project.php?type='.$_GET['type'].'&project='.$project.'&externalSite='.$externalSite.'" style="color: rgb(51, 84, 80);"><h4>'.$project.'</h4></a>
                                    <p class="text-black-50 mb-0">'.$description.'</p>
                                    <br>
                    ';
                    if ($githubPage != null) 
                    echo 
                    '
                                    <a href="'.$githubPage.'">
                                        <div class="social d-flex justify-content-center">
                                            <p>Github Page:</p>
                                            <i class="fab fa-github" style="width: 3rem; height: 3rem;"></i></a>
                                        </div>
                                    </a>
                    ';
                    echo
                    '
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
</body>

</html>