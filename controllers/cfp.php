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
<p>We want to nerd out in our room. We want you to propose talks on nerdy, crazy stuff you did with PHP. Not the common conference talk, but let us hear about the crazy shit you are not supposed to tell anyone. Wrote a distributed raytracer, which generates images based on cosmic radiation only using functional paradigms in PHP? You are right on.</p>
<h4>Facts:</h4>
<ul>
    <li>You get free entry to the conference.</li>
    <li>There will be food and drinks for you, for free.</li>
    <li>We will not be able to cover travel or accommodation.</li>
</ul>

<h4>Your talk</h4>
<p>When selecting the talks we will <strong>not</strong> know who proposed the talk.</p>

<h4>Deadline</h4>
<p>You will have to submit your talks until <strong>23th June 2014</strong>.</p>

<?php if (count($errors)): ?>
<h4>Sorry, but:</h4>
<ul class="errors">
    <?php foreach ($errors as $message): ?>
    <li><?=$message?></li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>

<?php if (count($success)): ?>
<h4>Yeah:</h4>
<ul class="success">
    <?php foreach ($success as $message): ?>
    <li><?=$message?></li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>

<form method="POST">
    <fieldset>
        <label for="name">Full Name</label>
        <input type="text" name="name" id="name" placeholder="Your full name" value="<?=htmlspecialchars($model->name)?>">

        <label for="email">E-Mail</label>
        <input type="text" name="email" id="email" placeholder="you@example.com" value="<?=htmlspecialchars($model->email)?>">

        <label for="sum">Calculate: <?=implode(' + ', $_SESSION['numbers'])?> (You gonna beat all the bots with this!)</label>
        <input type="text" name="sum" id="sum" placeholder="$result">

        <span class="checkbox">
            <input type="checkbox" <?php if ($model->first): ?>checked<?php endif; ?> name="first" id="first" value="1">
            <label for="first">Check this, if you are a first-time speaker – we can then provide you with additional mentoring to get your talk right.</label>
        </span>

        <label for="title">Talk Title</label>
        <input type="text" name="title" id="title" placeholder="Talk title" value="<?=htmlspecialchars($model->title)?>">

        <label for="abstract">Abstract (will appear on website)</label>
        <textarea name="abstract" id="abstract"><?=htmlspecialchars($model->abstract)?></textarea>

        <label for="notes">Notes (will not appear on website)</label>
        <textarea name="notes" id="notes"><?=htmlspecialchars($model->notes)?></textarea>

        <input type="submit" name="submit" value="Submit talk" class="button">
    </fieldset>
</form>
