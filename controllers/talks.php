<?php

class Model {
    public $name;
    public $email;
    public $first = false;
    public $title;
    public $abstract;
    public $notes;
}

$talks = array();
$errors = array();

try {
    $database = new PDO('sqlite:' . __DIR__ . '/../talks.database');
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $result = $database->query(
        'SELECT
            name, email, first, title, abstract, notes
        FROM
            talks'
    );

    foreach ($result->fetchAll() as $row) {
        $talks[] = $model = new Model();
        $model->name = $row['name'];
        $model->email = $row['email'];
        $model->first = $row['first'];
        $model->title = $row['title'];
        $model->abstract = $row['abstract'];
        $model->notes = $row['notes'];
    }
} catch (\Exception $e) {
    $errors[] = "D'Oh. Internal error. Ping us.";
}

?>
<h3>Talks</h3>

<?php if (count($errors)): ?>
<h4>Sorry, but:</h4>
<ul class="errors">
    <?php foreach ($errors as $message): ?>
    <li><?=$message?></li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>

<?php if (count($talks)): ?>
<ul>
    <?php foreach ($talks as $talk): ?>
    <li>
        <h5><?=htmlentities($talk->title)?></h5>
        <p><?=htmlentities($talk->abstract)?></p>
    </li>
    <?php endforeach; ?>
</ul>
<?php else: ?>
<h4>No talks yet.</h4>
<?php endif; ?>
