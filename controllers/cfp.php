<?php

session_start();

class Model {
    public $name;
    public $email;
    public $first = false;
    public $title;
    public $abstract;
    public $notes;
}

$errors = array();
$success = array();
$model = new Model();

if (isset($_POST['submit'])) {
    $mandatory = array('name', 'email', 'title', 'abstract');
    foreach ($mandatory as $fieldName) {
        if (!isset($_POST[$fieldName]) || empty($_POST[$fieldName])) {
            $errors[] = "Mandatory field $fieldName not specified.";
        } else {
            $model->$fieldName = $_POST[$fieldName];
        }
    }

    $optional = array('first', 'notes');
    foreach ($optional as $fieldName) {
        if (isset($_POST[$fieldName]) && !empty($_POST[$fieldName])) {
            $model->$fieldName = $_POST[$fieldName];
        }
    }

    $sum = isset($_POST['sum']) ? (int) $_POST['sum'] : NaN;
    if (array_sum($_SESSION['numbers']) !== $sum) {
        $errors[] = "Sorry, but you somehow got the sum wrong. Try again!";
    }

    if (count($errors)) {
        goto view;
    }

    try {
        $database = new PDO('sqlite:' . __DIR__ . '/../talks.database');
        $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $insertStatement = $database->prepare(
            'INSERT INTO talks
                (name, email, first, title, abstract, notes)
            VALUES
                (:name, :email, :first, :title, :abstract, :notes);'
        );

        $insertStatement->bindValue(':name', $model->name);
        $insertStatement->bindValue(':email', $model->email);
        $insertStatement->bindValue(':first', (int) $model->first);
        $insertStatement->bindValue(':title', $model->title);
        $insertStatement->bindValue(':abstract', $model->abstract);
        $insertStatement->bindValue(':notes', $model->notes);
        $insertStatement->execute();

        $success[] = "Talk submitted. Feel free to add another one.";
        $model = new Model();
    } catch (\Exception $e) {
        $errors[] = "D'Oh. Internal error. Ping us.";
    }
}

view:
$_SESSION['numbers'] = array_map(
    function () {
        return mt_rand(-10, 10);
    },
    range(1, mt_rand(2, 4))
);

?>
<h3>Call For Papers</h3>
<p>
    The scope of PHP developers has changed dramatically: if you have built your applications from scratch in
    pure PHP back in the days, you can trust in numerous frameworks covering all relevant topics, from routing over
    security aspects to dependency injection nowadays. Also, there is a sophisticated tooling, a stable infrastructure
    and the achievements of DevOps culture; and to be honest, the classic LAMP stack has become part of the
    web devlopment history. The range of skills to be covered and to be handled is way broader in
    modern days – and thus more exciting. This is exactly what we want to represent at the PHP track at FrOSCon.
</p>
<h4>Facts:</h4>
<ul>
    <li>You get free entry to the conference.</li>
    <li>There will be food and drinks for you, for free.</li>
    <li>We will not be able to cover travel or accommodation.</li>
    <li>Read more about the <a href="/#audience">audience</a> and the <a href="/#coc">Code of Conduct</a></li>
</ul>

<h4>Anonymous proposals – fair selection</h4>
<p>When selecting the talks we will <strong>not</strong> know who proposed the talk.</p>

<h4>Deadline</h4>
<p>You will have to submit your talks until <strong>25rd June 2017</strong>.</p>

<h4>Possible Topics</h4>
<ul>
    <li>Dealing with Legacy</li>
    <li>Frameworks</li>
    <li>Tooling - Testing / Building / Deployment</li>
    <li>
        <ul>
            <li>PHP Devs in Frontend Environments</li>
            <li>DevOps</li>
        </ul>
    </li>
</ul>

<?php if (count($errors)) { ?>
<h4>Sorry, but:</h4>
<ul class="errors">
    <?php foreach ($errors as $message) { ?>
    <li><?php echo $message?></li>
    <?php } ?>
</ul>
<?php } ?>

<?php if (count($success)) { ?>
<h4>Yeah:</h4>
<ul class="success">
    <?php foreach ($success as $message) { ?>
    <li><?php echo $message; ?></li>
    <?php } ?>
</ul>
<?php } ?>

<form method="POST">
    <fieldset>
        <label for="name">Full Name</label>
        <input type="text" name="name" id="name" placeholder="Your full name" value="<?php echo htmlspecialchars($model->name); ?>">

        <label for="email">E-Mail</label>
        <input type="text" name="email" id="email" placeholder="you@example.com" value="<?php echo htmlspecialchars($model->email); ?>">

        <label for="sum">Calculate: <?php echo implode(' + ', $_SESSION['numbers']); ?> (You gonna beat all the bots with this!)</label>
        <input type="text" name="sum" id="sum" placeholder="$result">

        <span class="checkbox">
            <input type="checkbox" <?php ($model->first ? 'checked' : '' ); ?> name="first" id="first" value="1">
            <label for="first">Check this, if you are a first-time speaker – we can then provide you with additional mentoring to get your talk right.</label>
        </span>

        <label for="title">Talk Title</label>
        <input type="text" name="title" id="title" placeholder="Talk title" value="<?php echo htmlspecialchars($model->title); ?>">

        <label for="abstract">Abstract (will appear on website)</label>
        <textarea name="abstract" id="abstract"><?php echo htmlspecialchars($model->abstract); ?></textarea>

        <label for="notes">Notes (will not appear on website)</label>
        <textarea name="notes" id="notes"><?php echo htmlspecialchars($model->notes); ?></textarea>

        <input type="submit" name="submit" value="Submit talk" class="button">
    </fieldset>
</form>
