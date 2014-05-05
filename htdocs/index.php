<?php
// Beware, this is the most awesome PHP Framework, you ever gonna see and need…

class Page {
    public $identifier;
    public $name;
    public $controller;

    public function __construct($identifier, $name, $controller)
    {
        $this->identifier = $identifier;
        $this->name = $name;
        $this->controller = $controller;
    }
}

$pages = array(
    new Page('start', 'Start', 'index.html'),
    new Page('cfp', 'Call For Papers', 'cfp.php'),
);

$selected = isset($_GET['page']) ? $_GET['page'] : 'start';
$current = new Page('404', 'Not Found', '404.php');
foreach ($pages as $page) {
    if ($selected === $page->identifier) {
        $current = $page;
        goto view;
    }
}

view:
ob_start();
include __DIR__ . '/../controllers/' . $current->controller;
$content = ob_get_clean();

?>
<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>FrOSCon PHP Room</title>

    <meta name="description" content="PHP Room at the Free and Open Source Software Conference (FrOSCon)">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="/images/favicon.png">
    <link rel="apple-touch-icon" sizes="57x57" href="/images/favicon-57.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/images/favicon-72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/images/favicon-114.png">

    <link rel="stylesheet" href="/styles/base.css">
    <link rel="stylesheet" href="/styles/custom.css">
</head>
<body>
<header class="row clear">
    <div class="col col-8 mobile-full">
        <h1>Nerdish By Nature<a href="#nerdish">*</a></h1>
        <h2>FrOSCon PHP Room</h2>
        <ul>
        <?php foreach ($pages as $page): ?>
            <li <?php if ($selected === $page->identifier): ?>class="active"<?php endif; ?>><a href="/?page=<?=$page->identifier?>"><?=$page->name?></a></li>
        <?php endforeach; ?>
        </ul>
    </div>
    <div class="col col-4 mobile-full">
        <img src="images/nerdish_by_nature.png" width="300" height="280" alt="Nerdish By Nature" />
    </div>
</header>
<div class="row clear">
    <div class="col"><?=$content?></div>
</div>
<footer class="row clear">
    <p id="nerdish">
        * We know that this is not "correct" English. It is a reference to the song <a href="https://en.wikipedia.org/wiki/Fettes_Brot#Success_with_Nordisch_by_Nature_and_Jein">"Nordisch by Nature" by "Fettes Brot"</a>.
    </p>
    <p>
        <small>Source: <a href="https://github.com/schaumiii/froscon.phpug.do">https://github.com/schaumiii/froscon.phpug.do</a></small>
    </p>
</footer>
</body>
</html>
