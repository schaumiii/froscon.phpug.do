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

if ('html' === $current->type) { ?>
<h3>Talks</h3>
    <?php if (count($errors)) { ?>
        <h4>Sorry, but:</h4>
        <ul class="errors">
            <?php foreach ($errors as $message) { ?>
            <li><?php echo $message; ?></li>
            <?php } ?>
        </ul>
    <?php } ?>

    <?php if (count($talks)) { ?>
        <ul>
            <?php foreach ($talks as $talk) { ?>
            <li>
                <h5><?php echo htmlentities($talk->title); ?></h5>
                <?php if ($talk->first) { ?>
                    <p><em>First talk</em></p>
                <?php } ?>
                <p><?php echo nl2br(htmlentities(trim($talk->abstract))); ?></p>
            </li>
            <?php } ?>
        </ul>
        <?php } else { ?>
        <h4>No talks yet.</h4>
    <?php } ?>
<?php } else {
    header('Content-Type: application/json');
    echo json_encode($talks);
}
