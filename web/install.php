<?php
ob_start();
set_time_limit(0);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Narrow Jumbotron Template for Bootstrap</title>

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">

        <!-- Custom styles for this template -->
        <style type="text/css">
            /* Space out content a bit */
            body {
                padding-top: 20px;
                padding-bottom: 20px;
            }

            /* Everything but the jumbotron gets side spacing for mobile first views */
            .header,
            .marketing,
            .footer {
                padding-right: 15px;
                padding-left: 15px;
            }

            /* Custom page header */
            .header {
                border-bottom: 1px solid #e5e5e5;
            }
            /* Make the masthead heading the same height as the navigation */
            .header h3 {
                padding-bottom: 19px;
                margin-top: 0;
                margin-bottom: 0;
                line-height: 40px;
            }

            /* Custom page footer */
            .footer {
                padding-top: 19px;
                color: #777;
                border-top: 1px solid #e5e5e5;
            }

            /* Customize container */
            @media (min-width: 768px) {
                .container {
                    max-width: 730px;
                }
            }
            .container-narrow > hr {
                margin: 30px 0;
            }

            /* Main marketing message and sign up button */
            .jumbotron {
                text-align: center;
                border-bottom: 1px solid #e5e5e5;
            }
            .jumbotron .btn {
                padding: 14px 24px;
                font-size: 21px;
            }

            /* Supporting marketing content */
            .marketing {
                margin: 40px 0;
            }
            .marketing p + h4 {
                margin-top: 28px;
            }

            /* Responsive: Portrait tablets and up */
            @media screen and (min-width: 768px) {
                /* Remove the padding we set earlier */
                .header,
                .marketing,
                .footer {
                    padding-right: 0;
                    padding-left: 0;
                }
                /* Space out the masthead */
                .header {
                    margin-bottom: 30px;
                }
                /* Remove the bottom border on the jumbotron for visual effect */
                .jumbotron {
                    border-bottom: 0;
                }
            }

        </style>
    </head>

    <body>

        <div class="container">
            <div class="header">
                <nav>
                    <ul class="nav nav-pills pull-right">
                        <li role="presentation" class="active"><a href="/install.php">Home</a></li>
                        <li role="presentation"><a href="/install.php?step=test">Test</a></li>
                        <li role="presentation"><a href="/install.php?step=install">Install</a></li>
                        <li role="presentation"><a href="/install.php?step=setup">Setup</a></li>
                        <li role="presentation"><a href="/install.php?step=summary">Summary</a></li>
                    </ul>
                </nav>
                <h3 class="text-muted">Project name</h3>
            </div>

            <div class="row">
                <?php
                $step = filter_input(INPUT_GET, 'step');


                switch ($step) {
                    case 'test':
                        require_once __DIR__ . '/../app/SymfonyRequirements.php';

                        $symfonyRequirements = new SymfonyRequirements();

                        $iniPath = $symfonyRequirements->getPhpIniConfigPath();

                        echo '<div class="alert alert-warning"><ul>';
                        echo $iniPath ? sprintf("<li>Configuration file used by PHP: %s</li>", $iniPath) : "<li>WARNING: No configuration file (php.ini) used by PHP!</li>";


                        echo "<li>The PHP CLI can use a different php.ini file</li>";
                        echo "<li>than the one used with your web server.</li>";
                        if ('\\' == DIRECTORY_SEPARATOR) {
                            echo "<li>(especially on the Windows platform)</li>";
                        }
                        echo "<li>To be on the safe side, please also launch the requirements check</li>";
                        echo "<li>from your web server using the web/config.php script.</li>";

                        echo '</ul></div>';

                        /**
                         * Prints a Requirement instance
                         */
                        function echo_requirement(Requirement $requirement) {
                            $result = $requirement->isFulfilled() ? 'OK' : ($requirement->isOptional() ? 'WARNING' : 'ERROR');

                            switch (str_pad($result, 9)) {
                                case 'OK':
                                    echo '<tr class="success">';
                                    break;
                                case 'WARNING':
                                    echo '<tr class="warning">';
                                    break;
                                case 'ERROR':

                                    echo '<tr class="danger">';
                                    break;
                                default:

                                    echo '<tr>';
                                    break;
                            }
                            echo '<td>' . str_pad($result, 9) . '</td>';
                            echo '<td>' . $requirement->getTestMessage() . "</td>";

                            if (!$requirement->isFulfilled()) {
                                echo '<td>' . $requirement->getHelpText() . '</td>';
                            }
                            echo '</tr>';
                        }

                        echo '<table class="table table-striped">';

                        $checkPassed = true;
                        foreach ($symfonyRequirements->getRequirements() as $req) {
                            /** @var $req Requirement */
                            echo_requirement($req);
                            if (!$req->isFulfilled()) {
                                $checkPassed = false;
                            }
                        }


                        foreach ($symfonyRequirements->getRecommendations() as $req) {
                            echo_requirement($req);
                        }

                        echo '</table>';


                        echo '<p><a class="btn btn-lg btn-success" href="/install.php?step=install" role="button">Install</a></p>';

                        break;

                    case 'install':
                        $pwd = __DIR__;
                        echo exec("echo $pwd/../");
                        echo shell_exec("cd .. && composer update -vvv 2>&1");
                        //composer update -vvv && bower update && mysql -u root -p -e "DROP DATABASE IF EXISTS ojs;create database ojs;"
                        //php app/console ojs:install:travis
                        break;

                    default:
                        echo '<div class="jumbotron">
                <h1>Welcome to OJS Install and Setup</h1>
                <p class="lead">Cras justo odio, dapibus ac facilisis in, egestas eget quam. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
                <p><a class="btn btn-lg btn-success" href="/install.php?step=test" role="button">Start</a></p>
            </div>';
                        break;
                }
                ?>
            </div>

            <footer class="footer">
                <p>&copy; Company 2014</p>
            </footer>

        </div> <!-- /container -->


    </body>
</html>
<?php
ob_flush();
